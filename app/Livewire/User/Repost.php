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

    public $liked = true;
    public $alreadyLiked = false;
    public $commented = null;
    public $followed = true;
    public $alreadyFollowing = false;
    public $availableRepostTime = null;
    public $isLoading = true;

    // Budget check properties
    public $canAffordLike = true;
    public $canAffordComment = true;

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
            $this->resetModalState();
            $this->soundCloudService->refreshUserTokenIfNeeded(user());

            // Fast parallel checks using cache
            $eligibility = $this->checkRepostEligibility();

            if (!$eligibility) {
                return;
            }

            $this->campaign = ModelsCampaign::with([
                'music.user:id,urn,name,email,avatar',
                'music.user.userInfo:id,user_urn,followers_count',
                'user:id,urn,name,email'
            ])->findOrFail($campaignId);

            if ($this->campaign) {
                $this->checkUserInteractions();
                $this->checkBudgetAvailability(); // NEW: Check budget when modal opens
                $this->showRepostActionModal = true;
                $this->isLoading = false;
            }
        } catch (\Exception $e) {
            Log::error('Error loading repost modal: ' . $e->getMessage(), [
                'campaign_id' => $campaignId,
                'exception' => $e
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to load repost details. Please try again.');
            $this->resetModalState();
            $this->dispatch('reset-submission');
        }
    }

    private function resetModalState()
    {
        $this->reset([
            'campaign',
            'liked',
            'alreadyLiked',
            'commented',
            'followed',
            'alreadyFollowing',
            'availableRepostTime',
            'canAffordLike',
            'canAffordComment'
        ]);

        $this->liked = true;
        $this->followed = true;
        $this->alreadyLiked = false;
        $this->alreadyFollowing = false;
        $this->commented = null;
        $this->availableRepostTime = null;
        $this->canAffordLike = true;
        $this->canAffordComment = true;

        $this->isLoading = true;

        $this->resetValidation();
        $this->resetErrorBag();
    }

    private function checkRepostEligibility(): bool
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
            $this->resetModalState();
            $this->dispatch('reset-submission');
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
            $this->resetModalState();
            $this->dispatch('reset-submission');
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
        $this->liked = true;
        $this->followed = true;
        $this->alreadyLiked = false;
        $this->alreadyFollowing = false;

        try {
            $httpClient = Http::timeout(5)->withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            if ($this->campaign->likeable == 0) {
                $this->liked = false;
            } else {
                $likeAble = UserAnalytics::where([
                    ['owner_user_urn', '=', $this->campaign->music->user->urn],
                    ['act_user_urn', '=', user()->urn],
                    ['type', '=', UserAnalytics::TYPE_LIKE],
                    ['source_type', '=', get_class($this->campaign->music)],
                    ['source_id', '=', $this->campaign->music->id]
                ])->exists();

                if ($likeAble) {
                    $this->liked = false;
                    $this->alreadyLiked = true;
                }
            }

            $userUrn = $this->campaign->user?->urn;
            $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

            if ($checkResponse->getStatusCode() === 200) {
                $this->followed = false;
                $this->alreadyFollowing = true;
            }

            if ($this->campaign->commentable == 0) {
                $this->commented = null;
            }
        } catch (\Exception $e) {
            Log::warning('Error checking user interactions: ' . $e->getMessage());
        }
    }

    /**
     * NEW METHOD: Check budget availability when modal opens
     * Determines if user can afford like and/or comment based on remaining budget
     */
    private function checkBudgetAvailability()
    {
        $repostPrice = user()->repost_price;
        $remainingBudget = $this->campaign->budget_credits - $this->campaign->credits_spent;

        $likeCost = 2;
        $commentCost = 2;

        // Calculate worst case: if user checks both like AND comment
        $maxPossibleCost = $repostPrice + $likeCost + $commentCost;

        // If budget can't cover repost + both actions, disable individual bonuses
        if ($remainingBudget < $maxPossibleCost) {
            // Check what they can actually afford
            $this->canAffordLike = $remainingBudget >= ($repostPrice + $likeCost);
            $this->canAffordComment = $remainingBudget >= ($repostPrice + $commentCost);

            // If both would be true but combined they exceed budget, disable both
            if ($this->canAffordLike && $this->canAffordComment) {
                $this->canAffordLike = false;
                $this->canAffordComment = false;
            }
        } else {
            // Budget can cover everything, show both bonuses
            $this->canAffordLike = true;
            $this->canAffordComment = true;
        }
    }

    public function repost()
    {
        // CRITICAL: Dispatch reset event at the start
        $this->dispatch('reset-submission');

        try {
            $this->validate($this->commentedRules());

            $currentUserUrn = user()->urn;

            if ($this->campaign->user_urn === $currentUserUrn) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost your own campaign.');
                $this->dispatch('reset-submission');
                return;
            }

            if (ModelsRepost::where([
                ['reposter_urn', '=', $currentUserUrn],
                ['campaign_id', '=', $this->campaign->id]
            ])->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                $this->dispatch('reset-submission');
                return;
            }

            $musicUrn = match ($this->campaign->music_type) {
                Track::class => $this->campaign->music->urn,
                Playlist::class => $this->campaign->music->soundcloud_urn,
                default => null
            };

            if (!$musicUrn) {
                $this->dispatch('alert', type: 'error', message: 'Invalid music type.');
                $this->dispatch('reset-submission');
                return;
            }

            $httpClient = Http::timeout(10)->withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            $result = $this->performRepostActions($httpClient, $musicUrn);

            if ($result['success']) {
                if (hasEmailSentPermission('em_repost_accepted', $this->campaign->user->urn)) {
                    NotificationMailSent::dispatch([[
                        'email' => $this->campaign->user->email,
                        'subject' => 'Repost Notification',
                        'title' => 'Dear ' . $this->campaign->user->name,
                        'body' => 'Your ' . $this->campaign->title . ' has been reposted successfully.',
                    ]]);
                }

                $this->campaignService->syncReposts(
                    $this->campaign,
                    user(),
                    $this->campaign->music->soundcloud_track_id ?? null,
                    $result['actions']
                );

                Cache::forget("user_reposts_today_{$currentUserUrn}");
                Cache::forget("user_reposts_12h_{$currentUserUrn}");

                $message = 'Campaign music reposted successfully.';
                if (!$result['actions']['likeable']) {
                    $message .= ' (Like skipped - already liked)';
                }

                $this->dispatch('alert', type: 'success', message: $message);
                $this->dispatch('repost-success', campaignId: $this->campaign->id);
                $this->dispatch('refreshCampaigns');
                session()->push('repostedIds', $this->campaign->id);

                $this->closeConfirmModal();
            } else {
                $this->dispatch('alert', type: 'error', message: $result['message']);
                $this->dispatch('reset-submission');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('reset-submission');
            throw $e;
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id' => $this->campaign->id ?? null,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            $this->dispatch('reset-submission');
        }
    }

    private function performRepostActions($httpClient, $musicUrn)
    {
        try {

            // --------------------------------------------------
            // 🔹 Detect endpoint and fetch initial repost count
            // --------------------------------------------------
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

            // --------------------------------------------------
            // 🔹 Perform repost
            // --------------------------------------------------
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


            // --------------------------------------------------
            // 🔹 Verify repost
            // --------------------------------------------------
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

            // --------------------------------------------------
            // 🔹 Default action results
            // --------------------------------------------------

            $actions = [
                'likeable' => false,
                'comment' => false,
                'follow' => false,
                'canAffordLike' => $this->canAffordLike,
                'canAffordComment' => $this->canAffordComment
            ];

            // --------------------------------------------------
            // 🔹 Determine allowed actions
            // --------------------------------------------------
            $canLike = $this->liked && $this->campaign->likeable;

            $canComment = $this->commented &&
                $this->campaign->music_type === Track::class &&
                $this->campaign->music->commentable;

            $canFollow = $this->followed && !$this->alreadyFollowing;

            // --------------------------------------------------
            // ❤️ LIKE Action
            // --------------------------------------------------
            if ($canLike) {
                $likeEndpoint = $this->campaign->music_type === Track::class
                    ? "{$this->baseUrl}/likes/tracks/{$musicUrn}"
                    : "{$this->baseUrl}/likes/playlists/{$musicUrn}";

                $likeResponse = $httpClient->post($likeEndpoint);
                $actions['likeable'] = $likeResponse->successful();
            }

            // --------------------------------------------------
            // 💬 COMMENT Action
            // --------------------------------------------------
            if ($canComment) {
                $commentData = [
                    'comment' => [
                        'body' => $this->commented,
                        'timestamp' => time()
                    ]
                ];

                $commentResponse = $httpClient->post("{$this->baseUrl}/tracks/{$musicUrn}/comments", $commentData);
                $actions['comment'] = $commentResponse->successful();
            }

            // --------------------------------------------------
            // 👤 FOLLOW Action
            // --------------------------------------------------
            if ($canFollow) {
                $followResponse = $httpClient->post("{$this->baseUrl}/users/{$this->campaign->user->urn}/follow");
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
        $this->showRepostActionModal = false;
        $this->resetModalState();
        $this->dispatch('modal-closed');
        $this->dispatch('reset-submission');
        $this->dispatch('reset-widget-initiallized');
    }
}
