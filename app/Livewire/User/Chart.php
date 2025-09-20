<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\UserAnalytics;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
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
    public $playing = null;
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
        $tracks =  $this->analyticsService->getPaginatedTrackAnalytics(
            filter: 'last_week',
            dateRange: null,
            genres: [],
            perPage: $this->tracksPerPage,
            page: $this->getPage(),
            actionableType: Campaign::class
        );
        return $tracks;
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
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        if ($this->campaignService->getCampaigns()->where('id', decrypt($encryptedCampaignId))->where('user_urn', user()->urn)->exists()) {
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
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);
            if (!$campaign) {
                return;
            }

            if (UserAnalytics::where('act_user_urn', user()->urn)->where('track_urn', decrypt($encryptedTrackUrn))->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already liked this track.');
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign->music->urn}");
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }
            if ($response->successful()) {
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->likeCampaign($campaign, user());
                $this->dispatch('alert', type: 'success', message: 'Like successful.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body());
                $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
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
    public function repostTrack($encryptedCampaignId, $encryptedTrackUrn)
    {
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedTrackUrn);

            if (!$campaign) {
                return;
            }

            if (Repost::where('reposter_urn', user()->urn)->where('campaign_id', $campaign->id)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }
            $data = [
                'likeable' => false,
                'comment' => false,
                'follow' => false
            ];
            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Notification',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your ' . $campaign->title . 'campaign has been reposted successfully.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);
                $this->dispatch('alert', type: 'success', message: 'Campaign music reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body());
                $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
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

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function render()
    {

        $paginated = $this->getTopTrackData();
        // Get the collection from paginator
        $items = $paginated->getCollection();

        // Map over items to calculate engagement score and rate
        $itemsWithMetrics = $items->map(function ($track) {
            $totalViews = $track['metrics']['total_views']['current_total'] == 0 ? 1 : $track['metrics']['total_views']['current_total'];
            $totalPlays = $track['metrics']['total_plays']['current_total'];
            $totalReposts = $track['metrics']['total_reposts']['current_total'];
            $totalLikes = $track['metrics']['total_likes']['current_total'];
            $totalComments = $track['metrics']['total_comments']['current_total'];
            $totalFollowers = $track['metrics']['total_followers']['current_total'];
            $track['repost'] = false;
            if ($track['actionable_details'] != null) {
                $repost = Repost::where('reposter_urn', user()->urn)->where('campaign_id', $track['actionable_details']['id'])->exists();
                if ($repost) {
                    $track['repost'] = true;
                }
            }
            $track['like'] = false;
            $like = UserAnalytics::where('act_user_urn', user()->urn)->where('track_urn', $track['track_details']['urn'])->exists();
            if ($like) {
                $track['like'] = true;
            }


            $avgTotal = ($totalLikes + $totalComments + $totalReposts + $totalPlays + $totalFollowers) / 5;

            // Engagement % (capped at 100)
            $engagementRate = $totalViews >= $avgTotal ? min(100, ($avgTotal / $totalViews) * 100) : 0;

            // Engagement Score (0–10 scale)
            $engagementScore = round(($engagementRate / 100) * 10, 1);
            $track['getMusicSrc'] = $this->soundCloudService->getMusicSrc($track['track_details']['urn']);


            // Add score and rate to track array
            $track['engagement_score'] = $engagementScore;
            $track['engagement_rate'] = round($engagementRate, 2); // optional rounding

            return $track;
        });

        // Sort by engagement score descending
        $sorted = $itemsWithMetrics->sortByDesc('engagement_score');

        // Update paginator collection
        $paginated->setCollection($sorted);
        return view(
            'livewire.user.chart',
            [
                'topTracks' => $paginated
            ]
        );
    }
}
