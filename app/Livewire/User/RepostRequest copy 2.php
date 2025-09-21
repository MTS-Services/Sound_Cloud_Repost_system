<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\CreditTransaction;
use App\Models\RepostRequest as ModelsRepostRequest;
use App\Models\Repost;
use App\Models\Track;
use App\Models\UserSetting;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\UserSettingsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class RepostRequestCopy extends Component
{
    public $repostRequests;
    public $track;
    public $activeMainTab = 'incoming_request'; // Default tab

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';
    public $requestReceiveable = false;
    public bool $showRepostRequestModal = false;
    public bool $showRepostConfirmationModal = false;

    // Confirmation Repost
    public $totalRepostPrice = 0;
    public $request = null;
    public $liked = false;
    public $commented = null;
    public $followed = true;

    protected SoundCloudService $soundCloudService;
    protected UserSettingsService $userSettingsService;

    public function boot(SoundCloudService $soundCloudService, UserSettingsService $userSettingsService)
    {
        $this->soundCloudService = $soundCloudService;
        $this->userSettingsService = $userSettingsService;
    }

    public function mount()
    {
        $this->requestReceiveable = UserSetting::self()->value('accept_repost') ?? 0 ? false : true;
        $this->dataLoad();
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function confirmRepost($requestId)
    {
        $this->showRepostConfirmationModal = true;
        $this->request = ModelsRepostRequest::findOrFail($requestId)->load('track', 'requester');
    }

    public function repost($requestId)
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        try {
            $currentUserUrn = user()->urn;
            // Check if the user has already reposted this specific request
            if (
                Repost::where('reposter_urn', $currentUserUrn)
                ->where('repost_request_id', $requestId)
                ->exists()
            ) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this request.');
                return;
            }

            // Find the request and load its track
            $request = ModelsRepostRequest::findOrFail($requestId)->load('track', 'requester');

            // Ensure track is associated with the request
            if (!$request->track) {
                $this->dispatch('alert', type: 'error', message: 'Track not found for this request.');
                return;
            }

            $soundcloudRepostId = null;

            // Prepare HTTP client with authorization header
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            $commentSoundcloud = [
                'comment' => [
                    'body' => $this->commented,
                    'timestamp' => time()
                ]
            ];

            $response = null;
            $like_response = null;
            $comment_response = null;
            $follow_response = null;

            // Repost the track to SoundCloud
            $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$request->track_urn}");
            if ($this->commented) {
                $comment_response = $httpClient->post("{$this->baseUrl}/tracks/{$request->track_urn}/comments", $commentSoundcloud);
                if (!$comment_response->successful()) {
                    $this->dispatch('alert', type: 'error', message: 'Failed to comment on track.');
                }
            }
            if ($this->liked) {
                $like_response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$request->track_urn}");
                if (!$like_response->successful()) {
                    $this->dispatch('alert', type: 'error', message: 'Failed to like track.');
                }
            }
            if ($this->followed) {
                $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$request->requester_urn}");
                if (!$follow_response->successful()) {
                    $this->dispatch('alert', type: 'error', message: 'Failed to follow user.');
                }
            }
            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $request->requester_urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $request->requester->email,
                            'subject' => 'Repost Request Accepted',
                            'title' => 'Dear ' . $request->requester->name,
                            'body' => 'Your request for repost has been accepted. Please login to your Repostchain account to listen to the music.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }
                $soundcloudRepostId = $response->json('id');

                $trackOwnerUrn = $request->track->user?->urn ?? $request->user?->urn;
                $trackOwnerName = $request->track->user?->name ?? $request->user?->name;

                DB::transaction(function () use ($requestId, $request, $currentUserUrn, $soundcloudRepostId, $trackOwnerUrn, $trackOwnerName) {
                    // Create repost record
                    $repost = Repost::create([
                        'reposter_urn' => $currentUserUrn,
                        'repost_request_id' => $requestId,
                        'campaign_id' => $request->campaign_id,
                        'music_id' => $request->track->id,
                        'music_type' => Track::class,
                        'soundcloud_repost_id' => $soundcloudRepostId,
                        'track_owner_urn' => $trackOwnerUrn,
                        'reposted_at' => now(),
                        'credits_earned' => (float) repostPrice(user()),
                    ]);
                    if ($this->commented) {
                        $repost->increment('comment_count', 1);
                    }
                    if ($this->liked) {
                        $repost->increment('like_count', 1);
                    }
                    if ($this->followed) {
                        $repost->increment('followowers_count', 1);
                    }

                    $request->update([
                        'status' => ModelsRepostRequest::STATUS_APPROVED,
                        'completed_at' => now(),
                        'responded_at' => now(),
                    ]);

                    // Create the CreditTransaction record
                    CreditTransaction::create([
                        'receiver_urn' => $currentUserUrn,
                        'sender_urn' => $request->user?->urn,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                        'source_id' => $request->id,
                        'source_type' => RepostRequest::class,
                        'status' => CreditTransaction::STATUS_SUCCEEDED,
                        'transaction_type' => CreditTransaction::TYPE_EARN,
                        'amount' => 0,
                        'credits' => (float) repostPrice(user()) + ($this->commented ? 2 : 0) + ($this->liked ? 2 : 0),
                        'description' => "Repost From Direct Request",
                        'metadata' => [
                            'repost_id' => $repost->id,
                            'repost_request_id' => $request->id,
                            'soundcloud_repost_id' => $soundcloudRepostId,
                        ]
                    ]);
                });

                $this->dispatch('alert', type: 'success', message: 'Request reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'request_id' => $requestId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                $this->dispatch('alert', type: 'error', message: 'Failed to repost to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
            return;
        }
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

    public function setActiveTab($tab)
    {
        $this->activeMainTab = $tab;
        $this->dataLoad();
    }

    public function dataLoad()
    {
        $query = ModelsRepostRequest::with(['track', 'targetUser'])->directRequest();

        switch ($this->activeMainTab) {
            case 'incoming_request':
                $query->incoming()->notExpired()->pending();
                break;
            case 'outgoing_request':
                $query->outgoing();
                break;
            case 'previously_reposted':
                $query->incoming()->approved();
                break;
            case 'expired_request':
                $query->incoming()->expired();
                break;
        }
        // Order by created_at desc and paginate
        return $this->repostRequests = $query->orderBy('status', 'asc')->take(10)->get();
    }

    public function render()
    {
        return view('livewire.user.repost-request');
    }
}
