<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\completeExpiredCampaignsJob;
use App\Services\User\CampaignManagement\CampaignService;

class CompleteExpiredCampaigns extends Command
{
    protected $signature = 'campaigns:complete-expired';

    protected $description = 'Complete expired campaigns and refund remaining credits';

    public function handle(): int
    {
        $this->info('Dispatching job to check for expired campaigns...');

        try {
            completeExpiredCampaignsJob::dispatch();

            $this->info('Job dispatched to queue successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error dispatching job: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
