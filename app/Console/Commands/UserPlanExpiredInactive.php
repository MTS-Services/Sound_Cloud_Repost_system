<?php

namespace App\Console\Commands;

use App\Models\UserPlan;
use Illuminate\Console\Command;
use Log;

class UserPlanExpiredInactive extends Command
{
    protected $signature = 'app:user-plan-expired-inactive';
    protected $description = 'Mark expired user plans as inactive';

    public function handle()
    {
        $updatedCount = UserPlan::markExpiredAsInactive();
        $this->info("{$updatedCount} user plans marked as inactive.");
        Log::info("UserPlanExpiredInactive: {$updatedCount} user plans marked as inactive.");
    }
}
