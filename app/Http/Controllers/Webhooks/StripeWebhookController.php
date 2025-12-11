<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\UserPlan;
use App\Models\CustomNotification;
use App\Models\User;
use App\Events\UserNotificationSent;
use App\Events\AdminNotificationSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        try {
            switch ($event->type) {
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                case 'customer.subscription.trial_will_end':
                    $this->handleTrialWillEnd($event->data->object);
                    break;

                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event: ' . $event->type);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed: ' . $e->getMessage(), [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        Log::info('Subscription created', ['subscription_id' => $subscription->id]);

        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();

        if ($userPlan) {
            $currentPeriodEnd = $subscription->current_period_end;

            $userPlan->update([
                'status' => UserPlan::STATUS_ACTIVE,
                'start_date' => now(),
                'next_billing_date' => Carbon::createFromTimestamp($currentPeriodEnd),
            ]);
        }
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated', ['subscription_id' => $subscription->id]);

        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();

        if ($userPlan) {
            $status = match ($subscription->status) {
                'active' => UserPlan::STATUS_ACTIVE,
                'canceled' => UserPlan::STATUS_CANCELED,
                'past_due' => UserPlan::STATUS_INACTIVE,
                'unpaid' => UserPlan::STATUS_INACTIVE,
                'incomplete' => UserPlan::STATUS_PENDING,
                'incomplete_expired' => UserPlan::STATUS_EXPIRED,
                'trialing' => UserPlan::STATUS_ACTIVE,
                default => $userPlan->status,
            };

            $userPlan->update([
                'status' => $status,
                'next_billing_date' => Carbon::createFromTimestamp($subscription->current_period_end),
                'auto_renew' => !$subscription->cancel_at_period_end,
            ]);

            if ($subscription->cancel_at_period_end && !$userPlan->canceled_at) {
                $userPlan->update(['canceled_at' => now()]);
            }
        }
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', ['subscription_id' => $subscription->id]);

        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();

        if ($userPlan) {
            $userPlan->update([
                'status' => UserPlan::STATUS_EXPIRED,
                'auto_renew' => false,
                'canceled_at' => now(),
            ]);

            $user = User::where('urn', $userPlan->user_urn)->first();
            if ($user) {
                $notification = CustomNotification::create([
                    'receiver_id' => $user->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Subscription Expired',
                        'message' => 'Your subscription has expired.',
                        'description' => 'Your ' . $userPlan->plan->name . ' subscription has ended.',
                        'icon' => 'alert-circle',
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            }
        }
    }

    /**
     * CRITICAL: This handles all subscription renewals automatically
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $invoice->subscription,
            'amount_paid' => $invoice->amount_paid / 100
        ]);

        if (!$invoice->subscription) {
            Log::info('Not a subscription invoice, skipping');
            return;
        }

        $userPlan = UserPlan::where('stripe_subscription_id', $invoice->subscription)->first();

        if (!$userPlan) {
            Log::warning('UserPlan not found for subscription', ['subscription_id' => $invoice->subscription]);
            return;
        }

        // Check if this is a renewal payment
        $existingPayments = Payment::where('subscription_id', $invoice->subscription)
            ->where('status', Payment::STATUS_SUCCEEDED)
            ->count();

        $isRenewal = $existingPayments > 0;

        // Get accurate period dates from invoice
        $periodEnd = $invoice->lines->data[0]->period->end ?? $invoice->period_end;
        $periodStart = $invoice->lines->data[0]->period->start ?? $invoice->period_start;

        // Create payment record
        Payment::create([
            'user_urn' => $userPlan->user_urn,
            'order_id' => $userPlan->order_id,
            'payment_gateway' => Payment::PAYMENT_GATEWAY_STRIPE,
            'payment_provider_id' => $invoice->customer,
            'payment_intent_id' => $invoice->payment_intent,
            'subscription_id' => $invoice->subscription,
            'amount' => $invoice->amount_paid / 100,
            'currency' => strtoupper($invoice->currency),
            'status' => Payment::STATUS_SUCCEEDED,
            'is_recurring' => true,
            'receipt_url' => $invoice->hosted_invoice_url,
            'notes' => $isRenewal ? 'Subscription renewal payment' : 'Initial subscription payment',
            'metadata' => [
                'invoice_id' => $invoice->id,
                'subscription_id' => $invoice->subscription,
                'is_renewal' => $isRenewal,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
            ],
            'processed_at' => now(),
            'creater_id' => $userPlan->creater_id,
            'creater_type' => $userPlan->creater_type,
        ]);

        // FIXED: Update next billing date to the actual next period
        $nextBillingDate = Carbon::createFromTimestamp($periodEnd);

        // Calculate display end_date based on billing cycle
        $displayEndDate = $userPlan->billing_cycle === 'yearly'
            ? Carbon::createFromTimestamp($periodStart)->addYear()
            : Carbon::createFromTimestamp($periodStart)->addMonth();

        $userPlan->update([
            'status' => UserPlan::STATUS_ACTIVE,
            'end_date' => $displayEndDate,
            'next_billing_date' => $nextBillingDate,
        ]);

        Log::info('User plan updated after payment', [
            'user_plan_id' => $userPlan->id,
            'next_billing_date' => $nextBillingDate->format('Y-m-d H:i:s'),
            'is_renewal' => $isRenewal
        ]);

        // Send notification
        $user = User::where('urn', $userPlan->user_urn)->first();
        if ($user) {
            $notificationTitle = $isRenewal ? 'Subscription Renewed' : 'Subscription Activated';
            $notificationMessage = $isRenewal
                ? 'Your subscription has been renewed successfully.'
                : 'Your subscription has been activated.';

            $notification = CustomNotification::create([
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'type' => CustomNotification::TYPE_USER,
                'message_data' => [
                    'title' => $notificationTitle,
                    'message' => $notificationMessage,
                    'description' => 'Payment of $' . ($invoice->amount_paid / 100) . ' processed successfully.',
                    'icon' => 'check-circle',
                    'additional_data' => [
                        'Plan' => $userPlan->plan->name,
                        'Amount' => '$' . ($invoice->amount_paid / 100),
                        'Next Billing Date' => $nextBillingDate->format('M d, Y'),
                        'Receipt URL' => $invoice->hosted_invoice_url,
                    ]
                ]
            ]);
            broadcast(new UserNotificationSent($notification));

            // Admin notification
            $adminNotification = CustomNotification::create([
                'sender_id' => $user->id,
                'sender_type' => User::class,
                'type' => CustomNotification::TYPE_ADMIN,
                'message_data' => [
                    'title' => 'Subscription Payment Received',
                    'message' => 'User ' . $user->name . ' subscription payment processed.',
                    'description' => ($isRenewal ? 'Renewal' : 'New') . ' payment of $' . ($invoice->amount_paid / 100),
                    'icon' => 'dollar-sign',
                ]
            ]);
            broadcast(new AdminNotificationSent($adminNotification));
        }
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        Log::error('Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $invoice->subscription,
            'attempt_count' => $invoice->attempt_count ?? 1
        ]);

        if (!$invoice->subscription) {
            return;
        }

        $userPlan = UserPlan::where('stripe_subscription_id', $invoice->subscription)->first();

        if (!$userPlan) {
            return;
        }

        $userPlan->update([
            'status' => UserPlan::STATUS_INACTIVE,
        ]);

        $failureReason = 'Payment failed';
        if (isset($invoice->last_finalization_error->message)) {
            $failureReason = $invoice->last_finalization_error->message;
        } elseif (isset($invoice->charge->failure_message)) {
            $failureReason = $invoice->charge->failure_message;
        }

        Payment::create([
            'user_urn' => $userPlan->user_urn,
            'order_id' => $userPlan->order_id,
            'payment_gateway' => Payment::PAYMENT_GATEWAY_STRIPE,
            'payment_provider_id' => $invoice->customer,
            'payment_intent_id' => $invoice->payment_intent,
            'subscription_id' => $invoice->subscription,
            'amount' => $invoice->amount_due / 100,
            'currency' => strtoupper($invoice->currency),
            'status' => Payment::STATUS_FAILED,
            'is_recurring' => true,
            'failure_reason' => $failureReason,
            'notes' => 'Subscription renewal payment failed (Attempt ' . ($invoice->attempt_count ?? 1) . ')',
            'metadata' => [
                'invoice_id' => $invoice->id,
                'subscription_id' => $invoice->subscription,
                'attempt_count' => $invoice->attempt_count ?? 1,
            ],
            'creater_id' => $userPlan->creater_id,
            'creater_type' => $userPlan->creater_type,
        ]);

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
                    'additional_data' => [
                        'Reason' => $failureReason,
                        'Attempt' => $invoice->attempt_count ?? 1,
                    ]
                ]
            ]);
            broadcast(new UserNotificationSent($notification));
        }
    }

    protected function handleTrialWillEnd($subscription)
    {
        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();

        if (!$userPlan) {
            return;
        }

        $user = User::where('urn', $userPlan->user_urn)->first();
        if ($user) {
            $trialEndDate = Carbon::createFromTimestamp($subscription->trial_end);
            $daysRemaining = now()->diffInDays($trialEndDate);

            $notification = CustomNotification::create([
                'receiver_id' => $user->id,
                'receiver_type' => User::class,
                'type' => CustomNotification::TYPE_USER,
                'message_data' => [
                    'title' => 'Trial Ending Soon',
                    'message' => "Your free trial is ending in {$daysRemaining} days.",
                    'description' => 'Your subscription will automatically renew unless you cancel.',
                    'icon' => 'info',
                    'additional_data' => [
                        'Trial End Date' => $trialEndDate->format('M d, Y'),
                        'Days Remaining' => $daysRemaining,
                    ]
                ]
            ]);
            broadcast(new UserNotificationSent($notification));
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded (backup event)', [
            'payment_intent_id' => $paymentIntent->id
        ]);

        if (!$paymentIntent->invoice) {
            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

            if ($payment && $payment->status !== Payment::STATUS_SUCCEEDED) {
                $payment->update([
                    'status' => Payment::STATUS_SUCCEEDED,
                    'processed_at' => now(),
                ]);
            }
        }
    }
}
