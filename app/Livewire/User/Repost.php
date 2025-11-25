<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign as ModelsCampaign;
use App\Models\Playlist;
use App\Models\Repost as ModelsRepost;
use App\Models\Track;
use App\Models\UserAnalytics;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Repost extends Component
{
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    public $showRepostActionModal = false;
    public $campaign;

    // Repost action properties
    public $liked = true;
    public $alreadyLiked = false;
    public $commented = null;
    public $followed = true;
    public $alreadyFollowing = false;
    public $availableRepostTime = null;

    protected CampaignService $campaignService;
    protected SoundCloudService $soundCloudService;
    protected AnalyticsService $analyticsService;

    public function boot(
        CampaignService $campaignService,
        SoundCloudService $soundCloudService,
        AnalyticsService $analyticsService
    ) {
        $this->campaignService = $campaignService;
        $this->soundCloudService = $soundCloudService;
        $this->analyticsService = $analyticsService;
    }

    public function render()
    {
        return view('livewire.user.repost');
    }

    protected function commentedRules()
    {
        return [
            'commented' => [
                'nullable',
                'string',
                'min:5',
                function ($attribute, $value, $fail) {
                    if (!$value) return;

                    if (str_word_count(trim($value)) < 2) {
                        $fail('Your comment must contain at least two words.');
                    }

                    $plainValue = preg_replace('/\s+/', '', $value);
                    if (preg_match('/([a-zA-Z])\1{4,}/i', $plainValue)) {
                        $fail('Your comment looks like spam.');
                    }

                    if (preg_match('/https?:\/\/(soundcloud\.com|snd\.sc)/i', $value)) {
                        $fail('Posting SoundCloud track links is not allowed.');
                    }

                    if (preg_match('/https?:\/\/(?!soundcloud\.com|snd\.sc)[^\s]+/i', $value)) {
                        $fail('Posting external links is not allowed in comments.');
                    }

                    if (preg_match('/([!?.,])\1{3,}/', $value)) {
                        $fail('Please avoid excessive punctuation.');
                    }

                    if (preg_match('/\b(\w+)\b(?:.*\b\1\b){3,}/i', $value)) {
                        $fail('Please avoid repeating the same word too many times.');
                    }

                    $spamWords = ['check out', 'subscribe', 'follow me', 'free download', 'visit my profile'];
                    foreach ($spamWords as $spam) {
                        if (stripos($value, $spam) !== false) {
                            $fail('Your comment looks like self-promotion or spam.');
                        }
                    }
                },
            ],
        ];
    }

    #[On('callRepostAction')]
    public function callRepostAction($campaignId)
    {
        try {
            $this->reset(['campaign', 'liked', 'alreadyLiked', 'commented', 'followed', 'alreadyFollowing']);
            // $this->reset();
            dd(decrypt($campaignId));

            $this->campaign = $this->campaignService->getCampaign($campaignId);
            $this->campaign->load('music.user.userInfo', 'user');

            // Fast parallel checks using cache
            $this->checkRepostEligibility();

            if ($this->campaign) {
                $this->checkUserInteractions();
                $this->showRepostActionModal = true;
            }
        } catch (\Exception $e) {
            Log::error('Error loading repost modal: ' . $e->getMessage());
            $this->dispatch('alert', type: 'error', message: 'Failed to load repost details. Please try again.');
        }
    }

    private function checkRepostEligibility()
    {
        // Check 24-hour limit using cache
        $todayRepostCount = Cache::remember(
            'user_reposts_today_' . user()->urn,
            60, // 1 minute cache
            fn() => ModelsRepost::where('reposter_urn', user()->urn)
                ->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])
                ->count()
        );

        if ($todayRepostCount >= 20) {
            $endOfDay = Carbon::today()->addDay();
            $hoursLeft = round(now()->diffInHours($endOfDay));
            $this->dispatch('alert', type: 'error', message: "You have reached your 24 hour repost limit. You can repost again in {$hoursLeft} hours.");
            $this->showRepostActionModal = false;
            return false;
        }

        // Check 12-hour limit
        if (!$this->canRepost12Hours(user()->urn)) {
            $now = Carbon::now();
            $availableTime = $this->availableRepostTime;
            $diff = $now->diff($availableTime);

            $hoursLeft = $diff->h;
            $minutesLeft = $diff->i;

            $message = "You have reached your 12 hour repost limit. You can repost again in ";
            if ($hoursLeft > 0) {
                $message .= "{$hoursLeft} hour" . ($hoursLeft > 1 ? "s" : "");
            }
            if ($hoursLeft > 0 && $minutesLeft > 0) {
                $message .= " ";
            }
            if ($minutesLeft > 0) {
                $message .= "{$minutesLeft} minute" . ($minutesLeft > 1 ? "s" : "");
            }

            $this->dispatch('alert', type: 'error', message: $message);
            $this->showRepostActionModal = false;
            return false;
        }

        return true;
    }

    private function canRepost12Hours($userUrn)
    {
        $twelveHoursAgo = Carbon::now()->subHours(12);

        $reposts = Cache::remember(
            "user_reposts_12h_{$userUrn}",
            300, // 5 minutes cache
            fn() => ModelsRepost::where('reposter_urn', $userUrn)
                ->where('created_at', '>=', $twelveHoursAgo)
                ->orderBy('created_at', 'asc')
                ->take(10)
                ->get()
        );

        if ($reposts->count() < 10) {
            return true;
        }

        $oldestRepostTime = $reposts->first()->created_at;
        $this->availableRepostTime = $oldestRepostTime->copy()->addHours(12);

        return Carbon::now()->greaterThanOrEqualTo($this->availableRepostTime);
    }

    private function checkUserInteractions()
    {
        // Reset states
        $this->reset(['liked', 'alreadyLiked', 'followed', 'alreadyFollowing']);

        try {
            $httpClient = Http::timeout(5)->withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Check like status
            if ($this->campaign->likeable == 0) {
                $this->liked = false;
            } else {
                $likeAble = UserAnalytics::where('owner_user_urn', $this->campaign?->music?->user?->urn)
                    ->where('act_user_urn', user()->urn)
                    ->liked()
                    ->where('source_type', get_class($this->campaign?->music))
                    ->where('source_id', $this->campaign?->music?->id)
                    ->exists();

                if ($likeAble) {
                    $this->liked = false;
                    $this->alreadyLiked = true;
                }
            }

            // Check follow status
            $userUrn = $this->campaign->user?->urn;
            $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

            if ($checkResponse->getStatusCode() === 200) {
                $this->followed = false;
                $this->alreadyFollowing = true;
            }

            // Disable comment if not allowed
            if ($this->campaign->commentable == 0) {
                $this->commented = null;
            }
        } catch (\Exception $e) {
            Log::warning('Error checking user interactions: ' . $e->getMessage());
        }
    }

    public function repost()
    {
        $this->validate($this->commentedRules());

        try {
            $currentUserUrn = user()->urn;

            // Quick validation checks
            if ($this->campaign->user_urn === $currentUserUrn) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost your own campaign.');
                return;
            }

            if (ModelsRepost::where('reposter_urn', $currentUserUrn)
                ->where('campaign_id', $this->campaign->id)
                ->exists()
            ) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                return;
            }

            // Determine music URN
            $musicUrn = match ($this->campaign->music_type) {
                Track::class => $this->campaign->music->urn,
                Playlist::class => $this->campaign->music->soundcloud_urn,
                default => null
            };

            if (!$musicUrn) {
                $this->dispatch('alert', type: 'error', message: 'Invalid music type.');
                return;
            }

            $httpClient = Http::timeout(10)->withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Perform repost and interactions
            $result = $this->performRepostActions($httpClient, $musicUrn);

            if ($result['success']) {
                // Send notification email
                if (hasEmailSentPermission('em_repost_accepted', $this->campaign->user->urn)) {
                    NotificationMailSent::dispatch([[
                        'email' => $this->campaign->user->email,
                        'subject' => 'Repost Notification',
                        'title' => 'Dear ' . $this->campaign->user->name,
                        'body' => 'Your ' . $this->campaign->title . ' has been reposted successfully.',
                    ]]);
                }

                // Save repost to database
                $this->campaignService->syncReposts(
                    $this->campaign,
                    user(),
                    $this->campaign->music->soundcloud_track_id ?? null,
                    $result['actions']
                );

                // Clear relevant caches
                Cache::forget('user_reposts_today_' . user()->urn);
                Cache::forget('user_reposts_12h_' . user()->urn);

                $message = 'Campaign music reposted successfully.';
                if (!$result['actions']['likeable']) {
                    $message .= ' (Like skipped - already liked)';
                }

                $this->dispatch('alert', type: 'success', message: $message);
                $this->dispatch('repost-success', campaignId: $this->campaign->id);
                $this->dispatch('refreshCampaigns');

                $this->closeConfirmModal();
            } else {
                $this->dispatch('alert', type: 'error', message: $result['message']);
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id' => $this->campaign->id ?? null,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
        }
    }

    private function performRepostActions($httpClient, $musicUrn)
    {
        try {
            // Get initial counts
            $endpoint = $this->campaign->music_type === Track::class
                ? "/tracks/{$musicUrn}"
                : "/playlists/{$musicUrn}";

            $initialData = $this->soundCloudService->makeGetApiRequest(
                endpoint: $endpoint,
                errorMessage: 'Failed to fetch initial data'
            );

            $countField = $this->campaign->music_type === Track::class
                ? 'reposts_count'
                : 'repost_count';
            $previousReposts = $initialData['collection'][$countField] ?? 0;

            // Perform repost
            $repostEndpoint = $this->campaign->music_type === Track::class
                ? "{$this->baseUrl}/reposts/tracks/{$musicUrn}"
                : "{$this->baseUrl}/reposts/playlists/{$musicUrn}";

            $repostResponse = $httpClient->post($repostEndpoint);

            if (!$repostResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to repost to SoundCloud.'
                ];
            }

            // Verify repost count increased
            $newData = $this->soundCloudService->makeGetApiRequest(
                endpoint: $endpoint,
                errorMessage: 'Failed to verify repost'
            );
            $newReposts = $newData['collection'][$countField] ?? 0;

            if ($newReposts <= $previousReposts) {
                return [
                    'success' => false,
                    'message' => 'You have already reposted this from SoundCloud.'
                ];
            }

            // Perform additional actions in parallel
            $actions = [
                'likeable' => false,
                'comment' => false,
                'follow' => true
            ];

            // Like action
            if ($this->liked && $this->campaign->likeable) {
                $likeEndpoint = $this->campaign->music_type === Track::class
                    ? "{$this->baseUrl}/likes/tracks/{$musicUrn}"
                    : "{$this->baseUrl}/likes/playlists/{$musicUrn}";

                $likeResponse = $httpClient->post($likeEndpoint);
                $actions['likeable'] = $likeResponse->successful();
            }

            // Comment action
            if ($this->commented && $this->campaign->music_type === Track::class && $this->campaign->music->commentable) {
                $commentData = [
                    'comment' => [
                        'body' => $this->commented,
                        'timestamp' => time()
                    ]
                ];
                $commentResponse = $httpClient->post("{$this->baseUrl}/tracks/{$musicUrn}/comments", $commentData);
                $actions['comment'] = $commentResponse->successful();
            }

            // Follow action
            if ($this->followed && !$this->alreadyFollowing) {
                $followResponse = $httpClient->put("{$this->baseUrl}/me/followings/{$this->campaign->user->urn}");
                $actions['follow'] = $followResponse->successful();
            }

            return [
                'success' => true,
                'actions' => $actions
            ];
        } catch (\Exception $e) {
            Log::error('Error performing repost actions: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to complete repost actions.'
            ];
        }
    }

    public function closeConfirmModal(): void
    {
        $this->reset([
            'campaign',
            'liked',
            'alreadyLiked',
            'commented',
            'followed',
            'alreadyFollowing',
            'availableRepostTime'
        ]);
        $this->showRepostActionModal = false;
    }
}
