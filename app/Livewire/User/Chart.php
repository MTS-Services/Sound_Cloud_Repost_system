<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Track;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class Chart extends Component
{
    use WithPagination;

    protected AnalyticsService $analyticsService;
    protected ?CampaignService $campaignService = null;

    public $tracksPerPage = 20;
    public $activeTab = 'listView';
    // public $topTracks = [];

    public function boot(AnalyticsService $analyticsService, CampaignService $campaignService)
    {
        $this->analyticsService = $analyticsService;
        $this->campaignService = $campaignService;
    }

    public function refresh()
    {
        $this->getTopTrackData();
    }

    public function getTopTrackData(): LengthAwarePaginator
    {
        // try {
        return $this->analyticsService->getPaginatedTrackAnalytics(
            filter: 'last_week',
            dateRange: null,
            genres: [],
            perPage: $this->tracksPerPage,
            page: $this->getPage(),
            actionType: Campaign::class
        );
        // } catch (\Exception $e) {
        //     Log::error('Paginated track data loading failed', ['error' => $e->getMessage()]);
        //     return new LengthAwarePaginator([], 0, $this->tracksPerPage, $this->getPage());
        // }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function baseValidation($encryptedCampaignId, $encryptedTrackUrn)
    {
        $currentUserUrn = user()->urn;

        $campaign = $this->campaignService->getCampaigns()->where('id', decrypt($encryptedCampaignId))->where('user_urn', $currentUserUrn)->first();
        // if ($campaign) {
        //     $this->dispatch('alert', type: 'error', message: 'You cannot act on your own campaign.');
        //     return null;
        // }
        $campaign->load('music.user.userInfo');
        if (decrypt($encryptedTrackUrn) != $campaign->music->urn) {
            $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            return null;
        }
        return $campaign;
    }

    public function likeTrack($encryptedCampaignId, $encryptedTrackUrn)
    {
        $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);

        if (!$campaign) {
            return;
        }

        $soundcloudRepostId = null;

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);

        switch ($campaign->music_type) {
            case Track::class:
                $like_response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->user?->urn}");
                break;
            case Playlist::class:
                $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                if ($this->liked) {
                    $like_response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign->music->urn}");
                }
                if ($this->commented) {
                    $comment_response = $httpClient->post("{$this->baseUrl}/playlists/{$campaign->music->urn}/comments", $commentSoundcloud);
                }
                if ($this->followed) {
                    $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->user?->urn}");
                }
                break;
            default:
                $this->dispatch('alert', type: 'error', message: 'Invalid music type specified for the campaign.');
                return;
        }
        $data = [
            'likeable' => $like_response ? $this->liked : false,
            'comment' => $comment_response ? $this->commented : false,
            'follow' => $follow_response ? $this->followed : false
        ];
    }

    public function render()
    {
        return view(
            'livewire.user.chart',
            [
                'topTracks' => $this->getTopTrackData()
            ]
        );
    }
}
