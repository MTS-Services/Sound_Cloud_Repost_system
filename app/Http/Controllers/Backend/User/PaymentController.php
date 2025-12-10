<?php

namespace App\Http\Controllers\Backend\User;

use App\Events\AdminNotificationSent;
use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;
use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
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
        $application_settings = ApplicationSetting::whereIn('key', ['stripe_gateway_status', 'paypal_gateway_status'])
            ->pluck('value', 'key');
        $data['stripeStatus'] = $application_settings['stripe_gateway_status'];
        $data['paypalStatus'] = $application_settings['paypal_gateway_status'];
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
     * Create a payment intent (for one-time payments or subscription setup)
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email_address' => 'required|email',
            'customer_email' => 'sometimes|email',
            'order_id' => 'required',
        ]);

        try {
            $order = Order::find($request->order_id);

            if (!$order) {
                Log::error('Order not found', ['order_id' => $request->order_id]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('Processing payment intent', [
                'order_id' => $order->id,
                'source_type' => $order->source_type,
                'amount' => $order->amount
            ]);

            $isSubscription = $order->source_type === Plan::class;

            if ($isSubscription) {
                return $this->createSubscriptionIntent($request, $order);
            } else {
                return $this->createOneTimePaymentIntent($request, $order);
            }
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create setup intent for subscription (collects payment method first)
     */
    protected function createSubscriptionIntent(Request $request, Order $order)
    {
        try {
            Log::info('Creating subscription setup intent', [
                'order_id' => $order->id,
                'user_urn' => $order->user_urn,
            ]);

            $user = User::where('urn', $order->user_urn)->first();

            if (!$user) {
                throw new \Exception('User not found for order');
            }

            // Create or get Stripe customer
            $customer = $this->stripeService->createOrGetCustomer([
                'email' => $request->email_address,
                'name' => $request->name,
                'stripe_customer_id' => $user->stripe_customer_id ?? null,
                'metadata' => [
                    'user_urn' => $order->user_urn,
                    'order_id' => $order->id,
                ],
            ]);

            Log::info('Stripe customer created/retrieved', ['customer_id' => $customer->id]);

            $user->update(['stripe_customer_id' => $customer->id]);

            // Get plan details
            $plan = Plan::find($order->source_id);

            if (!$plan) {
                throw new \Exception('Plan not found');
            }

            $userPlan = UserPlan::where('order_id', $order->id)->first();
            $interval = $userPlan && $userPlan->billing_cycle === 'yearly' ? 'year' : 'month';
            $amount = $interval === 'year' ? $plan->yearly_price : $plan->monthly_price;

            Log::info('Plan details', [
                'plan_id' => $plan->id,
                'interval' => $interval,
                'amount' => $amount
            ]);

            // Create product
            $product = $this->stripeService->createProduct([
                'name' => $plan->name . ' - ' . ucfirst($interval) . ' Plan',
                'description' => $plan->notes,
                'metadata' => [
                    'plan_id' => $plan->id,
                    'order_id' => $order->id,
                ],
            ]);

            // Create price
            $price = $this->stripeService->createPrice([
                'product_id' => $product->id,
                'amount' => $amount,
                'interval' => $interval,
                'currency' => 'usd',
                'metadata' => [
                    'plan_id' => $plan->id,
                    'billing_cycle' => $interval === 'year' ? 'yearly' : 'monthly',
                ],
            ]);

            Log::info('Product and price created', [
                'product_id' => $product->id,
                'price_id' => $price->id
            ]);

            // Create setup intent to collect payment method
            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $customer->id,
                'payment_method_types' => ['card'],
                'usage' => 'off_session',
                'metadata' => [
                    'order_id' => $order->id,
                    'user_urn' => $order->user_urn,
                    'plan_id' => $plan->id,
                    'price_id' => $price->id,
                ],
            ]);

            Log::info('Setup intent created', ['setup_intent_id' => $setupIntent->id]);

            // Create temporary payment record
            DB::transaction(function () use ($request, $order, $customer, $price, $setupIntent) {
                Payment::create([
                    'name' => $request->name,
                    'email_address' => $request->email_address,
                    'address' => $request->address ?? null,
                    'currency' => 'usd',
                    'postal_code' => $request->postal_code ?? null,
                    'user_urn' => $order->user_urn,
                    'order_id' => $order->id,
                    'payment_gateway' => Payment::PAYMENT_GATEWAY_STRIPE,
                    'payment_provider_id' => $customer->id,
                    'amount' => $order->amount,
                    'credits_purchased' => $order->credits,
                    'status' => 'processing',
                    'payment_intent_id' => $setupIntent->id,
                    'is_recurring' => true,
                    'metadata' => [
                        'setup_intent_id' => $setupIntent->id,
                        'customer_id' => $customer->id,
                        'price_id' => $price->id,
                    ],
                    'creater_id' => $order->creater_id,
                    'creater_type' => $order->creater_type,
                ]);

                UserPlan::where('order_id', $order->id)->update([
                    'status' => UserPlan::STATUS_PENDING,
                ]);
            });

            return response()->json([
                'client_secret' => $setupIntent->client_secret,
                'setup_intent_id' => $setupIntent->id,
                'requires_setup' => true,
                'price_id' => $price->id,
                'customer_id' => $customer->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription setup failed', [
                'error' => $e->getMessage(),
                'order_id' => $order->id ?? 'unknown'
            ]);
            throw $e;
        }
    }

    /**
     * Create subscription after setup intent is confirmed
     */
    public function createSubscription(Request $request)
    {
        $request->validate([
            'setup_intent_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'customer_id' => 'required|string',
            'price_id' => 'required|string',
            'order_id' => 'required',
        ]);

        try {
            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('Creating subscription', [
                'order_id' => $order->id,
                'payment_method_id' => $request->payment_method_id
            ]);

            // Create subscription with confirmed payment method
            $subscription = \Stripe\Subscription::create([
                'customer' => $request->customer_id,
                'items' => [['price' => $request->price_id]],
                'default_payment_method' => $request->payment_method_id,
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'order_id' => $order->id,
                    'user_urn' => $order->user_urn,
                ],
            ]);

            Log::info('Subscription created', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status
            ]);

            // Update payment record
            $payment = Payment::where('order_id', $order->id)
                ->where('payment_intent_id', $request->setup_intent_id)
                ->first();

            if ($payment) {
                $payment->update([
                    'subscription_id' => $subscription->id,
                    'payment_intent_id' => $subscription->latest_invoice->payment_intent->id ?? $request->setup_intent_id,
                    'status' => $subscription->status === 'active' ? Payment::STATUS_SUCCEEDED : 'processing',
                    'processed_at' => $subscription->status === 'active' ? now() : null,
                ]);
            }

            // Update UserPlan
            UserPlan::where('order_id', $order->id)->update([
                'stripe_subscription_id' => $subscription->id,
                'status' => $subscription->status === 'active' 
                    ? UserPlan::STATUS_ACTIVE 
                    : UserPlan::STATUS_PENDING,
                'start_date' => now(),
                'next_billing_date' => date('Y-m-d H:i:s', $subscription->current_period_end),
            ]);

            return response()->json([
                'success' => true,
                'subscription_id' => Crypt::encryptString($subscription->id),
                'status' => $subscription->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription creation failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create one-time payment intent
     */
    protected function createOneTimePaymentIntent(Request $request, Order $order)
    {
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
                'currency' => 'usd',
                'postal_code' => $request->postal_code ?? null,
                'user_urn' => $order->user_urn,
                'order_id' => $order->id,
                'payment_gateway' => Payment::PAYMENT_GATEWAY_STRIPE,
                'amount' => $order->amount,
                'credits_purchased' => $order->credits,
                'status' => $paymentIntent->status,
                'payment_intent_id' => $paymentIntent->id,
                'is_recurring' => false,
                'metadata' => $paymentIntent->metadata->toArray() ?? null,
                'creater_id' => $order->creater_id,
                'creater_type' => $order->creater_type,
            ]);
        });

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => Crypt::encryptString($paymentIntent->id),
        ]);
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        // Handle subscription success
        if ($request->has('sid')) {
            return $this->handleSubscriptionSuccessPage($request);
        }

        // Handle one-time payment success
        $request->validate(['pid' => 'required|string']);

        try {
            $decryptedId = Crypt::decryptString($request->pid);
            $paymentIntent = $this->stripeService->retrievePaymentIntent($decryptedId);

            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

            if ($payment) {
                $payment->update([
                    'status' => $paymentIntent->status,
                    'payment_method' => $paymentIntent->payment_method ?? null,
                    'processed_at' => $paymentIntent->status === 'succeeded' ? now() : null,
                ]);
            }

            DB::transaction(function () use ($paymentIntent, $payment) {
                $order = Order::findOrFail($payment->order_id);
                $this->handleOneTimePaymentSuccess($payment, $order, $paymentIntent);
                $this->sendNotifications($payment, $paymentIntent, $order);
            });

            return view('backend.admin.payments.success', compact('payment', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('user.dashboard')
                ->with('error', 'Payment verification failed');
        }
    }

    /**
     * Handle subscription success page
     */
    protected function handleSubscriptionSuccessPage(Request $request)
    {
        try {
            $subscriptionId = Crypt::decryptString($request->sid);
            $subscription = $this->stripeService->retrieveSubscription($subscriptionId);

            $payment = Payment::where('subscription_id', $subscription->id)->first();

            if (!$payment) {
                throw new \Exception('Payment record not found');
            }

            if ($payment->status !== Payment::STATUS_SUCCEEDED) {
                $payment->update([
                    'status' => Payment::STATUS_SUCCEEDED,
                    'processed_at' => now(),
                ]);
            }

            DB::transaction(function () use ($payment, $subscription) {
                $order = Order::findOrFail($payment->order_id);

                UserPlan::where('order_id', $order->id)->update([
                    'status' => UserPlan::STATUS_ACTIVE,
                    'start_date' => now(),
                    'end_date' => date('Y-m-d H:i:s', $subscription->current_period_end),
                    'next_billing_date' => date('Y-m-d H:i:s', $subscription->current_period_end),
                ]);

                $this->sendNotifications($payment, (object)['status' => 'succeeded'], $order);
            });

            $paymentIntent = (object)['status' => 'succeeded'];
            return view('backend.admin.payments.success', compact('payment', 'paymentIntent'));
        } catch (\Exception $e) {
            Log::error('Subscription success handling failed: ' . $e->getMessage());
            return redirect()->route('user.dashboard')
                ->with('error', 'Payment verification failed');
        }
    }

    protected function handleOneTimePaymentSuccess(Payment $payment, Order $order, $paymentIntent)
    {
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
    }

    protected function sendNotifications(Payment $payment, $paymentIntent, Order $order)
    {
        $additionalData = $this->prepareNotificationData($payment);

        $userNotification = CustomNotification::create([
            'receiver_id' => user()->id,
            'receiver_type' => User::class,
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => 'Payment successful',
                'message' => 'You have successfully made a payment.',
                'description' => 'Payment of $' . $payment->amount . ' ' . $payment->currency . ' completed successfully.',
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
                'description' => 'Payment of $' . $payment->amount . ' completed.',
                'icon' => 'dollar-sign',
                'additional_data' => $additionalData
            ]
        ]);

        broadcast(new UserNotificationSent($userNotification));
        broadcast(new AdminNotificationSent($adminNotification));
    }

    protected function prepareNotificationData(Payment $payment): array
    {
        $data = [];
        if (isset($payment->name)) $data['Name'] = $payment->name;
        if (isset($payment->email_address)) $data['Email'] = $payment->email_address;
        if (isset($payment->address)) $data['Address'] = $payment->address;

        $data['Order ID'] = $payment->order_id;
        $data['Payment Gateway'] = $payment->payment_gateway_label;
        $data['Amount'] = '$' . $payment->amount;
        $data['Currency'] = $payment->currency;

        if (isset($payment->credits_purchased)) {
            $data['Credits'] = $payment->credits_purchased;
        }
        if ($payment->is_recurring) {
            $data['Subscription'] = 'Auto-renewable';
        }

        return $data;
    }

    public function paymentCancel(Request $request)
    {
        return view('backend.admin.payments.cancel');
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        try {
            $userPlan = UserPlan::findOrFail($request->user_plan_id);

            if ($userPlan->stripe_subscription_id) {
                $this->stripeService->cancelSubscription(
                    $userPlan->stripe_subscription_id,
                    $request->immediately ?? false
                );
            }

            $userPlan->update([
                'auto_renew' => false,
                'status' => UserPlan::STATUS_CANCELED,
                'canceled_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription canceled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription'
            ], 500);
        }
    }

    // PayPal methods remain the same...
    public function paypalPaymentLink($encryptedOrderId)
    {
        $orderId = decrypt($encryptedOrderId);
        $order = Order::findOrFail($orderId);

        $amount = $order->amount;
        $reference = Str::uuid();

        DB::transaction(function () use (&$payment, $order, $reference) {
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
                    'description' => 'Purchased ' . $order->credits . ' credits',
                    'creater_id' => $order->creater_id,
                    'creater_type' => $order->creater_type,
                ]);
            }

            $payment = Payment::create([
                'user_urn' => $order->user_urn,
                'order_id' => $order->id,
                'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
                'amount' => $order->amount,
                'credits_purchased' => $order->credits,
                'status' => Payment::STATUS_PROCESSING,
                'reference' => $reference,
                'creater_id' => $order->creater_id,
                'creater_type' => $order->creater_type,
            ]);
        });

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('user.payment.paypal.paymentSuccess') . '?reference=' . $reference,
                'cancel_url' => route('user.payment.paypal.paymentCancel') . '?reference=' . $reference
            ],
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $amount
                ]
            ]]
        ];

        $order = $provider->createOrder($data);
        $url = collect($order['links'])->where('rel', 'approve')->first()['href'];

        return redirect()->away($url);
    }

    public function paypalPaymentSuccess(Request $request)
    {
        $reference = $request->query('reference');

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $order = $provider->capturePaymentOrder($request->token);
            $payment = Payment::where('reference', $reference)
                ->where('status', 'processing')
                ->first();

            if (!$payment) {
                session()->flash('error', "Payment not found.");
                return redirect(route('user.add-credits'));
            }

            if ($order['status'] === 'COMPLETED') {
                $capture = $order['purchase_units'][0]['payments']['captures'][0];
                
                $payment->update([
                    'payment_provider_id' => $order['id'],
                    'status' => Payment::STATUS_SUCCEEDED,
                    'currency' => $capture['amount']['currency_code'],
                    'payment_intent_id' => $order['id'],
                    'metadata' => json_encode($order),
                ]);

                session()->flash('success', "Payment was successful!");
            } else {
                session()->flash('error', "Payment failed.");
            }

            return redirect(route('user.add-credits'));
        } catch (\Exception $e) {
            Log::error('PayPal Payment Error: ' . $e->getMessage());
            session()->flash('error', "An error occurred.");
            return redirect(route('user.add-credits'));
        }
    }

    public function paypalPaymentCancel(Request $request)
    {
        $reference = $request->query('reference');
        $payment = Payment::where('reference', $reference)
            ->where('status', 'processing')
            ->first();

        if ($payment) {
            $payment->update(['status' => Payment::STATUS_CANCELED]);
        }

        session()->flash('error', "Payment was canceled.");
        return redirect(route('user.add-credits'));
    }
}