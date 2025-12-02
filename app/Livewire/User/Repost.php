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

            $this->campaign = ModelsCampaign::with([
                'music.user:id,urn,name,email,avatar',
                'music.user.userInfo:id,user_urn,followers_count',
                'user:id,urn,name,email'
            ])->findOrFail($campaignId);

            if ($this->campaign) {
                $this->checkUserInteractions();
                $this->showRepostActionModal = true;
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
            'availableRepostTime'
        ]);

        $this->liked = true;
        $this->followed = true;
        $this->alreadyLiked = false;
        $this->alreadyFollowing = false;
        $this->commented = null;
        $this->availableRepostTime = null;

        $this->resetValidation();
        $this->resetErrorBag();
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

            $actions = [
                'likeable' => false,
                'comment' => false,
                'follow' => true
            ];

            if ($this->liked && $this->campaign->likeable) {
                $likeEndpoint = $this->campaign->music_type === Track::class
                    ? "{$this->baseUrl}/likes/tracks/{$musicUrn}"
                    : "{$this->baseUrl}/likes/playlists/{$musicUrn}";

                $likeResponse = $httpClient->post($likeEndpoint);
                $actions['likeable'] = $likeResponse->successful();
            }

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
        $this->showRepostActionModal = false;
        $this->resetModalState();
        $this->dispatch('modal-closed');
        $this->dispatch('reset-submission');
    }
}
