<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Track;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Throwable;

class Chart extends Component
{
    use WithPagination;

    protected AnalyticsService $analyticsService;
    protected ?CampaignService $campaignService = null;
    protected ?SoundCloudService $soundCloudService = null;
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    public $tracksPerPage = 20;
    public $activeTab = 'listView';
    // public $topTracks = [];

    public function boot(AnalyticsService $analyticsService, CampaignService $campaignService, SoundCloudService $soundCloudService)
    {
        $this->analyticsService = $analyticsService;
        $this->campaignService = $campaignService;
        $this->soundCloudService = $soundCloudService;
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
        if ($this->campaignService->getCampaigns()->where('id', decrypt($encryptedCampaignId))->where('user_urn', $currentUserUrn)->exists()) {
            $this->dispatch('alert', type: 'error', message: 'You cannot act on your own campaign.');
            return null;
        }
        $campaign = $this->campaignService->getCampaign($encryptedCampaignId);
        $campaign->load('music.user');
        if (decrypt($encryptedTrackUrn) != $campaign->music->urn) {
            $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            return null;
        }
        return $campaign;
    }

    public function likeTrack($encryptedCampaignId, $encryptedTrackUrn)
    {
        $this->soundCloudService->ensureSoundCloudConnection(user());
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);
            if (!$campaign) {
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $like_response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $like_response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $like_response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign->music->urn}");
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }
            $data = [
                'likeable' => $like_response ? true : false
            ];
            if ($like_response->successful()) {
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);
                $this->dispatch('alert', type: 'success', message: 'Campaign music reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $like_response->body());
                $this->dispatch('alert', type: 'error', message: 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => decrypt($encryptedCampaignId),
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            return;
        }
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
