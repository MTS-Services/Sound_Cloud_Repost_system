<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaypalController extends Controller
{
    public function paypalPaymentLink(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $reference = Str::uuid();
        $amount = $request->input('amount', '100');

        $payment = Payment::create([
            'user_urn'          => user()->id,
            'payment_method'    => 'PayPal',
            'payment_gateway'   => Payment::PAYMENT_METHOD_PAYPAL,
            'amount'            => $amount,
            'currency'          => 'USD',
            'credits_purchased' => $amount,
            'status'            => 'processing',
            'reference'         => $reference,
            'processed_at'      => now(),
        ]);

        Log::info('Payment Created', $payment->toArray());

        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paypal.paymentSuccess') . '?reference=' . $reference,
                'cancel_url' => route('paypal.paymentCancel') . '?reference=' . $reference
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value"         => $amount
                    ]
                ]
            ]
        ];

        $order = $provider->createOrder($data);
        $url = collect($order['links'])->where('rel', 'approve')->first()['href'];

        return redirect()->away($url);
    }

    public function paypalPaymentSuccess(Request $request)
    {
        $reference = $request->query('reference');

        Log::info('Payment Success Callback', ['reference' => $reference]);

        try {
            $token = $request->token;
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $order = $provider->capturePaymentOrder($token);

            $payment = Payment::where('reference', $reference)->where('status', 'processing')->first();

            Log::info('Payment Fetched', ['payment' => $payment]);

            if ($payment && $order['status'] == 'COMPLETED' && isset($order['purchase_units'][0]['payments']['captures'][0])) {
                $capture = $order['purchase_units'][0]['payments']['captures'][0];
                $amount = $capture['amount']['value'] ?? null;
                $currency = $capture['amount']['currency_code'] ?? 'USD';
                $paymentProviderId = $order['id'] ?? null;
                $receiptUrl = $capture['links'][0]['href'] ?? null;
                $payer = $order['payer'] ?? null;
                $shippingAddress = $order['purchase_units'][0]['shipping']['address'] ?? null;

                $payment->update([
                    'payment_provider_id' => $paymentProviderId,
                    'status'              => 'succeeded',
                    'payment_intent_id'   => $paymentProviderId,
                    'receipt_url'         => $receiptUrl,
                    'name'                => $payer['name']['given_name'] . ' ' . $payer['name']['surname'],
                    'email_address'       => $payer['email_address'],
                    'address'             => $shippingAddress['address_line_1'] ?? null,
                    'postal_code'         => $shippingAddress['postal_code'] ?? null,
                    'metadata'            => json_encode($order),
                ]);

                session()->flash('success', "Payment was successful!");
                return redirect(route('user.add-credits'));
            }

            session()->flash('error', "Payment failed or record not found.");
            return redirect(route('user.add-credits'));
        } catch (\Exception $e) {
            Log::error('PayPal Payment Error: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', "An error occurred while processing the payment.");
            return redirect(route('user.add-credits'));
        }
    }

    public function paypalPaymentCancel(Request $request)
    {
        $reference = $request->query('reference');
        $payment = Payment::where('reference', $reference)->where('status', 'processing')->first();
        if ($payment) {
            $payment->update(['status' => 'canceled']);
        }
        Log::info('Payment Cancel Callback', ['reference' => $reference, 'payment' => $payment]);
        session()->flash('error', "Payment was canceled.");
        return redirect(route('user.add-credits'));
    }
}
