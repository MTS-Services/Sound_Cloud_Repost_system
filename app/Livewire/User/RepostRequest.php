<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Jobs\TrackViewCount;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\RepostRequest as ModelsRepostRequest;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserAnalytics;
use App\Models\UserSetting;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\Mamber\RepostRequestService;
use App\Services\User\UserSettingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class RepostRequest extends Component
{
    public $repostRequests;
    public $pendingRequestCount = 0;
    public $track;
    public $activeMainTab = 'incoming_request'; // Default tab

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';
    public $playingRequests = [];
    public $playStartTimes = [];
    public $playTimes = [];
    public $playedRequests = [];
    public $repostedRequests = [];
    public $playCount = false;
    public $requestReceiveable = false;
    public bool $showRepostRequestModal = false;
    public bool $showRepostConfirmationModal = false;

    // Confirmation Repost
    public $totalRepostPrice = 0;
    public $request = null;
    public $liked = false;
    public $alreadyLiked = false;
    public string $commented = '';
    public $following = true;
    public $alreadyFollowing = false;


    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];

    protected SoundCloudService $soundCloudService;
    protected UserSettingsService $userSettingsService;
    protected ?AnalyticsService $analyticsService = null;
    protected RepostRequestService $repostRequestService;

    public function boot(SoundCloudService $soundCloudService, UserSettingsService $userSettingsService, RepostRequestService $repostRequestService, AnalyticsService $analyticsService)
    {
        $this->soundCloudService = $soundCloudService;
        $this->userSettingsService = $userSettingsService;
        $this->analyticsService = $analyticsService;
        $this->repostRequestService = $repostRequestService;
    }

    public function mount()
    {
        $this->requestReceiveable = UserSetting::self()->value('accept_repost') ?? 0 ? false : true;
        $this->dataLoad();

        foreach ($this->repostRequests as $request) {
            $this->playTimes[$request->id] = 0;
        }
    }

    public function updated()
    {
        // $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function updatedActiveMainTab()
    {
        return $this->redirect(route('user.reposts-request') . '?tab=' . $this->activeMainTab, navigate: true);
    }
    /**
     * Handle audio play event
     */
    public function handleAudioPlay($requestId)
    {
        $this->playingRequests[$requestId] = true;
        $this->playStartTimes[$requestId] = now()->timestamp;
    }

    /**
     * Handle audio pause event
     */
    public function handleAudioPause($requestId)
    {
        $this->updatePlayTime($requestId);
        unset($this->playingRequests[$requestId]);
        unset($this->playStartTimes[$requestId]);
    }

    /**
     * Handle audio time update event
     */
    public function handleAudioTimeUpdate($requestId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($requestId, $this->playedRequests)) {
            $this->playedRequests[] = $requestId;
            $this->dispatch('requestPlayedEnough', $requestId);
        }
    }

    /**
     * Handle audio ended event
     */
    public function handleAudioEnded($requestId)
    {
        $this->handleAudioPause($requestId);
    }

    /**
     * Update play time for a request
     */
    private function updatePlayTime($requestId)
    {
        if (isset($this->playStartTimes[$requestId])) {
            $playDuration = now()->timestamp - $this->playStartTimes[$requestId];
            $this->playTimes[$requestId] = ($this->playTimes[$requestId] ?? 0) + $playDuration;

            if ($this->playTimes[$requestId] >= 5 && !in_array($requestId, $this->playedRequests)) {
                $this->playedRequests[] = $requestId;
                $this->dispatch('requestPlayedEnough', $requestId);
            }
        }
    }

    /**
     * Polling method to update play times for currently playing requests
     */
    public function updatePlayingTimes()
    {
        foreach ($this->playingRequests as $requestId => $isPlaying) {
            if ($isPlaying && isset($this->playStartTimes[$requestId])) {
                $playDuration = now()->timestamp - $this->playStartTimes[$requestId];
                $totalPlayTime = ($this->playTimes[$requestId] ?? 0) + $playDuration;

                if ($totalPlayTime >= 5 && !in_array($requestId, $this->playedRequests)) {
                    $this->playedRequests[] = $requestId;
                    $this->dispatch('requestPlayedEnough', $requestId);
                }
            }
        }
    }

    /**
     * Start playing a request manually
     */
    public function startPlaying($requestId)
    {
        $this->handleAudioPlay($requestId);
    }

    /**
     * Stop playing a request manually
     */
    public function stopPlaying($requestId)
    {
        $this->handleAudioPause($requestId);
    }

    /**
     * Simulate audio progress (for testing without actual audio)
     */
    public function simulateAudioProgress($requestId, $seconds = 1)
    {
        if (!isset($this->playTimes[$requestId])) {
            $this->playTimes[$requestId] = 0;
        }

        $this->playTimes[$requestId] += $seconds;

        if ($this->playTimes[$requestId] >= 5 && !in_array($requestId, $this->playedRequests)) {
            $this->playedRequests[] = $requestId;

            $this->dispatch('alert', type: 'success', message: 'Request marked as played for 5+ seconds!');
        }
    }

    /**
     * Check if request can be reposted
     */
    public function canRepost($requestId): bool
    {
        $request = $this->repostRequests->find($requestId);

        if (!$request) {
            return false;
        }

        // Can only repost pending requests
        if ($request->status != ModelsRepostRequest::STATUS_PENDING) {
            return false;
        }

        if ($request->expired_at <= now()) {
            return false;
        }



        // Check if already reposted
        if (in_array($requestId, $this->repostedRequests)) {
            return false;
        }

        // Must have played for 5+ seconds
        $canRepost = in_array($requestId, $this->playedRequests);


        if ($canRepost && !$this->playCount) {
            Log::info('Entering playcount logic', ['requestId' => $requestId, 'canRepost' => $canRepost, 'playCount' => $this->playCount]);

            $request = $this->repostRequests->find($requestId);

            if ($request && $request->music) {
                Log::info('Request and music found', ['requestId' => $requestId]);

                try {
                    // Record analytics for the play
                    $response = $this->analyticsService->recordAnalytics(
                        source: $request->music,
                        actionable: $request,
                        type: UserAnalytics::TYPE_PLAY,
                        genre: $request->music->genre ?? 'anyGenre'
                    );

                    Log::info('Analytics response', ['requestId' => $requestId, 'response' => $response]);

                    // Only increment if analytics recording was successful
                    if ($response !== false && $response !== null) {
                        $request->increment('playback_count');
                        Log::info('Playback count incremented', ['requestId' => $requestId]);
                    } else {
                        Log::warning('Analytics failed - no increment', ['requestId' => $requestId, 'response' => $response]);
                    }

                    $this->playCount = true;
                } catch (\Exception $e) {
                    Log::error('Analytics recording failed for repost request', [
                        'requestId' => $requestId,
                        'error' => $e->getMessage()
                    ]);

                    $this->playCount = true;
                }
            } else {
                Log::warning('Request or music not found', ['requestId' => $requestId, 'hasRequest' => !!$request, 'hasMusic' => $request ? !!$request->music : false]);
            }
        } else {
            Log::info('Playcount condition not met', ['requestId' => $requestId, 'canRepost' => $canRepost, 'playCount' => $this->playCount]);
        }
        return $canRepost;
    }

    /**
     * Check if request is currently playing
     */
    public function isPlaying($requestId): bool
    {
        return isset($this->playingRequests[$requestId]) && $this->playingRequests[$requestId] === true;
    }

    /**
     * Get total play time for a request
     */
    public function getPlayTime($requestId): int
    {
        $baseTime = $this->playTimes[$requestId] ?? 0;

        if ($this->isPlaying($requestId) && isset($this->playStartTimes[$requestId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$requestId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    /**
     * Get remaining time until repost is enabled
     */
    public function getRemainingTime($requestId): int
    {
        $playTime = $this->getPlayTime($requestId);
        return max(0, 5 - $playTime);
    }

    /**
     * Handle repost action
     */

    public function confirmRepost($requestId)
    {
        if (!$this->canRepost($requestId)) {
            $this->dispatch('alert', type: 'error', message: 'You cannot repost this request. Please play it for at least 5 seconds first.');
            return;
        }
        $this->showRepostConfirmationModal = true;
        $this->request = ModelsRepostRequest::findOrFail($requestId)->load('music', 'requester');
        $response = $this->soundCloudService->getAuthUserFollowers($this->request->requester);
        if ($response->isNotEmpty()) {
            $already_following = $response->where('urn', user()->urn)->first();
            if ($already_following !== null) {
                Log::info('Repost request Page:- Already following');
                $this->following = false;
                $this->alreadyFollowing = true;
            }
        }

        if ($this->request->music) {
            if ($this->request->music_type == Track::class) {
                $favoriteData = $this->soundCloudService->fetchTracksFavorites($this->request->music);
                $searchUrn = user()->urn;
            } elseif ($this->request->music_type == Playlist::class) {
                $favoriteData = $this->soundCloudService->fetchPlaylistFavorites(user()->urn);
                $searchUrn = $this->request->music->soundcloud_urn;
            }
            $collection = collect($favoriteData['collection']);
            $found = $collection->first(function ($item) use ($searchUrn) {
                return isset($item['urn']) && $item['urn'] === $searchUrn;
            });
            if ($found) {
                $this->liked = false;
                $this->alreadyLiked = true;
            }
        }
    }
    public function repost($requestId)
    {
        if (!$this->canRepost($requestId)) {
            $this->dispatch('alert', type: 'error', message: 'You cannot repost this request. Please play it for at least 5 seconds first.');
            return;
        }

        $result = $this->repostRequestService->handleRepost($requestId, $this->commented, $this->liked, $this->following);

        if ($result['status'] === 'success') {
            $this->repostedRequests[] = $requestId;
        }

        $this->dispatch('alert', type: $result['status'], message: $result['message']);
    }

    public function declineRepostRequest($requestId)
    {
        try {
            $request = ModelsRepostRequest::findOrFail($requestId);
            $request->update([
                'status' => ModelsRepostRequest::STATUS_DECLINE,
                'rejection_reason' => 'Declined by user',
                'responded_at' => now(),
            ]);
            $repostEmailPermission = hasEmailSentPermission('em_repost_declined', $request?->requester_urn);
            if ($repostEmailPermission) {
                $datas = [
                    [
                        'email' => $request->requester->email,
                        'subject' => 'Repost Declined',
                        'title' => 'Dear ' . $request->requester->name,
                        'body' => 'Your repost request has been declined.',
                    ],
                ];
                NotificationMailSent::dispatch($datas);
            }
            $this->dataLoad();
            $this->dispatch('alert', type: 'success', message: 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to decline repost request. Please try again.');
        }
    }
    public function cancleRepostRequest($requestId)
    {
        try {
            $request = ModelsRepostRequest::findOrFail($requestId);
            $request->update([
                'status' => ModelsRepostRequest::STATUS_CANCELLED,
                'responded_at' => now(),
            ]);
            // Create credit transaction
            $creditTransaction = new CreditTransaction();
            $creditTransaction->receiver_urn = $request->requester_urn;
            $creditTransaction->transaction_type = CreditTransaction::TYPE_REFUND;
            $creditTransaction->calculation_type = CreditTransaction::CALCULATION_TYPE_DEBIT;
            $creditTransaction->source_id = $request->id;
            $creditTransaction->source_type = RepostRequest::class;
            $creditTransaction->amount = 0;
            $creditTransaction->credits = $request->credits_spent;
            $creditTransaction->description = 'Repost Request Refund';
            $creditTransaction->metadata = [
                'request_type' => 'repost_request',
                'requester_urn' => $request->requester_urn,
                'request_id' => $request->id,
            ];
            $creditTransaction->status = CreditTransaction::STATUS_SUCCEEDED;
            $creditTransaction->save();
            $this->dataLoad();
            $this->dispatch('alert', type: 'success', message: 'Repost request cancelled successfully.');
        } catch (Throwable $e) {
            Log::error("Error cancelling repost request: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to cancel repost request. Please try again.');
        }
    }
    public function requestReceiveableToggle()
    {
        $userUrn = user()->urn;
        $requestable = $this->requestReceiveable ? 1 : 0;
        $this->userSettingsService->createOrUpdate($userUrn, ['accept_repost' => $requestable]);
        $this->dataLoad();
    }

    public function dataLoad()
    {
        $query = ModelsRepostRequest::with(['music', 'targetUser']);
        $tab = request()->query('tab', $this->activeMainTab);
        $this->activeMainTab = $tab;

        switch ($tab) {
            case 'incoming_request':
                $query->incoming()->pending()->notExpired();
                break;
            case 'outgoing_request':
                $query->outgoing()->where('status', '!=', ModelsRepostRequest::STATUS_CANCELLED)->where('status', '!=', ModelsRepostRequest::STATUS_DECLINE);
                break;
            case 'previously_reposted':
                $query->incoming()->where('campaign_id', null)->approved();
                break;
        }
        // Order by created_at desc and paginate
        return $this->repostRequests = $query->orderBy('status', 'asc')->take(10)->get();
        // Order by created_at desc and paginate
        $this->repostRequests = $query->latest()->orderBy('status', 'asc')->take(10)->get();
        Bus::dispatch(new TrackViewCount($this->repostRequests, user()->urn, 'request'));

        return $this->repostRequests;
    }
    public function responseReset()
    {
        $responseAt = UserSetting::self()->value('response_rate_reset');
        if ($responseAt && Carbon::parse($responseAt)->greaterThan(now()->subDays(30))) {
            $this->dispatch('alert', type: 'error', message: 'You can only reset your response rate once every 30 days.');
            return;
        }
        $userUrn = user()->urn;
        $this->userSettingsService->createOrUpdate($userUrn, ['response_rate_reset' => now()]);
        $this->dataLoad();
        $this->dispatch('alert', type: 'success', message: 'Your response rate has been reset.');
    }

    public function render()
    {
        $user = User::withCount([
            'reposts as reposts_count_today' => function ($query) {
                $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
            },
            'campaigns',
            'requests' => function ($query) {
                $query->pending();
            },
        ])->find(user()->id);

        $data['dailyRepostCurrent'] = $user->reposts_count_today ?? 0;
        $data['totalMyCampaign'] = $user->campaigns_count ?? 0;
        $data['pendingRequests'] = $user->requests_count ?? 0;
        return view(
            'livewire.user.repost-request',
            [
                'repostRequests' => $this->repostRequests,
                'data' => $data,
            ]
        );
    }
}
