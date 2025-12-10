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
use Carbon\Carbon;
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

            // IMPORTANT: Get period dates from subscription items, not directly from subscription
            $subscriptionItem = $subscription->items->data[0];
            $currentPeriodStart = $subscriptionItem->current_period_start;
            $currentPeriodEnd = $subscriptionItem->current_period_end;

            Log::info('Subscription created successfully', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $currentPeriodStart),
                'current_period_end' => date('Y-m-d H:i:s', $currentPeriodEnd),
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
                    'metadata' => [
                        'subscription_id' => $subscription->id,
                        'customer_id' => $request->customer_id,
                        'current_period_start' => $currentPeriodStart,
                        'current_period_end' => $currentPeriodEnd,
                    ],
                ]);
            }

            // Update UserPlan with CORRECT dates
            $userPlan = UserPlan::where('order_id', $order->id)->first();

            if ($userPlan) {
                $startDate = now();
                // Use subscription item's current_period_end as the next billing date
                $nextBillingDate = \Carbon\Carbon::createFromTimestamp($currentPeriodEnd);

                // Calculate end_date based on billing cycle
                if ($userPlan->billing_cycle === 'yearly') {
                    $endDate = $startDate->copy()->addYear();
                } else {
                    $endDate = $startDate->copy()->addMonth();
                }

                Log::info('Updating UserPlan with dates', [
                    'user_plan_id' => $userPlan->id,
                    'start_date' => $startDate->format('Y-m-d H:i:s'),
                    'end_date' => $endDate->format('Y-m-d H:i:s'),
                    'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s'),
                ]);

                $userPlan->update([
                    'stripe_subscription_id' => $subscription->id,
                    'status' => $subscription->status === 'active'
                        ? UserPlan::STATUS_ACTIVE
                        : UserPlan::STATUS_PENDING,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'next_billing_date' => $nextBillingDate,
                ]);
            }

            return response()->json([
                'success' => true,
                'subscription_id' => Crypt::encryptString($subscription->id),
                'status' => $subscription->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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

            Log::info('Handling subscription success', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'current_period_end' => $subscription->current_period_end,
            ]);

            $payment = Payment::where('subscription_id', $subscription->id)->first();

            if (!$payment) {
                throw new \Exception('Payment record not found');
            }

            // Update payment if not already succeeded
            if ($payment->status !== Payment::STATUS_SUCCEEDED) {
                $payment->update([
                    'status' => Payment::STATUS_SUCCEEDED,
                    'processed_at' => now(),
                ]);
            }

            $subscriptionItem = $subscription->items->data[0];
            DB::transaction(function () use ($payment, $subscriptionItem) {
                $order = Order::findOrFail($payment->order_id);
                $userPlan = UserPlan::where('order_id', $order->id)->first();


                if ($userPlan) {
                    $startDate = now();
                    $nextBillingDate = \Carbon\Carbon::createFromTimestamp($subscriptionItem->current_period_end);

                    // Calculate end_date based on billing cycle
                    if ($userPlan->billing_cycle === 'yearly') {
                        $endDate = $startDate->copy()->addYear();
                    } else {
                        $endDate = $startDate->copy()->addMonth();
                    }

                    Log::info('Activating subscription on success page', [
                        'user_plan_id' => $userPlan->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'next_billing_date' => $nextBillingDate,
                    ]);

                    $userPlan->update([
                        'status' => UserPlan::STATUS_ACTIVE,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'next_billing_date' => $nextBillingDate,
                    ]);
                }

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
        if ($order->source_type == Plan::class) {
            return $this->createPaypalSubscriptionPlan($order);
        } else {
            return $this->createOneTimePaypalPayment($order);
        }
        return redirect()->back()->with('error', 'Failed to create PayPal payment link');
    }

    public function createOneTimePaypalPayment($order)
    {
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

    public function createPaypalSubscriptionPlan($order)
    {
        try {
            $plan = Plan::find($order->source_id);
            $userPlan = UserPlan::where('order_id', $order->id)->first();

            if (!$plan || !$userPlan) {
                throw new \Exception('Plan or UserPlan not found');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Determine billing cycle
            $interval = $userPlan->billing_cycle === 'yearly' ? 'YEAR' : 'MONTH';
            $amount = $userPlan->billing_cycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

            // Get or create product
            $productId = $this->getOrCreatePaypalProduct($provider, $plan);

            // Create unique plan name with timestamp to avoid conflicts
            $planName = $plan->name . ' - ' . ucfirst($interval) . ' (' . time() . ')';

            // Create PayPal billing plan
            $planData = [
                'product_id' => $productId,
                'name' => $planName,
                'description' => $plan->notes ?? 'Subscription plan for ' . $plan->name,
                'billing_cycles' => [
                    [
                        'frequency' => [
                            'interval_unit' => $interval,
                            'interval_count' => 1
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0, // 0 = Infinite billing cycles
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => number_format($amount, 2, '.', ''),
                                'currency_code' => 'USD'
                            ]
                        ]
                    ]
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee' => [
                        'value' => '0',
                        'currency_code' => 'USD'
                    ],
                    'setup_fee_failure_action' => 'CONTINUE',
                    'payment_failure_threshold' => 3
                ]
            ];

            Log::info('Creating PayPal plan', $planData);
            $paypalPlan = $provider->createPlan($planData);

            if (!isset($paypalPlan['id'])) {
                throw new \Exception('Failed to create PayPal plan: ' . json_encode($paypalPlan));
            }

            Log::info('PayPal plan created', ['plan_id' => $paypalPlan['id']]);

            // Create subscription
            $subscriptionData = [
                'plan_id' => $paypalPlan['id'],
                'start_time' => now()->addMinutes(1)->toIso8601String(), // Start 1 minute from now
                'subscriber' => [
                    'name' => [
                        'given_name' => explode(' ', user()->name)[0] ?? 'Customer',
                        'surname' => explode(' ', user()->name)[1] ?? ''
                    ],
                    'email_address' => user()->email
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'en-US',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ],
                    'return_url' => route('user.payment.paypal.subscription-success', ['order_id' => $order->id]),
                    'cancel_url' => route('user.payment.paypal.subscription-cancel', ['order_id' => $order->id])
                ]
            ];

            Log::info('Creating PayPal subscription', $subscriptionData);
            $subscription = $provider->createSubscription($subscriptionData);

            if (!isset($subscription['id'])) {
                throw new \Exception('Failed to create subscription: ' . json_encode($subscription));
            }

            Log::info('PayPal subscription created', [
                'subscription_id' => $subscription['id'],
                'status' => $subscription['status']
            ]);

            // Update UserPlan
            $userPlan->update([
                'paypal_subscription_id' => $subscription['id'],
                'status' => UserPlan::STATUS_PENDING,
            ]);

            // Find approval URL
            $approvalUrl = null;
            if (isset($subscription['links'])) {
                foreach ($subscription['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
            }

            if (!$approvalUrl) {
                throw new \Exception('Approval URL not found in subscription response');
            }

            return redirect()->away($approvalUrl);
        } catch (\Exception $e) {
            Log::error('PayPal subscription creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get or create PayPal product
     */
    protected function getOrCreatePaypalProduct($provider, $plan)
    {
        // Check if product exists in database
        if ($plan->paypal_product_id) {
            try {
                // Verify product still exists in PayPal
                $product = $provider->showProductDetails($plan->paypal_product_id);
                if (isset($product['id'])) {
                    return $plan->paypal_product_id;
                }
            } catch (\Exception $e) {
                Log::warning('Saved PayPal product not found, creating new one', [
                    'product_id' => $plan->paypal_product_id
                ]);
            }
        }

        // Create new product
        $productData = [
            'name' => $plan->name,
            'description' => $plan->notes ?? 'Subscription product for ' . $plan->name,
            'type' => 'SERVICE',
            'category' => 'SOFTWARE',
            'image_url' => config('app.url') . '/images/logo.png', // Optional
            'home_url' => config('app.url')
        ];

        Log::info('Creating PayPal product', $productData);
        $product = $provider->createProduct($productData);

        if (!isset($product['id'])) {
            throw new \Exception('Failed to create PayPal product: ' . json_encode($product));
        }

        // Save product ID
        $plan->update(['paypal_product_id' => $product['id']]);

        return $product['id'];
    }

    /**
     * Handle subscription approval success
     */
    // public function paypalsubscriptionSuccess(Request $request, $orderId)
    // {
    //     try {
    //         $subscriptionId = $request->query('subscription_id');

    //         if (!$subscriptionId) {
    //             throw new \Exception('Subscription ID not found in request');
    //         }

    //         $provider = new PayPalClient;
    //         $provider->setApiCredentials(config('paypal'));
    //         $provider->getAccessToken();

    //         // Get subscription details
    //         $subscription = $provider->showSubscriptionDetails($subscriptionId);

    //         if (!isset($subscription['id'])) {
    //             throw new \Exception('Failed to retrieve subscription details');
    //         }

    //         Log::info('PayPal subscription details retrieved', [
    //             'subscription_id' => $subscription['id'],
    //             'status' => $subscription['status']
    //         ]);

    //         $order = Order::findOrFail($orderId);
    //         $userPlan = UserPlan::where('order_id', $order->id)->where('status', UserPlan::STATUS_PENDING)->first();
    //         Log::info('UserPlan found', ['user_plan_id' => $userPlan->id]);

    //         if (!$userPlan) {
    //             throw new \Exception('UserPlan not found');
    //         }

    //         // PayPal subscription is ACTIVE after approval
    //         if ($subscription['status'] === 'ACTIVE') {
    //             $startDate = now();

    //             // Get billing info from subscription
    //             $billingInfo = $subscription['billing_info'] ?? null;
    //             $nextBillingTime = $billingInfo['next_billing_time'] ?? null;

    //             // Calculate end date based on billing cycle
    //             if ($userPlan->billing_cycle === 'yearly') {
    //                 $endDate = $startDate->copy()->addYear();
    //             } else {
    //                 $endDate = $startDate->copy()->addMonth();
    //             }

    //             // Parse next billing date
    //             $nextBillingDate = $nextBillingTime
    //                 ? Carbon::parse($nextBillingTime)
    //                 : $endDate;

    //             // Update UserPlan
    //             $userPlan->update([
    //                 'status' => UserPlan::STATUS_ACTIVE,
    //                 'start_date' => $startDate,
    //                 'end_date' => $endDate,
    //                 'next_billing_date' => $nextBillingDate,
    //                 'auto_renew' => true,
    //             ]);

    //             Log::info('UserPlan updated after PayPal subscription', [
    //                 'user_plan_id' => $userPlan->id,
    //                 'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s'),
    //                 'userPlan' => json_encode($userPlan),

    //             ]);

    //             // Create initial payment record
    //             Payment::create([
    //                 'user_urn' => $order->user_urn,
    //                 'order_id' => $order->id,
    //                 'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
    //                 'payment_provider_id' => $subscription['subscriber']['payer_id'] ?? null,
    //                 'subscription_id' => $subscriptionId,
    //                 'amount' => $order->amount,
    //                 'currency' => 'USD',
    //                 'status' => Payment::STATUS_SUCCEEDED,
    //                 'is_recurring' => true,
    //                 'notes' => 'Initial PayPal subscription payment',
    //                 'metadata' => [
    //                     'subscription_id' => $subscriptionId,
    //                     'paypal_subscription' => $subscription,
    //                     'is_renewal' => false,
    //                 ],
    //                 'processed_at' => now(),
    //                 'creater_id' => $order->creater_id,
    //                 'creater_type' => $order->creater_type,
    //             ]);

    //             // Send notification to user
    //             $user = User::where('urn', $order->user_urn)->first();
    //             if ($user) {
    //                 $notification = \App\Models\CustomNotification::create([
    //                     'receiver_id' => $user->id,
    //                     'receiver_type' => User::class,
    //                     'type' => \App\Models\CustomNotification::TYPE_USER,
    //                     'message_data' => [
    //                         'title' => 'Subscription Activated',
    //                         'message' => 'Your subscription has been activated successfully.',
    //                         'description' => 'Your ' . $userPlan->plan->name . ' subscription is now active.',
    //                         'icon' => 'check-circle',
    //                         'additional_data' => [
    //                             'Plan' => $userPlan->plan->name,
    //                             'Amount' => '$' . $order->amount,
    //                             'Next Billing Date' => $nextBillingDate->format('M d, Y'),
    //                         ]
    //                     ]
    //                 ]);
    //                 broadcast(new \App\Events\UserNotificationSent($notification));
    //             }

    //             return redirect()->route('user.my-subscription')
    //                 ->with('success', 'Subscription activated successfully!');
    //         }

    //         // If not active, redirect with appropriate message
    //         return redirect()->route('user.dashboard')
    //             ->with('error', 'Subscription activation pending. Status: ' . ($subscription['status'] ?? 'Unknown'));
    //     } catch (\Exception $e) {
    //         Log::error('PayPal subscription success handling failed', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return redirect()->route('user.dashboard')
    //             ->with('error', 'Failed to process subscription: ' . $e->getMessage());
    //     }
    // }


    /**
     * Handle subscription approval success
     */
    public function paypalsubscriptionSuccess(Request $request, $orderId)
    {
        try {
            $subscriptionId = $request->query('subscription_id');

            if (!$subscriptionId) {
                throw new \Exception('Subscription ID not found in request');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Get subscription details
            $subscription = $provider->showSubscriptionDetails($subscriptionId);

            if (!isset($subscription['id'])) {
                throw new \Exception('Failed to retrieve subscription details');
            }

            Log::info('PayPal subscription details retrieved', [
                'subscription_id' => $subscription['id'],
                'status' => $subscription['status']
            ]);

            $order = Order::findOrFail($orderId);
            $userPlan = UserPlan::where('order_id', $order->id)->first();

            if (!$userPlan) {
                throw new \Exception('UserPlan not found');
            }

            // PayPal subscription is ACTIVE after approval
            if ($subscription['status'] === 'ACTIVE') {
                // Use a database transaction to ensure atomic updates
                DB::transaction(function () use ($subscription, $subscriptionId, $order, $userPlan) {
                    $startDate = now();

                    // Get billing info from subscription
                    $billingInfo = $subscription['billing_info'] ?? null;
                    $nextBillingTime = $billingInfo['next_billing_time'] ?? null;

                    // Calculate end date based on billing cycle
                    if ($userPlan->billing_cycle === 'yearly') {
                        $endDate = $startDate->copy()->addYear();
                    } else {
                        $endDate = $startDate->copy()->addMonth();
                    }

                    // Parse next billing date
                    $nextBillingDate = $nextBillingTime
                        ? Carbon::parse($nextBillingTime)
                        : $endDate;

                    // IMPORTANT: Update UserPlan FIRST before creating payment
                    $userPlan->update([
                        'status' => UserPlan::STATUS_ACTIVE,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'next_billing_date' => $nextBillingDate,
                        'auto_renew' => true,
                    ]);

                    Log::info('UserPlan updated after PayPal subscription', [
                        'user_plan_id' => $userPlan->id,
                        'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s'),
                        'userPlan' => json_encode($userPlan)
                    ]);

                    // Update order status BEFORE creating payment
                    // This prevents the Payment boot method from triggering unwanted updates
                    $order->update([
                        'status' => Order::STATUS_COMPLETED
                    ]);

                    // Create payment record LAST
                    // The Payment model's boot method won't affect UserPlan because order is already completed
                    Payment::create([
                        'user_urn' => $order->user_urn,
                        'order_id' => $order->id,
                        'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
                        'payment_provider_id' => $subscription['subscriber']['payer_id'] ?? null,
                        'subscription_id' => $subscriptionId,
                        'amount' => $order->amount,
                        'currency' => 'USD',
                        'status' => Payment::STATUS_SUCCEEDED,
                        'is_recurring' => true,
                        'notes' => 'Initial PayPal subscription payment',
                        'metadata' => [
                            'subscription_id' => $subscriptionId,
                            'paypal_subscription' => $subscription,
                            'is_renewal' => false,
                        ],
                        'processed_at' => now(),
                        'creater_id' => $order->creater_id,
                        'creater_type' => $order->creater_type,
                    ]);

                    $userPlan->update([
                        'status' => UserPlan::STATUS_ACTIVE
                    ]);

                    Log::info('Payment record created successfully', [
                        'order_id' => $order->id,
                        'final_order_status' => $order->fresh()->status,
                        'final_userplan_status' => $userPlan->fresh()->status
                    ]);
                });

                // Send notification to user (outside transaction for better performance)
                $user = User::where('urn', $order->user_urn)->first();
                if ($user) {
                    // Get fresh user plan data after transaction
                    $freshUserPlan = $userPlan->fresh();

                    $notification = \App\Models\CustomNotification::create([
                        'receiver_id' => $user->id,
                        'receiver_type' => User::class,
                        'type' => \App\Models\CustomNotification::TYPE_USER,
                        'message_data' => [
                            'title' => 'Subscription Activated',
                            'message' => 'Your subscription has been activated successfully.',
                            'description' => 'Your ' . $freshUserPlan->plan->name . ' subscription is now active.',
                            'icon' => 'check-circle',
                            'additional_data' => [
                                'Plan' => $freshUserPlan->plan->name,
                                'Amount' => '$' . $order->amount,
                                'Next Billing Date' => $freshUserPlan->next_billing_date->format('M d, Y'),
                            ]
                        ]
                    ]);
                    broadcast(new \App\Events\UserNotificationSent($notification));
                }
                $userPlan->refresh();
                $userPlan->update([
                    'status' => UserPlan::STATUS_ACTIVE
                ]);
                return redirect()->route('user.my-subscription')
                    ->with('success', 'Subscription activated successfully!');
            }


            // If not active, redirect with appropriate message
            return redirect()->route('user.dashboard')
                ->with('error', 'Subscription activation pending. Status: ' . ($subscription['status'] ?? 'Unknown'));
        } catch (\Exception $e) {
            Log::error('PayPal subscription success handling failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.dashboard')
                ->with('error', 'Failed to process subscription: ' . $e->getMessage());
        }
    }

    /**
     * Handle subscription cancellation by user
     */
    public function paypalSubscriptionCancel($orderId)
    {
        Log::info('User cancelled PayPal subscription', ['order_id' => $orderId]);

        return redirect()->route('user.plans')
            ->with('error', 'Subscription setup was cancelled');
    }

    /**
     * Cancel active PayPal subscription
     */
    public function cancelPaypalSubscription(Request $request)
    {
        try {
            $userPlan = UserPlan::findOrFail($request->user_plan_id);

            if (!$userPlan->paypal_subscription_id) {
                throw new \Exception('No PayPal subscription found');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $reason = $request->reason ?? 'Customer requested cancellation';

            // Cancel subscription in PayPal
            $result = $provider->cancelSubscription(
                $userPlan->paypal_subscription_id,
                $reason
            );

            Log::info('PayPal subscription cancelled', [
                'subscription_id' => $userPlan->paypal_subscription_id,
                'result' => $result
            ]);

            // Update UserPlan
            $userPlan->update([
                'status' => UserPlan::STATUS_CANCELED,
                'auto_renew' => false,
                'canceled_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription canceled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal subscription cancellation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription: ' . $e->getMessage()
            ], 500);
        }
    }
}
