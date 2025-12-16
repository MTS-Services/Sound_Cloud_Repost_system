<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Jobs\TopPerformanceSourceJob;
use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\UserAnalytics;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Throwable;

class Chart extends Component
{
    protected AnalyticsService $analyticsService;
    protected ?CampaignService $campaignService = null;
    protected ?SoundCloudService $soundCloudService = null;
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    public $activeTab = 'listView';

    public function boot(AnalyticsService $analyticsService, CampaignService $campaignService, SoundCloudService $soundCloudService)
    {
        $this->analyticsService = $analyticsService;
        $this->campaignService = $campaignService;
        $this->soundCloudService = $soundCloudService;
    }

    public function refresh()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        // $period = [
        //     'start' => $startDate,
        //     'end' => $endDate,
        // ];


        // $sources = $this->analyticsService->getTopSources(
        //     filter: 'date_range',
        //     dateRange: $period,
        //     actionableType: Campaign::class
        // );

        TopPerformanceSourceJob::dispatch($startDate, $endDate);
        $this->redirectRoute('user.charts', navigate: true);
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
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        try {
            $campaign = $this->baseValidation($encryptedCampaignId, $encryptedSourceId);
            if (!$campaign) {
                return;
            }

            if (UserAnalytics::where('act_user_urn', user()->urn)->where('source_id', decrypt($encryptedSourceId))->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already liked this campaign.');
                return;
            }

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $response = null;

            $like_increased = false;
            switch ($campaign->music_type) {
                case Track::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $campaign?->music?->urn, errorMessage: 'Failed to fetch track details');
                    $previous_likes = $checkLiked['collection']['favoritings_count'];
                    $response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $campaign?->music?->urn, errorMessage: 'Failed to fetch track details');
                    $new_likes = $checkLiked['collection']['favoritings_count'];

                    if ($new_likes > $previous_likes) {
                        $like_increased = true;
                    }
                    break;
                case Playlist::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $campaign?->music?->soundcloud_urn, errorMessage: 'Failed to fetch playlist details');
                    $previous_likes = $checkLiked['collection']['likes_count'];

                    $response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign?->music?->soundcloud_urn}");

                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $campaign?->music?->soundcloud_urn, errorMessage: 'Failed to fetch playlist details');
                    $new_likes = $checkLiked['collection']['likes_count'];

                    if ($new_likes > $previous_likes) {
                        $like_increased = true;
                    }
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }
            if ($like_increased == false) {
                $this->dispatch('alert', type: 'error', message: 'You have already liked this ' . $campaign->music_type == Track::class ? 'track' : 'playlist' . ' from soundcloud.');
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
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
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

            $repost_increased = false;

            switch ($campaign->music_type) {
                case Track::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $campaign?->music?->urn, errorMessage: 'Failed to fetch track details');
                    $previous_reposts = $checkLiked['collection']['repost_count'];
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/tracks/' . $campaign?->music?->urn, errorMessage: 'Failed to fetch track details');
                    $new_reposts = $checkLiked['collection']['repost_count'];
                    if ($new_reposts > $previous_reposts) {
                        $repost_increased = true;
                    }
                    break;
                case Playlist::class:
                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $campaign?->music?->soundcloud_urn, errorMessage: 'Failed to fetch playlist details');
                    $previous_reposts = $checkLiked['collection']['repost_count'];

                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->soundcloud_urn}");

                    $checkLiked = $this->soundCloudService->makeGetApiRequest(endpoint: '/playlists/' . $campaign?->music?->soundcloud_urn, errorMessage: 'Failed to fetch playlist details');
                    $new_reposts = $checkLiked['collection']['repost_count'];

                    if ($new_reposts > $previous_reposts) {
                        $repost_increased = true;
                    }
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Something went wrong. Please try again.');
                    return;
            }

            if ($repost_increased == false) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this ' . $campaign->music_type == Track::class ? 'track' : 'playlist' . ' from soundcloud.');
                return;
            }

            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->music_type == Track::class ? $campaign->user->urn : $campaign->music->user->soundcloud_urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Notification',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your ' . $campaign->title . ' has been reposted successfully.',
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
        $paginated = Cache::store('database')->get('top_20_sources_cache');
        $period = [];

        if ($paginated != null) {
            $period = $paginated['period'];
            $paginated = $paginated['analytics'];
        }

        $items = collect($paginated);

        // // Map over items to calculate engagement score and rate
        // $itemsWithMetrics = $items->map(function ($source) {
        //     $source['repost'] = false;
        //     // NOTE: The access path below needs to be checked carefully against what the service returns
        //     if ($source['actionable'] != null) {

        //         // If the service returns an array, the subsequent database query is okay here.
        //         $repost = Repost::where('reposter_urn', user()->urn)->where('campaign_id', $source['actionable']['id'])->exists();
        //         if ($repost) {
        //             $source['repost'] = true;
        //         }
        //     }
        //     $source['like'] = false;
        //     // NOTE: The access path below needs to be checked carefully against what the service returns
        //     $like = UserAnalytics::where('act_user_urn', user()->urn)->where('source_id', $source['source']['id'])->exists();
        //     if ($like) {
        //         $source['like'] = true;
        //     }
        //     return $source;
        // });

        $sourceIds = $items->pluck('source.id')->filter()->all();
        $campaignIds = $items->pluck('actionable.id')->filter()->all();
        $userUrn = user()->urn;

        // Preload reposts for the current user for all campaigns
        $reposts = Repost::where('reposter_urn', $userUrn)
            ->whereIn('campaign_id', $campaignIds)
            ->pluck('campaign_id')
            ->toArray();

        // Preload likes for the current user for all sources
        $likes = UserAnalytics::where('act_user_urn', $userUrn)
            ->whereIn('source_id', $sourceIds)
            ->pluck('source_id')
            ->toArray();

        $countId4474 = UserAnalytics::where('source_id', '4474')->where('type', 6)->whereDate('created_at', '>=', Carbon::now()->subDays(7))->count();
        // dd($countId4474, $items);

        $itemsWithMetrics = $items->map(function ($source) use ($reposts, $likes) {
            $source['repost'] = $source['actionable'] && in_array($source['actionable']['id'], $reposts);
            $source['like'] = $source['source'] && in_array($source['source']['id'], $likes);
            return $source;
        });

        return view(
            'livewire.user.chart',
            [
                'topSources' => $itemsWithMetrics,
                'period' => $period
            ]
        );
    }
}
