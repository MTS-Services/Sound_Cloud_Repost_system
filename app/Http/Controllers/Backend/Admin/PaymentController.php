<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditTransaction;
use App\Models\Payment;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;
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

    public function paymentMethod( string $credit_id)
    {
        $data['order'] = $this->orderService->getOrder($credit_id);
        return view('frontend.pages.payment_method', $data);
    }

    /**
     * Show the payment form
     */
    public function showPaymentForm(Request $request, string $order_id)
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
            'amount' => 'required|numeric|min:0.50',
            'credits' => 'required|numeric|min:0.50',
            'currency' => 'sometimes|string|size:3',
            'customer_email' => 'sometimes|email',
        ]);

        try {
            $paymentIntent = $this->stripeService->createPaymentIntent([
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'usd',
                'metadata' => [
                    'order_id' => $request->order_id ?? null,
                    'customer_email' => $request->customer_email ?? null,
                ],
            ]);
            DB::commit([
                $creditTransaction = CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'transaction_type' => CreditTransaction::TYPE_PURCHASE,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'amount' => $request->amount,
                    'credits' => $request->credits,
                    'metadata' => $paymentIntent->metadata->toArray(),
                    'source_type' => 'test',
                    'source_id' => 000
                ]),
                // Store payment record
                Payment::create([
                    'user_urn' => user()->urn,
                    'payment_gateway' => \App\Models\Payment::PAYMENT_METHOD_STRIPE,
                    'credits_purchased' => $request->amount,
                    'credit_transaction_id' => $creditTransaction->id,
                    'exchange_rate' => 1,
                    'payment_provider_id' => $paymentIntent->id,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $request->amount,
                    'currency' => $request->currency ?? 'usd',
                    'status' => $paymentIntent->status,
                    'metadata' => $paymentIntent->metadata->toArray(),
                ])
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to create payment intent'
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);
        try {
            $paymentIntent = $this->stripeService->retrievePaymentIntent($request->payment_intent_id);
            // Update payment record
            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => $paymentIntent->status,
                    'payment_method' => $paymentIntent->payment_method ?? null,
                    'processed_at' => $paymentIntent->status === 'succeeded' ? now() : null,
                ]);
            }
            return view('backend.admin.payments.success', compact('payment', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('f.payment.form')->with('error', 'Payment verification failed');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function paymentCancel()
    {
        return view('backend.admin.payments.cancel');
    }

    /**
     * Handle Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentIntentFailed($paymentIntent);
                break;

            default:
                Log::info('Received unknown event type: ' . $event['type']);
        }

        return response('Webhook handled', 200);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent['id'])->first();

        if ($payment) {
            $payment->update([
                'status' => 'succeeded',
                'paid_at' => now(),
            ]);

            Log::info('Payment succeeded: ' . $paymentIntent['id']);
        }
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent['id'])->first();

        if ($payment) {
            $payment->update([
                'status' => 'failed',
            ]);

            Log::info('Payment failed: ' . $paymentIntent['id']);
        }
    }
}
