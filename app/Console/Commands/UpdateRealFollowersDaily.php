<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRealFollowers;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRealFollowersDaily extends Command
{
    protected $signature = 'app:update-real-followers-daily';
    protected $description = 'Dispatch daily jobs to update real followers for all active users';

    public function handle()
    {
        Log::info('Starting daily real followers update...');

        $delayMinutes = 0;

        User::active()->chunk(50, function ($users) use (&$delayMinutes) {
            foreach ($users as $user) {
                UpdateRealFollowers::dispatch($user)
                    ->delay(now()->addMinutes($delayMinutes));

                $delayMinutes += 1; // stagger each job by 1 minute
            }
        });

        Log::info('All UpdateRealFollowers jobs have been dispatched successfully.');
    }
}
