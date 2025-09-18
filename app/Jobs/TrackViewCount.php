<?php

namespace App\Jobs;

use App\Models\UserAnalytics;
use App\Services\User\AnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        foreach ($this->tracksData as $data) {
            $genre = isset($data['genre']) && $data['genre'] ? $data['genre'] : $data['track']->genre;
            $this->analyticsService->recordAnalytics($data['track'], $data['actionable'], UserAnalytics::TYPE_VIEW, $genre);
        }

    }
}
