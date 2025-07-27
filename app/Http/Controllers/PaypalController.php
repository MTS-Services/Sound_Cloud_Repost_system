<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaypalController extends Controller
{
    public function paypalPaymentLink($encryptedOrderId)
    {

        $orderId = decrypt($encryptedOrderId);
        $order = Order::findOrFail($orderId);

        $amount = $order->amount;
        $credit = $order->credits;
        $reference = Str::uuid();
        $payment = null;

        DB::transaction(function () use (&$payment, $orderId, $amount, $credit, $reference) {
            // ✅ 1. Create CreditTransaction first
            $creditTransaction = CreditTransaction::create([
                'receiver_urn'      => user()->urn,
                'transaction_type'  => CreditTransaction::TYPE_PURCHASE,
                'calculation_type'  => CreditTransaction::CALCULATION_TYPE_DEBIT,
                'status'           => 'processing',
                'amount'            => $amount,
                'credits'           => $credit,
                'metadata'          => json_encode(['via' => 'PayPal']),
                'source_type'       => 'paypal',
                'source_id'         => 0, // placeholder
            ]);

            // ✅ 2. Create Payment linked to that CreditTransaction
            $payment = Payment::create([
                'user_urn'              => user()->urn,
                'payment_method'        => 'PayPal',
                'payment_gateway'       => Payment::PAYMENT_METHOD_PAYPAL,
                'amount'                => $amount,
                'currency'              => 'USD',
                'credits_purchased'     => $credit,
                'status'                => 'processing',
                'reference'             => $reference,
                'processed_at'          => now(),
                'credit_transaction_id' => $creditTransaction->id,
                'order_id'              => $orderId, // if you have this in DB
            ]);
        });

        // Log::info('Payment Created', $payment->toArray());

        // ✅ Continue to PayPal flow
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

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
            $paymentProviderId = $order['id'] ?? null;

            $payment = Payment::where('reference', $reference)->where('status', 'processing')->first();
            if (! $payment) {
                Log::warning('Payment not found for reference.', ['reference' => $reference]);
                session()->flash('error', "Payment not found.");
                return redirect(route('user.add-credits'));
            }

            $creditTransaction = CreditTransaction::find($payment->credit_transaction_id);
            // dd($creditTransaction);

            if (
                $order['status'] === 'COMPLETED' &&
                isset($order['purchase_units'][0]['payments']['captures'][0])
            ) {
                $capture         = $order['purchase_units'][0]['payments']['captures'][0];
                $amount          = $capture['amount']['value'] ?? null;
                $currency        = $capture['amount']['currency_code'] ?? 'USD';
                $receiptUrl      = $capture['links'][0]['href'] ?? null;
                $payer           = $order['payer'] ?? [];
                $payerName       = ($payer['name']['given_name'] ?? '') . ' ' . ($payer['name']['surname'] ?? '');
                $payerEmail      = $payer['email_address'] ?? null;
                $shippingAddress = $order['purchase_units'][0]['shipping']['address'] ?? [];
                $address         = $shippingAddress['address_line_1'] ?? null;
                $postalCode      = $shippingAddress['postal_code'] ?? null;


                $creditTransaction->update([
                    'status'    => 'succeeded',
                ]);

                dd($creditTransaction);

                $payment->update([
                    'payment_provider_id' => $paymentProviderId,
                    'status'              => 'succeeded',
                    'payment_intent_id'   => $paymentProviderId,
                    'receipt_url'         => $receiptUrl,
                    'name'                => $payerName,
                    'email_address'       => $payerEmail,
                    'address'             => $address,
                    'postal_code'         => $postalCode,
                    'metadata'            => json_encode($order),
                ]);

                Log::info('Payment and credit transaction updated', ['reference' => $reference]);

                session()->flash('success', "Payment was successful!");
                return redirect(route('user.add-credits'));
            }

            Log::warning('Payment capture incomplete or failed.', ['reference' => $reference]);
            session()->flash('error', "Payment failed or was not completed.");
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
