<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\UserPlan;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;

class PayPalSubscriptionController extends Controller
{
    /**
     * Create PayPal subscription plan
     */
    public function createPaypalSubscriptionPlan($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
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
                    'return_url' => route('user.payment.success', ['order_id' => $order->id]),
                    'cancel_url' => route('user.payment.cancel', ['order_id' => $order->id])
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

                // Update UserPlan
                $userPlan->update([
                    'status' => UserPlan::STATUS_ACTIVE,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'next_billing_date' => $nextBillingDate,
                    'auto_renew' => true,
                ]);

                Log::info('UserPlan updated after PayPal subscription', [
                    'user_plan_id' => $userPlan->id,
                    'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s')
                ]);

                // Create initial payment record
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

                // Send notification to user
                $user = User::where('urn', $order->user_urn)->first();
                if ($user) {
                    $notification = \App\Models\CustomNotification::create([
                        'receiver_id' => $user->id,
                        'receiver_type' => User::class,
                        'type' => \App\Models\CustomNotification::TYPE_USER,
                        'message_data' => [
                            'title' => 'Subscription Activated',
                            'message' => 'Your subscription has been activated successfully.',
                            'description' => 'Your ' . $userPlan->plan->name . ' subscription is now active.',
                            'icon' => 'check-circle',
                            'additional_data' => [
                                'Plan' => $userPlan->plan->name,
                                'Amount' => '$' . $order->amount,
                                'Next Billing Date' => $nextBillingDate->format('M d, Y'),
                            ]
                        ]
                    ]);
                    broadcast(new \App\Events\UserNotificationSent($notification));
                }

                return redirect()->route('user.dashboard')
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

        return redirect()->route('user.dashboard')
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
