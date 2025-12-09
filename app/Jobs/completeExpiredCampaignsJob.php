<?php

namespace App\Jobs;

use App\Services\User\CampaignManagement\CampaignService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class completeExpiredCampaignsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CampaignService $campaignService): void
    {
        try {
            Log::info('Checking expired campaigns...');
            $count = $campaignService->completeExpiredCampaigns();
            Log::info($count . ' : Expired campaigns check completed successfully');
        } catch (\Exception $e) {
            Log::error('Error checking expired campaigns: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CheckExpiredCampaigns job failed', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
