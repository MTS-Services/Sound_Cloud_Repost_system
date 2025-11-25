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

    public $campaign;
    public $isLoading = true;

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
        // Immediately set loading state
        $this->isLoading = true;

        try {
            // Clear all previous data first
            $this->resetRepostData();

            // Load campaign with optimized eager loading
            $this->campaign = ModelsCampaign::with([
                'music.user:id,urn,name,email,avatar',
                'music.user.userInfo:id,user_id,followers_count',
                'user:id,urn,name,email'
            ])->findOrFail(decrypt($campaignId));

            // Fast parallel checks
            if (!$this->checkRepostEligibility()) {
                $this->resetRepostData();
                return;
            }

            $this->checkUserInteractions();

            // Data loaded, ready to show
            $this->isLoading = false;
        } catch (\Exception $e) {
            Log::error('Error loading repost modal: ' . $e->getMessage());
            $this->dispatch('alert', type: 'error', message: 'Failed to load repost details. Please try again.');
            $this->dispatch('closeRepostModal');
            $this->resetRepostData();
        }
    }

    private function resetRepostData()
    {
        $this->reset([
            'campaign',
            'liked',
            'alreadyLiked',
            'commented',
            'followed',
            'alreadyFollowing',
            'availableRepostTime',
            'isLoading'
        ]);
        $this->liked = true;
        $this->followed = true;
        $this->isLoading = true;
    }

    private function checkRepostEligibility()
    {
        $userUrn = user()->urn;

        // Check 24-hour limit using cache
        $todayRepostCount = Cache::remember(
            "user_reposts_today_{$userUrn}",
            60,
            fn() => ModelsRepost::where('reposter_urn', $userUrn)
                ->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])
                ->count()
        );

        if ($todayRepostCount >= 20) {
            $endOfDay = Carbon::today()->addDay();
            $hoursLeft = round(now()->diffInHours($endOfDay));
            $this->dispatch('alert', type: 'error', message: "You have reached your 24 hour repost limit. You can repost again in {$hoursLeft} hours.");
            $this->dispatch('closeRepostModal');
            return false;
        }

        // Check 12-hour limit
        if (!$this->canRepost12Hours($userUrn)) {
            $now = Carbon::now();
            $diff = $now->diff($this->availableRepostTime);

            $message = "You have reached your 12 hour repost limit. You can repost again in ";
            if ($diff->h > 0) {
                $message .= "{$diff->h} hour" . ($diff->h > 1 ? "s" : "");
                if ($diff->i > 0) $message .= " ";
            }
            if ($diff->i > 0) {
                $message .= "{$diff->i} minute" . ($diff->i > 1 ? "s" : "");
            }

            $this->dispatch('alert', type: 'error', message: $message);
            $this->dispatch('closeRepostModal');
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
        $this->liked = true;
        $this->followed = true;
        $this->alreadyLiked = false;
        $this->alreadyFollowing = false;

        try {
            // Check like status - single optimized query
            if ($this->campaign->likeable == 0) {
                $this->liked = false;
            } else {
                $this->alreadyLiked = UserAnalytics::where([
                    ['owner_user_urn', '=', $this->campaign->music->user->urn],
                    ['act_user_urn', '=', user()->urn],
                    ['type', '=', UserAnalytics::TYPE_LIKE],
                    ['source_type', '=', get_class($this->campaign->music)],
                    ['source_id', '=', $this->campaign->music->id]
                ])->exists();

                if ($this->alreadyLiked) {
                    $this->liked = false;
                }
            }

            // Check follow status - single API call
            $response = Http::timeout(3)
                ->withHeaders(['Authorization' => 'OAuth ' . user()->token])
                ->get("{$this->baseUrl}/me/followings/{$this->campaign->user->urn}");

            if ($response->successful()) {
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

            // Check if already reposted
            if (ModelsRepost::where([
                ['reposter_urn', '=', $currentUserUrn],
                ['campaign_id', '=', $this->campaign->id]
            ])->exists()) {
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

            // Perform repost
            $result = $this->performRepostActions($musicUrn);

            if ($result['success']) {
                // Send notification email asynchronously
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

                // Clear caches
                Cache::forget("user_reposts_today_{$currentUserUrn}");
                Cache::forget("user_reposts_12h_{$currentUserUrn}");

                $message = 'Campaign music reposted successfully.';
                if (!$result['actions']['likeable']) {
                    $message .= ' (Like skipped - already liked)';
                }

                $this->dispatch('alert', type: 'success', message: $message);
                $this->dispatch('repost-success', campaignId: $this->campaign->id);
                $this->dispatch('refreshCampaigns');
                $this->dispatch('closeRepostModal');

                // Clear all data after successful repost
                $this->resetRepostData();
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

    private function performRepostActions($musicUrn)
    {
        try {
            $httpClient = Http::timeout(8)->withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

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

            // Initialize actions
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
                $commentResponse = $httpClient->post(
                    "{$this->baseUrl}/tracks/{$musicUrn}/comments",
                    ['comment' => ['body' => $this->commented, 'timestamp' => time()]]
                );
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
        $this->dispatch('closeRepostModal');
        $this->resetRepostData();
    }
}
