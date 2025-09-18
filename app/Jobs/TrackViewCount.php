<?php

namespace App\Jobs;

use App\Models\UserAnalytics;
use App\Services\User\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TrackViewCount implements ShouldQueue
{
    use Queueable;

    /**
     * Tracks data array
     * @var array
     * @example [
     *      {
     *          "track": track,
     *          "actionable": object (campaign, request, null),
     *          "genre": "string"
     *      }
     *  ]
     */
    private $tracksData = [];
    protected AnalyticsService $analyticsService;
    /**
     * Create a new job instance.
     */
    public function __construct($tracksData, AnalyticsService $analyticsService = new AnalyticsService())
    {
        $this->tracksData = $tracksData;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing TrackViewCount job for ' . count($this->tracksData) . ' tracks.');
        foreach ($this->tracksData as $data) {
            Log::info('Recording view for track ID: ' . json_encode($data['track']));
            $genre = isset($data['genre']) && $data['genre'] ? $data['genre'] : $data['track']->genre;
            $this->analyticsService->recordAnalytics($data['track'], $data['actionable'], UserAnalytics::TYPE_VIEW, $genre);
        }

    }
}
