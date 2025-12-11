<?php

namespace App\Console\Commands;

use App\Models\UserPlan;
use App\Models\User;
use App\Models\CustomNotification;
use App\Events\UserNotificationSent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     */
    protected $description = 'Check and update expired subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        // Find active subscriptions that have passed their end date
        $expiredPlans = UserPlan::where('status', UserPlan::STATUS_ACTIVE)
            ->where('auto_renew', false) // Only check non-auto-renewing plans
            ->whereDate('end_date', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredPlans as $userPlan) {
            try {
                $userPlan->update([
                    'status' => UserPlan::STATUS_EXPIRED,
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
                            'description' => 'Your ' . $userPlan->plan->name . ' subscription has ended. Renew to continue enjoying premium features.',
                            'icon' => 'alert-circle',
                        ]
                    ]);
                    broadcast(new UserNotificationSent($notification));
                }

                $count++;
                $this->info("Expired subscription for user: {$userPlan->user_urn}");
            } catch (\Exception $e) {
                Log::error('Failed to expire subscription', [
                    'user_plan_id' => $userPlan->id,
                    'error' => $e->getMessage()
                ]);
                $this->error("Failed to expire subscription for user: {$userPlan->user_urn}");
            }
        }

        // Send reminders for subscriptions expiring soon (3 days before)
        $expiringPlans = UserPlan::where('status', UserPlan::STATUS_ACTIVE)
            ->whereDate('end_date', '=', now()->addDays(3))
            ->get();

        foreach ($expiringPlans as $userPlan) {
            $user = User::where('urn', $userPlan->user_urn)->first();
            if ($user) {
                $notification = CustomNotification::create([
                    'receiver_id' => $user->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Subscription Expiring Soon',
                        'message' => 'Your subscription expires in 3 days.',
                        'description' => 'Your ' . $userPlan->plan->name . ' subscription will end on ' .
                            $userPlan->end_date->format('M d, Y') .
                            ($userPlan->auto_renew ? ' and will auto-renew.' : '. Renew to continue.'),
                        'icon' => 'clock',
                    ]
                ]);
                broadcast(new UserNotificationSent($notification));
            }
        }

        $this->info("Processed {$count} expired subscriptions.");
        $this->info("Sent {$expiringPlans->count()} expiration reminders.");

        return 0;
    }
}
