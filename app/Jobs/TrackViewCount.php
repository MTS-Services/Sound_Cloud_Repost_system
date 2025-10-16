<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
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
    private $datas;

    /**
     * Tracks data array
     * @var string
     * @example 'track' | 'campaign' | 'request' | 'playlist'
     */
    private $type = 'track';
    protected AnalyticsService $analyticsService;

    private $source;
    private $genre;
    private $actionable = null;
    private $actUserUrn;
    /**
     * Create a new job instance.
     */

    //   public function recordAnalytics(object $source, ?object $actionable = null, int $type, string $genre, $actUserUrn = null): UserAnalytics|bool|null
    public function __construct($datas, $actuUserUrn, $type = 'track', AnalyticsService $analyticsService = new AnalyticsService())
    {
        $this->datas = $datas;
        $this->analyticsService = $analyticsService;
        $this->type = $type;
        $this->actUserUrn = $actuUserUrn;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('TrackViewCount Job is processing. total datas: ' . count($this->datas));
        foreach ($this->datas as $data) {
            switch ($this->type) {
                case 'track':
                    $this->genre = $data->genre;
                    $this->actionable = null;
                    $this->source = $data;
                    break;
                case 'campaign':
                    $data->load('music');
                    $this->genre = $data->target_genre;
                    $this->actionable = $data;
                    $this->source = $data->music;
                    break;
                case 'request':
                    $data->load('music');
                    $this->genre = $data?->music?->genre;
                    $this->actionable = $data;
                    $this->source = $data?->music;
                    break;
                case 'playlist':
                    $this->genre = $data->genre;
                    $this->actionable = null;
                    $this->source = $data;
                    break;
            }
            Log::info('TrackViewCount Job processing track urn: ' . $this->source->urn . ', genre: ' . $this->genre . ', actionable type: ' . ($this->actionable ? get_class($this->actionable) : 'null'));
            $this->analyticsService->recordAnalytics($this->source, $this->actionable, UserAnalytics::TYPE_VIEW, $this->genre, $this->actUserUrn);
        }
    }
}
