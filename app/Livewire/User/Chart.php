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

    public $sourcesPerPage = 20;
    public $activeTab = 'listView';
    public $playing = null;

    public function boot(AnalyticsService $analyticsService, CampaignService $campaignService, SoundCloudService $soundCloudService)
    {
        $this->analyticsService = $analyticsService;
        $this->campaignService = $campaignService;
        $this->soundCloudService = $soundCloudService;
    }

    public function refresh()
    {
        $this->getPaginatedSourceData();
    }

    public function getPaginatedSourceData(): LengthAwarePaginator
    {
        // try {
            $sources =  $this->analyticsService->getPaginatedAnalytics(
                filter: 'last_week',
                dateRange: null,
                genres: [],
                perPage: $this->sourcesPerPage,
                page: $this->getPage(),
                actionableType: Campaign::class
            );
            dd($sources);
            return $sources;
        // } catch (\Throwable $e) {
        //     Log::error('Paginated source data loading failed', ['error' => $e->getMessage()]);
        //     return new LengthAwarePaginator([], 0, $this->sourcesPerPage, $this->getPage());
        // }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function baseValidation($encryptedCampaignId, $encryptedSourceId)
    {
        if ($this->campaignService->getCampaigns()->where('id', decrypt($encryptedCampaignId))->where('user_urn', user()->urn)->exists()) {
            $this->dispatch('alert', type: 'error', message: 'You cannot act on your own campaign.');
            return null;
        }
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $campaign = $this->campaignService->getCampaign($encryptedCampaignId);
        $campaign->load('music.user');
        if (decrypt($encryptedSourceId) != $campaign->music->id) {
            $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
            return null;
        }
        return $campaign;
    }

    public function likeSource($encryptedCampaignId, $encryptedSourceId)
    {
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedSourceId);
            if (!$campaign) {
                return;
            }

            if (UserAnalytics::where('act_user_urn', user()->urn)->where('source_id', decrypt($encryptedSourceId))->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already liked this campaign.');
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
    public function repostSource($encryptedCampaignId, $encryptedSourceId)
    {
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedSourceId);

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
                $soundcloudRepostId = $campaign->music_type == Track::class ? $campaign->music->soundcloud_track_id : $campaign->music->soundcloud_id;
                $this->campaignService->repostSource($campaign, $soundcloudRepostId, user());
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

        $paginated = $this->getPaginatedSourceData();
        // Get the collection from paginator
        $items = $paginated->getCollection();

        // Map over items to calculate engagement score and rate
        $itemsWithMetrics = $items->map(function ($source) {
            $totalViews = $source['metrics']['total_views']['current_total'] == 0 ? 1 : $source['metrics']['total_views']['current_total'];
            $totalPlays = $source['metrics']['total_plays']['current_total'];
            $totalReposts = $source['metrics']['total_reposts']['current_total'];
            $totalLikes = $source['metrics']['total_likes']['current_total'];
            $totalComments = $source['metrics']['total_comments']['current_total'];
            $totalFollowers = $source['metrics']['total_followers']['current_total'];
            $source['repost'] = false;
            if ($source['actionable_details'] != null) {
                $repost = Repost::where('reposter_urn', user()->urn)->where('campaign_id', $source['actionable_details']['id'])->exists();
                if ($repost) {
                    $source['repost'] = true;
                }
            }
            $source['like'] = false;
            $like = UserAnalytics::where('act_user_urn', user()->urn)->where('source_id', $source['source_details']['id'])->exists();
            if ($like) {
                $source['like'] = true;
            }


            $avgTotal = ($totalLikes + $totalComments + $totalReposts + $totalPlays + $totalFollowers) / 5;

            // Engagement % (capped at 100)
            $engagementRate = $totalViews >= $avgTotal ? min(100, ($avgTotal / $totalViews) * 100) : 0;

            // Engagement Score (0–10 scale)
            $engagementScore = round(($engagementRate / 100) * 10, 1);
            $source['getMusicSrc'] = $this->soundCloudService->getMusicSrc($source['source_details']['id']);

            // Add score and rate to source array
            $source['engagement_score'] = $engagementScore;
            $source['engagement_rate'] = round($engagementRate, 2); // optional rounding

            return $source;
        });

        // Sort by engagement score descending
        $sorted = $itemsWithMetrics->sortByDesc('engagement_score');

        // Update paginator collection
        $paginated->setCollection($sorted);
        return view(
            'livewire.user.chart',
            [
                'topSources' => $paginated
            ]
        );
    }
}
