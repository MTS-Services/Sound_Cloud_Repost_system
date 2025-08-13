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
            'currency' => 'sometimes|string|size:3',
            'customer_email' => 'sometimes|email',
        ]);
        $order = $this->orderService->getOrder(encrypt($request->order_id));
        try {
            $paymentIntent = $this->stripeService->createPaymentIntent([
                'amount' => $order->amount,
                'currency' => $request->currency ?? 'usd',
                'metadata' => [
                    'order_id' => $request->order_id ?? null,
                    'customer_email' => $request->customer_email ?? null,
                ],
            ]);
            DB::transaction(function () use ($request, $order, $paymentIntent) {

                if ($order->source_type == Credit::class) {
                    CreditTransaction::create([
                        'receiver_urn' => $order->user_urn,
                        'transaction_type' => CreditTransaction::TYPE_PURCHASE,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                        'source_id' => $order->id,
                        'source_type' => Order::class,
                        'amount' => $order->amount,
                        'credits' => $order->credits,
                        'description' => 'Purchased ' . $order->credits . ' credits for ' . $order->amount . ' ' . $request->currency,
                        'creater_id' => $order->creater_id,
                        'creater_type' => $order->creater_type,

                    ]);
                }


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
            Log::error('Payment intent creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to create payment intent ' . $e->getMessage(),
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
            // dd($paymentIntent);

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
                if (isset($payment->payment_method)) {
                    $additionalData['Payment Method'] = $payment->payment_method;
                }
                $additionalData['Payment Gateway'] = $payment->payment_gateway_label;
                $additionalData['Amount'] = $payment->amount;
                $additionalData['Currency'] = $payment->currency;
                if (isset($payment->credits_purchased)) {
                    $additionalData['Credits'] = $payment->credits_purchased;
                }
                if (isset($payment->receipt_url)) {
                    $additionalData['Receipt URL'] = $payment->receipt_url;
                }

                $userNotification = CustomNotification::create([
                    'receiver_id' => user()->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Payment successful',
                        'message' => 'You have successfully made a payment.',
                        'description' => 'You have successfully made a payment.' . ' ' . $paymentIntent->amount . ' ' . $paymentIntent->currency,
                        'icon' => 'banknote',
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
                        'icon' => 'banknote',
                        'additional_data' => $additionalData
                    ]
                ]);
                broadcast(new UserNotificationSent($userNotification));
                broadcast(new AdminNotificationSent($adminNotification));
            });

            return view('backend.admin.payments.success', compact('payment', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('f.payment.form')->with('error', 'Payment verification failed');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function paymentCancel(Request $request)
    {
        return view('backend.admin.payments.cancel');
    }

    /**
     * Handle Stripe webhooks
     */
    // public function handleWebhook(Request $request)
    // {
    //     $payload = $request->getContent();
    //     $sig_header = $request->header('Stripe-Signature');
    //     $endpoint_secret = config('services.stripe.webhook.secret');

    //     try {
    //         $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    //     } catch (\UnexpectedValueException $e) {
    //         Log::error('Invalid payload: ' . $e->getMessage());
    //         return response('Invalid payload', 400);
    //     } catch (\Stripe\Exception\SignatureVerificationException $e) {
    //         Log::error('Invalid signature: ' . $e->getMessage());
    //         return response('Invalid signature', 400);
    //     }

    //     // Handle the event
    //     switch ($event['type']) {
    //         case 'payment_intent.succeeded':
    //             $paymentIntent = $event['data']['object'];
    //             $this->handlePaymentIntentSucceeded($paymentIntent);
    //             break;

    //         case 'payment_intent.payment_failed':
    //             $paymentIntent = $event['data']['object'];
    //             $this->handlePaymentIntentFailed($paymentIntent);
    //             break;

    //         default:
    //             Log::info('Received unknown event type: ' . $event['type']);
    //     }

    //     return response('Webhook handled', 200);
    // }

    // private function handlePaymentIntentSucceeded($paymentIntent)
    // {
    //     $payment = Payment::where('payment_intent_id', $paymentIntent['id'])->first();

    //     if ($payment) {
    //         $payment->update([
    //             'status' => 'succeeded',
    //             'paid_at' => now(),
    //         ]);

    //         Log::info('Payment succeeded: ' . $paymentIntent['id']);
    //     }
    // }

    // private function handlePaymentIntentFailed($paymentIntent)
    // {
    //     $payment = Payment::where('payment_intent_id', $paymentIntent['id'])->first();

    //     if ($payment) {
    //         $payment->update([
    //             'status' => 'failed',
    //         ]);

    //         Log::info('Payment failed: ' . $paymentIntent['id']);
    //     }
    // }
}
