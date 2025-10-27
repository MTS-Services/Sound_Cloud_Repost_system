<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecordSoundCloudPlay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaignId;

    /**
     * Create a new job instance.
     */
    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1️⃣ Find the campaign
        $campaign = Campaign::find($this->campaignId);

        if ($campaign) {
            // 2️⃣ Increment play count
            $campaign->increment('play_count');

            // 3️⃣ (Optional) Log for debugging
            Log::info("Play counted for campaign ID: {$this->campaignId}");
        } else {
            Log::warning("Campaign not found for ID: {$this->campaignId}");
        }
    }
}
