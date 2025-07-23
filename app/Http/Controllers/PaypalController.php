<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaypalController extends Controller
{
    public function paypalPaymentLink()
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paypal.paymentSuccess'),
                'cancel_url' => route('paypal.paymentCancel')
            ],
                "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => '100'
                    ]
                ]
            ]
        ];

        $order = $provider->createOrder($data);
        $url = collect($order['links'])->Where('rel', 'approve')->first()['href'];

        return redirect()->away($url);
    }

    public function paypalPaymentSuccess(Request $request)
    {
        try {
            $token = $request->token;
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $order = $provider->capturePaymentOrder($token);

            if ($order['status'] == 'COMPLETED' && isset($order['purchase_units'][0]['payments']['captures'][0])) {
                $capture = $order['purchase_units'][0]['payments']['captures'][0];
                $amount = $capture['amount']['value'] ?? null;
                $currency = $capture['amount']['currency_code'] ?? 'USD';
                $paymentProviderId = $order['id'] ?? null;
                $receiptUrl = $capture['links'][0]['href'] ?? null;
                $payer = $order['payer'] ?? null;
                $shippingAddress = $order['purchase_units'][0]['shipping']['address'] ?? null;

                if ($amount && $paymentProviderId) {
                    Payment::create([
                        'user_urn' => user()->id,
                        'payment_method' => 'PayPal',
                        'payment_gateway' => Payment::PAYMENT_METHOD_PAYPAL,
                        'payment_provider_id' => $paymentProviderId,
                        'amount' => $amount,
                        'currency' => $currency,
                        'credits_purchased' => $amount,
                        'status' => 'succeeded',
                        'payment_intent_id' => $paymentProviderId,
                        'receipt_url' => $receiptUrl,
                        'name' => $payer['name']['given_name'] . ' ' . $payer['name']['surname'],
                        'email_address' => $payer['email_address'],
                        'address' => $shippingAddress['address_line_1'] ?? null,
                        'postal_code' => $shippingAddress['postal_code'] ?? null,
                        'metadata' => json_encode($order),
                        'processed_at' => now(),
                    ]);

                    session()->flash('success', "Payment was successful!");
                    return redirect(route('user.add-credits'));
                }
            }

            session()->flash('error', "Payment failed. Please try again.");
            return redirect(route('user.add-credits'));
        } catch (\Exception $e) {
            Log::error('PayPal Payment Error: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', "An error occurred while processing the payment.");
            return redirect(route('user.add-credits'));
        }
    }

    public function paypalPaymentCancel()
    {
        session()->flash('error', "Payment was canceled.");
        return redirect(route('user.add-credits'));
    }
}
