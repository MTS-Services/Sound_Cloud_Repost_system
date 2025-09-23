<?php

namespace App\Http\Controllers\Backend\User;

use App\Events\AdminNotificationSent;
use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected StripeService $stripeService;
    protected OrderService $orderService;

    public function __construct(StripeService $stripeService, OrderService $orderService)
    {
        $this->stripeService = $stripeService;
        $this->orderService = $orderService;
    }

    public function paymentMethod(string $order_id)
    {
        $data['order'] = $this->orderService->getOrder($order_id);
        return view('frontend.pages.payment_method', $data);
    }

    /**
     * Show the payment form
     */
    public function showPaymentForm(string $order_id)
    {
        $data['order'] = $this->orderService->getOrder($order_id);
        return view('backend.admin.payments.form', $data);
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email_address' => 'required|email',
            // 'currency' => 'sometimes|string|size:3',
            'customer_email' => 'sometimes|email',
        ]);
        $order = $this->orderService->getOrder(encrypt($request->order_id));
        Log::info("Order:" . $order);
        try {
            $paymentIntent = $this->stripeService->createPaymentIntent([
                'amount' => $order->amount,
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $request->order_id ?? null,
                    'customer_email' => $request->customer_email ?? null,
                ],
            ]);
            DB::transaction(function () use ($request, $order, $paymentIntent) {
                Payment::create([
                    'name' => $request->name,
                    'email_address' => $request->email_address,
                    'address' => $request->address ?? null,
                    'currency' => $request->currency ?? 'usd',
                    'postal_code' => $request->postal_code ?? null,
                    'reference' => $request->reference ?? null,
                    'user_urn' => $order->user_urn,
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method ?? null,
                    'notes' => $order->notes ?? null,

                    'payment_gateway' => Payment::PAYMENT_GATEWAY_STRIPE,
                    'payment_provider_id' => $request->payment_provider_id ?? null,
                    'amount' => $order->amount,
                    'credits_purchased' => $order->credits,
                    'status' => $paymentIntent->status,
                    'payment_intent_id' => $paymentIntent->id ?? null,
                    'receipt_url' => $request->receipt_url ?? null,
                    'failure_reason' => $request->failure_reason ?? null,
                    'metadata' => $paymentIntent->metadata->toArray() ?? null,
                    'processed_at' => $request->processed_at ?? null,
                    'creater_id' => $order->creater_id,
                    'creater_type' => $order->creater_type,

                ]);
            });

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => Crypt::encryptString($paymentIntent->id),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'pid' => 'required|string',
        ]);
        try {
            $decryptedId = Crypt::decryptString($request->pid);
            $paymentIntent = $this->stripeService->retrievePaymentIntent($decryptedId);

            // Update payment record
            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => $paymentIntent->status,
                    'payment_method' => $paymentIntent->payment_method ?? null,
                    'processed_at' => $paymentIntent->status === 'succeeded' ? now() : null,
                ]);
            }

            DB::transaction(function () use ($paymentIntent, $payment) {

                $additionalData = [];
                if (isset($payment->name)) {
                    $additionalData['Name'] = $payment->name;
                }
                if (isset($payment->email_address)) {
                    $additionalData['Email'] = $payment->email_address;
                }
                if (isset($payment->address)) {
                    $additionalData['Address'] = $payment->address;
                }
                if (isset($payment->reference)) {
                    $additionalData['Reference'] = $payment->reference;
                }
                $additionalData['Order ID'] = $payment->order_id;
                $additionalData['Payment Gateway'] = $payment->payment_gateway_label;
                $additionalData['Amount'] = $payment->amount;
                $additionalData['Currency'] = $payment->currency;
                if (isset($payment->credits_purchased)) {
                    $additionalData['Credits'] = $payment->credits_purchased;
                }
                if (isset($payment->receipt_url)) {
                    $additionalData['Receipt URL'] = $payment->receipt_url;
                }

                $order = Order::findOrFail($payment->order_id);

                CreditTransaction::create([
                    'receiver_urn' => $order->user_urn,
                    'transaction_type' => CreditTransaction::TYPE_PURCHASE,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'source_id' => $order->id,
                    'source_type' => Order::class,
                    'amount' => $order->amount,
                    'credits' => $order->credits ?? 0,
                    'description' => 'Purchased ' . $order->credits . ' credits for ' . $order->amount . ' ' . $payment->currency,
                    'creater_id' => $order->creater_id,
                    'creater_type' => $order->creater_type,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                ]);

                $userNotification = CustomNotification::create([
                    'receiver_id' => user()->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Payment successful',
                        'message' => 'You have successfully made a payment.',
                        'description' => 'You have successfully made a payment.' . ' ' . $paymentIntent->amount . ' ' . $paymentIntent->currency,
                        'icon' => 'dollar-sign',
                        'additional_data' => $additionalData
                    ]
                ]);
                $adminNotification = CustomNotification::create([
                    'sender_id' => user()->id,
                    'sender_type' => User::class,
                    'type' => CustomNotification::TYPE_ADMIN,
                    'message_data' => [
                        'title' => 'Payment successful',
                        'message' => 'User ' . user()->name . ' has successfully made a payment.',
                        'description' => 'User ' . user()->name . ' has successfully made a payment.' . ' ' . $paymentIntent->amount . ' ' . $paymentIntent->currency,
                        'icon' => 'dollar-sign',
                        'additional_data' => $additionalData
                    ]
                ]);
                broadcast(new UserNotificationSent($userNotification));
                broadcast(new AdminNotificationSent($adminNotification));
            });

            return view('backend.admin.payments.success', compact('payment', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('user.payment.form', encrypt($payment->order_id))->with('error', 'Payment verification failed');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function paymentCancel(Request $request)
    {
        return view('backend.admin.payments.cancel');
    }




    // Paypal Payment

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
            if ($order->source_type == Credit::class) {
                CreditTransaction::create([
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
            }
            $payment = Payment::create([
                'user_urn' => $order->user_urn,
                'order_id' => $order->id,
                'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
                'notes' => $order->notes,
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
                'return_url' => route('user.payment.paypal.paymentSuccess') . '?reference=' . $reference,
                'cancel_url' => route('user.payment.paypal.paymentCancel') . '?reference=' . $reference
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
