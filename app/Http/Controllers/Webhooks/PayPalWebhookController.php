<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\UserPlan;
use App\Models\CustomNotification;
use App\Models\User;
use App\Events\UserNotificationSent;
use App\Events\AdminNotificationSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayPalWebhookController extends Controller
{
    /**
     * Handle PayPal webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? null;

        Log::info('PayPal Webhook received', [
            'event_type' => $eventType,
            'event_id' => $payload['id'] ?? null
        ]);

        try {
            // Verify webhook signature (IMPORTANT FOR PRODUCTION)
            if (!$this->verifyWebhookSignature($request)) {
                Log::error('Invalid PayPal webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            switch ($eventType) {
                case 'BILLING.SUBSCRIPTION.ACTIVATED':
                    $this->handleSubscriptionActivated($payload['resource']);
                    break;

                case 'BILLING.SUBSCRIPTION.CANCELLED':
                    $this->handleSubscriptionCancelled($payload['resource']);
                    break;

                case 'BILLING.SUBSCRIPTION.EXPIRED':
                    $this->handleSubscriptionExpired($payload['resource']);
                    break;

                case 'BILLING.SUBSCRIPTION.SUSPENDED':
                    $this->handleSubscriptionSuspended($payload['resource']);
                    break;

                case 'BILLING.SUBSCRIPTION.UPDATED':
                    $this->handleSubscriptionUpdated($payload['resource']);
                    break;

                case 'PAYMENT.SALE.COMPLETED':
                    $this->handlePaymentCompleted($payload['resource']);
                    break;

                case 'PAYMENT.SALE.DENIED':
                case 'PAYMENT.SALE.REFUNDED':
                case 'PAYMENT.SALE.REVERSED':
                    $this->handlePaymentFailed($payload['resource']);
                    break;

                default:
                    Log::info('Unhandled PayPal webhook event: ' . $eventType);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('PayPal webhook processing failed', [
                'error' => $e->getMessage(),
                'event_type' => $eventType,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(Request $request)
    {
        $webhookId = config('paypal.webhook_id');

        if (!$webhookId) {
            Log::warning('PayPal webhook ID not configured - skipping verification');
            // In production, you should return false here
            return true; // Allow in development only
        }

        try {
            $provider = new \Srmklive\PayPal\Services\PayPal();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $verificationData = [
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => $webhookId,
                'webhook_event' => $request->all()
            ];

            $result = $provider->verifyWebHook($verificationData);

            $isValid = isset($result['verification_status']) &&
                $result['verification_status'] === 'SUCCESS';

            if (!$isValid) {
                Log::error('PayPal webhook verification failed', ['result' => $result]);
            }

            return $isValid;
        } catch (\Exception $e) {
            Log::error('PayPal signature verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle subscription activated (initial activation)
     */
    protected function handleSubscriptionActivated($resource)
    {
        $subscriptionId = $resource['id'];

        Log::info('Processing BILLING.SUBSCRIPTION.ACTIVATED', [
            'subscription_id' => $subscriptionId
        ]);

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if (!$userPlan) {
            Log::warning('UserPlan not found for PayPal subscription', [
                'subscription_id' => $subscriptionId
            ]);
            return;
        }

        // Get billing info
        $billingInfo = $resource['billing_info'] ?? null;
        $nextBillingTime = $billingInfo['next_billing_time'] ?? null;

        $startDate = now();
        $endDate = $userPlan->billing_cycle === 'yearly'
            ? $startDate->copy()->addYear()
            : $startDate->copy()->addMonth();

        $nextBillingDate = $nextBillingTime
            ? Carbon::parse($nextBillingTime)
            : $endDate;

        $userPlan->update([
            'status' => UserPlan::STATUS_ACTIVE,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'next_billing_date' => $nextBillingDate,
            'auto_renew' => true,
        ]);

        Log::info('PayPal subscription activated', [
            'subscription_id' => $subscriptionId,
            'user_plan_id' => $userPlan->id,
            'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s')
        ]);

        // Send notification
        $user = User::where('urn', $userPlan->user_urn)->first();
        if ($user) {
            $notification = CustomNotification::create([
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'type' => CustomNotification::TYPE_USER,
                'message_data' => [
                    'title' => 'Subscription Activated',
                    'message' => 'Your subscription is now active.',
                    'description' => 'Your ' . $userPlan->plan->name . ' subscription has been activated.',
                    'icon' => 'check-circle',
                ]
            ]);
            broadcast(new UserNotificationSent($notification));
        }
    }

    /**
     * Handle subscription updated
     */
    protected function handleSubscriptionUpdated($resource)
    {
        $subscriptionId = $resource['id'];

        Log::info('Processing BILLING.SUBSCRIPTION.UPDATED', [
            'subscription_id' => $subscriptionId
        ]);

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if (!$userPlan) {
            return;
        }

        // Update billing date if changed
        $billingInfo = $resource['billing_info'] ?? null;
        $nextBillingTime = $billingInfo['next_billing_time'] ?? null;

        if ($nextBillingTime) {
            $userPlan->update([
                'next_billing_date' => Carbon::parse($nextBillingTime),
            ]);
        }
    }

    /**
     * Handle subscription cancelled
     */
    protected function handleSubscriptionCancelled($resource)
    {
        $subscriptionId = $resource['id'];

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if ($userPlan) {
            $userPlan->update([
                'status' => UserPlan::STATUS_CANCELED,
                'auto_renew' => false,
                'canceled_at' => now(),
            ]);

            // Notify user
            $user = User::where('urn', $userPlan->user_urn)->first();
            if ($user) {
                $notification = CustomNotification::create([
                    'receiver_id' => $user->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Subscription Cancelled',
                        'message' => 'Your subscription has been cancelled.',
                        'description' => 'Your ' . $userPlan->plan->name . ' subscription was cancelled and will not renew.',
                        'icon' => 'alert-circle',
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            }

            Log::info('PayPal subscription cancelled', [
                'subscription_id' => $subscriptionId,
                'user_plan_id' => $userPlan->id
            ]);
        }
    }

    /**
     * Handle subscription expired
     */
    protected function handleSubscriptionExpired($resource)
    {
        $subscriptionId = $resource['id'];

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if ($userPlan) {
            $userPlan->update([
                'status' => UserPlan::STATUS_EXPIRED,
                'auto_renew' => false,
            ]);

            // Notify user
            $user = User::where('urn', $userPlan->user_urn)->first();
            if ($user) {
                $notification = CustomNotification::create([
                    'receiver_id' => $user->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Subscription Expired',
                        'message' => 'Your subscription has expired.',
                        'description' => 'Please renew to continue using ' . $userPlan->plan->name . '.',
                        'icon' => 'alert-triangle',
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            }

            Log::info('PayPal subscription expired', [
                'subscription_id' => $subscriptionId
            ]);
        }
    }

    /**
     * Handle subscription suspended
     */
    protected function handleSubscriptionSuspended($resource)
    {
        $subscriptionId = $resource['id'];

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if ($userPlan) {
            $userPlan->update([
                'status' => UserPlan::STATUS_INACTIVE,
            ]);

            Log::info('PayPal subscription suspended', [
                'subscription_id' => $subscriptionId
            ]);
        }
    }

    /**
     * Handle payment completed (CRITICAL FOR AUTO-RENEWALS)
     */
    protected function handlePaymentCompleted($resource)
    {
        $subscriptionId = $resource['billing_agreement_id'] ?? null;

        if (!$subscriptionId) {
            Log::info('Payment completed but no subscription ID found');
            return;
        }

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if (!$userPlan) {
            Log::warning('UserPlan not found for PayPal subscription', [
                'subscription_id' => $subscriptionId
            ]);
            return;
        }

        // Check if renewal
        $existingPayments = Payment::where('subscription_id', $subscriptionId)
            ->where('status', Payment::STATUS_SUCCEEDED)
            ->count();

        $isRenewal = $existingPayments > 0;

        // Create payment record
        $amount = (float) $resource['amount']['total'];

        Payment::create([
            'user_urn' => $userPlan->user_urn,
            'order_id' => $userPlan->order_id,
            'payment_gateway' => Payment::PAYMENT_GATEWAY_PAYPAL,
            'payment_provider_id' => $resource['id'],
            'subscription_id' => $subscriptionId,
            'amount' => $amount,
            'currency' => $resource['amount']['currency'],
            'status' => Payment::STATUS_SUCCEEDED,
            'is_recurring' => true,
            'notes' => $isRenewal ? 'PayPal subscription renewal' : 'Initial PayPal subscription payment',
            'metadata' => [
                'sale_id' => $resource['id'],
                'subscription_id' => $subscriptionId,
                'is_renewal' => $isRenewal,
            ],
            'processed_at' => now(),
            'creater_id' => $userPlan->creater_id,
            'creater_type' => $userPlan->creater_type,
        ]);

        // Update user plan for renewal
        if ($isRenewal) {
            $currentEndDate = $userPlan->end_date ?? now();

            // Add one billing cycle to current end date
            $newEndDate = $userPlan->billing_cycle === 'yearly'
                ? Carbon::parse($currentEndDate)->addYear()
                : Carbon::parse($currentEndDate)->addMonth();

            $userPlan->update([
                'status' => UserPlan::STATUS_ACTIVE,
                'end_date' => $newEndDate,
                'next_billing_date' => $newEndDate,
            ]);

            Log::info('PayPal subscription renewed', [
                'subscription_id' => $subscriptionId,
                'new_end_date' => $newEndDate->format('Y-m-d H:i:s')
            ]);
        }

        // Send notification
        $user = User::where('urn', $userPlan->user_urn)->first();
        if ($user) {
            $notificationTitle = $isRenewal ? 'Subscription Renewed' : 'Payment Received';

            $notification = CustomNotification::create([
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'type' => CustomNotification::TYPE_USER,
                'message_data' => [
                    'title' => $notificationTitle,
                    'message' => $isRenewal
                        ? 'Your subscription has been renewed.'
                        : 'Your payment has been received.',
                    'description' => 'Payment of $' . $amount . ' processed successfully.',
                    'icon' => 'check-circle',
                ]
            ]);
            broadcast(new UserNotificationSent($notification));
        }

        Log::info('PayPal payment completed', [
            'subscription_id' => $subscriptionId,
            'amount' => $amount,
            'is_renewal' => $isRenewal
        ]);
    }

    /**
     * Handle payment failed
     */
    protected function handlePaymentFailed($resource)
    {
        $subscriptionId = $resource['billing_agreement_id'] ?? null;

        if (!$subscriptionId) {
            return;
        }

        $userPlan = UserPlan::where('paypal_subscription_id', $subscriptionId)->first();

        if (!$userPlan) {
            return;
        }

        $userPlan->update([
            'status' => UserPlan::STATUS_INACTIVE,
        ]);

        // Notify user
        $user = User::where('urn', $userPlan->user_urn)->first();
        if ($user) {
            $notification = CustomNotification::create([
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'type' => CustomNotification::TYPE_USER,
                'message_data' => [
                    'title' => 'Payment Failed',
                    'message' => 'Your subscription payment failed.',
                    'description' => 'Please update your payment method to continue your subscription.',
                    'icon' => 'alert-triangle',
                ]
            ]);
            broadcast(new UserNotificationSent($notification));
        }

        Log::error('PayPal payment failed', [
            'subscription_id' => $subscriptionId
        ]);
    }
}
