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

        DB::transaction(function () use (&$payment, $order, $amount, $credit, $reference) {
            // ✅ 1. Create CreditTransaction first
            $creditTransaction = CreditTransaction::create([
                'receiver_urn' => $order->user_urn,
                'transaction_type' => CreditTransaction::TYPE_PURCHASE,
                'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                'source_id' => $order->id,
                'source_type' => Order::class,
                'amount' => $order->amount,
                'credits' => $order->credits,
                'metadata' => json_encode(['via' => 'PayPal']),
                'description' => 'Purchased ' . $order->credits . ' credits for ' . $order->amount . ' ' . 'usd',
                'creater_id' => $order->creater_id,
                'creater_type' => $order->creater_type,
            ]);

            $payment = Payment::create([
                'user_urn' => $order->user_urn,
                'order_id' => $order->id,
                'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
                'amount' => $order->amount,
                'credits_purchased' => $order->credits,
                'status' => Payment::STATUS_PROCESSING,
                'payment_intent_id' => $paymentIntent->id ?? null,
                'reference' => $reference,
                'creater_id' => $order->creater_id,
                'creater_type' => $order->creater_type,

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
                        "value" => $amount
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
            if (!$payment) {
                Log::warning('Payment not found for reference.', ['reference' => $reference]);
                session()->flash('error', "Payment not found.");
                return redirect(route('user.add-credits'));
            }

            if (
                $order['status'] === 'COMPLETED' &&
                isset($order['purchase_units'][0]['payments']['captures'][0])
            ) {
                $capture = $order['purchase_units'][0]['payments']['captures'][0];
                $amount = $capture['amount']['value'] ?? null;
                $currency = $capture['amount']['currency_code'] ?? 'USD';
                $receiptUrl = $capture['links'][0]['href'] ?? null;
                $payer = $order['payer'] ?? [];
                $payerName = ($payer['name']['given_name'] ?? '') . ' ' . ($payer['name']['surname'] ?? '');
                $payerEmail = $payer['email_address'] ?? null;
                $shippingAddress = $order['purchase_units'][0]['shipping']['address'] ?? [];
                $address = $shippingAddress['address_line_1'] ?? null;
                $postalCode = $shippingAddress['postal_code'] ?? null;




                $payment->update([
                    'payment_provider_id' => $paymentProviderId,
                    'status' => Payment::STATUS_SUCCEEDED,
                    'currency' => $currency,
                    'payment_intent_id' => $paymentProviderId,
                    'receipt_url' => $receiptUrl,
                    'name' => $payerName,
                    'email_address' => $payerEmail,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'metadata' => json_encode($order),
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
            $payment->update(['status' => Payment::STATUS_CANCELED]);
        }
        Log::info('Payment Cancel Callback', ['reference' => $reference, 'payment' => $payment]);
        session()->flash('error', "Payment was canceled.");
        return redirect(route('user.add-credits'));
    }
}
