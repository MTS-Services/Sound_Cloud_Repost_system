<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Jobs\TrackViewCount;
use App\Models\Campaign;
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
use App\Services\User\StarredUserService;
use App\Services\User\UserSettingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class RepostRequest2nd extends Component
{
    public $repostRequests;
    public $pendingRequestCount = 0;
    public $track;
    public $activeMainTab = 'incoming_request';

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    public $requestReceiveable = false;
    public bool $showRepostRequestModal = false;
    public bool $showRepostConfirmationModal = false;
    public int $todayRepost = 0;

    // Confirmation Repost
    public $totalRepostPrice = 0;
    public $request = null;
    public $liked = true;
    public $alreadyLiked = false;
    public string $commented = '';
    public $followed = true;
    public $alreadyFollowing = false;

    protected SoundCloudService $soundCloudService;
    protected UserSettingsService $userSettingsService;
    protected ?AnalyticsService $analyticsService = null;
    protected RepostRequestService $repostRequestService;
    protected StarredUserService $starredUserService;

    public function boot(SoundCloudService $soundCloudService, UserSettingsService $userSettingsService, RepostRequestService $repostRequestService, AnalyticsService $analyticsService, StarredUserService $starredUserService)
    {
        $this->soundCloudService = $soundCloudService;
        $this->userSettingsService = $userSettingsService;
        $this->analyticsService = $analyticsService;
        $this->repostRequestService = $repostRequestService;
        $this->starredUserService = $starredUserService;
    }

    public function mount()
    {
        $this->requestReceiveable = UserSetting::self()->value('accept_repost') ?? 0 ? false : true;
        $this->dataLoad();

        // Clear session tracking on fresh page load only
        if (!request()->hasHeader('X-Livewire')) {
            session()->forget('repost_request_playback_tracking');
            Log::info('Repost request tracking cleared on fresh page load');
        }

        if (session()->get('repostedRequestIds')) {
            session()->forget('repostedRequestIds');
        }
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function updatedActiveMainTab()
    {
        return $this->redirect(route('user.reposts-request') . '?tab=' . $this->activeMainTab, navigate: true);
    }

    #[On('confirmRepost')]
    public function confirmRepost($requestId)
    {
        if ($this->todayRepost >= 20) {
            $endOfDay = Carbon::today()->addDay();
            $hoursLeft = round(now()->diffInHours($endOfDay));
            $this->dispatch('alert', type: 'error', message: "You have reached your 24 hour repost limit. You can repost again {$hoursLeft} hours later.");
            return;
        }

        $this->showRepostConfirmationModal = true;
        $this->request = ModelsRepostRequest::findOrFail($requestId)->load(['music', 'requester', 'targetUser']);

        $this->reset(['liked', 'alreadyLiked', 'commented', 'followed', 'alreadyFollowing']);
        $baseQuery = UserAnalytics::where('owner_user_urn', $this->request?->music?->user?->urn)
            ->where('act_user_urn', user()->urn);

        $likeAble = (clone $baseQuery)->liked()->where('source_type', get_class($this->request?->music))
            ->where('source_id', $this->request?->music?->id)->first();

        if ($likeAble !== null) {
            $this->liked = false;
            $this->alreadyLiked = true;
        }
        if ($this->request->likeable == 0) {
            $this->liked = false;
        }
        if ($this->request->commentable == 0) {
            $this->commented = '';
        }

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ]);
        $userUrn = $this->request->requester?->urn;
        $checkResponse = $httpClient->get("{$this->baseUrl}/me/followings/{$userUrn}");

        if ($checkResponse->getStatusCode() === 200) {
            $this->followed = false;
            $this->alreadyFollowing = true;
        }
    }

    public function repost($requestId)
    {
        $result = $this->repostRequestService->handleRepost($requestId, $this->commented, $this->liked, $this->followed);

        if ($result['status'] === 'success') {
            session()->push('repostedRequestIds', $requestId);
            $this->dispatch('repost-success', requestId: $requestId);
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
        $query = ModelsRepostRequest::orderBy('created_at', 'desc')->with(['music', 'targetUser.starredUsers', 'requester.starredUsers']);
        $tab = request()->query('tab', $this->activeMainTab);
        $this->activeMainTab = $tab;

        switch ($tab) {
            case 'incoming_request':
                $query->incoming()->pending()->notExpired();
                break;
            case 'outgoing_request':
                $query->outgoing()->where('status', '!=', ModelsRepostRequest::STATUS_CANCELLED);
                break;
            case 'previously_reposted':
                $query->incoming()->where('campaign_id', null)->approved();
                break;
        }

        $this->repostRequests = $query->orderBy('status', 'asc')->take(100)->get();
        Bus::dispatch(new TrackViewCount($this->repostRequests, user()->urn, 'request'));

        return $this->repostRequests;
    }

    public function responseReset()
    {
        $responseAt = UserSetting::self()->value('response_rate_reset');
        if ($responseAt && Carbon::parse($responseAt)->greaterThan(now()->subMonth(1))) {
            $this->dispatch('alert', type: 'error', message: 'You can only reset your response rate once every 30 days.');
            return;
        }
        $userUrn = user()->urn;
        $this->userSettingsService->createOrUpdate($userUrn, ['response_rate_reset' => now()]);
        $this->dataLoad();
        $this->dispatch('alert', type: 'success', message: 'Your response rate has been reset.');
    }

    #[On('starMarkUser')]
    public function starMarkUser($userUrn)
    {
        try {
            $status = $this->starredUserService->toggleStarMark(user()->urn, $userUrn);
            if (!$status) {
                $this->dispatch('alert', type: 'error', message: 'You cannot star mark yourself.');
            }
        } catch (\Exception $e) {
            Log::error('Error in starMarkUser: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'N/A',
                'target_user_urn' => $userUrn ?? 'N/A',
                'exception' => $e,
            ]);
            $this->dispatch('alert', type: 'error', message: 'An error occurred while updating star mark status. Please try again later.');
        }
    }

    #[On('updatePlayCount')]
    public function updatePlayCount($requestId)
    {
        $cacheKey = "play_count_debounce_{$requestId}_" . user()->urn;

        if (Cache::has($cacheKey)) {
            Log::info("updatePlayCount debounced for request {$requestId}");
            return;
        }

        Cache::put($cacheKey, true, now()->addSeconds(2));

        $request = ModelsRepostRequest::with('music')->findOrFail($requestId);
        $music = $request->music;

        $response = $this->analyticsService->recordAnalytics(
            source: $music,
            actionable: $request,
            type: UserAnalytics::TYPE_PLAY,
            genre: $music->genre ?? 'anyGenre'
        );

        if ($response != false && $response != null) {
            $request->increment('playback_count');
        }
    }

    public function render()
    {
        $user = User::withCount([
            'reposts as reposts_count_today' => function ($query) {
                $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
            },
            'campaigns' => function ($query) {
                $query->where('status', Campaign::STATUS_OPEN);
            },
            'requests' => function ($query) {
                $query->pending();
            },
        ])->find(user()->id);

        $data['dailyRepostCurrent'] = $user->reposts_count_today ?? 0;
        $data['totalMyCampaign'] = $user->campaigns_count ?? 0;
        $data['pendingRequests'] = $user->requests_count ?? 0;

        return view(
            'livewire.user.repost-request-2nd',
            [
                'repostRequests' => $this->repostRequests,
                'data' => $data,
            ]
        );
    }
}
