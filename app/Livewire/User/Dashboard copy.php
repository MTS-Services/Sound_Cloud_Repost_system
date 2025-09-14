<?php

namespace App\Livewire\User;

use App\Livewire\User\RepostRequest as RepostRequestComponent;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;
use App\Models\Feature;
use App\Services\User\AnalyticsService;

use function PHPSTORM_META\type;

class Dashboard extends Component
{
    protected CreditTransactionService $creditTransactionService;
    protected SoundCloudService $soundCloudService;

    protected MyCampaignService $myCampaignService;
    protected AnalyticsService $analyticsService;

    protected FollowerAnalyzer $followerAnalyzer;

    public $total_credits;
    public $totalCount;
    public $repostRequests;
    public $recentTracks;
    public $totalCams;
    public $creditPercentage;
    public $campaignPercentage;
    public $repostRequestPercentage;

    // Campaign create modal
    public $showCampaignsModal = false;
    public $showSubmitModal = false;
    public $momentumEnabled = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $showRepostConfirmationModal = false;


    public $tracks = null;
    public $playlists = null;
    public $playlistTracks = [];
    public $activeTab = 'tracks';

    public $track = null;
    public $credit = 50;
    public $commentable = true;
    public $likeable = true;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 0;
    public $maxFollower = 100;
    public $followersLimit = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $targetGenre = 'anyGenre';
    public $user = null;
    public $allTracks;
    public $allPlaylists;

    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;
    public $trackLimit = 4;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $genres = [];
    public $showTrackTypes = false;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;

    // Properties for Add Credit functionality
    public $addCreditCampaignId = null;
    public int $addCreditCostPerRepost;
    public $addCreditCurrentBudget = null;
    public $addCreditTargetReposts = null;
    public $addCreditCreditsNeeded = 0;

    // Properties for Edit functionality
    public $editingCampaignId = null;
    public $editTitle = null;
    public $editDescription = null;
    public $editEndDate = null;
    public $editTargetReposts = null;
    public int $editCostPerRepost;
    public $editOriginalBudget = null;

    public $userFollowerAnalysis = [];
    public $followerPercentage = 0;

    protected function rules()
    {
        $rules = [
            'credit' => [
                'required',
                'integer',
                'min:50',
                function ($attribute, $value, $fail) {
                    if ($value > userCredits()) {
                        $fail('The credit is not available.');
                    }
                },
            ],
        ];

        return $rules;
    }

    protected function messages()
    {
        return [
            'credit.required' => 'Minimum credit is 100.',
            'maxFollower.required' => 'Max follower is required.',
        ];
    }

    public function boot(CreditTransactionService $creditTransactionService, SoundCloudService $soundCloudService, MyCampaignService $myCampaignService, AnalyticsService $analyticsService, FollowerAnalyzer $followerAnalyzer)
    {
        $this->creditTransactionService = $creditTransactionService;
        $this->soundCloudService = $soundCloudService;
        $this->myCampaignService = $myCampaignService;
        $this->analyticsService = $analyticsService;
        $this->followerAnalyzer = $followerAnalyzer;
    }

    public function mount()
    {
        $this->userFollowerAnalysis = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers());

        $lastWeekFollowerPercentage = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers(), 'last_week');
        $currentWeekFollowerPercentage = $this->followerAnalyzer->getQuickStats($this->soundCloudService->getAuthUserFollowers(), 'this_week');
        $lastWeek = $lastWeekFollowerPercentage['averageCredibilityScore'];
        $currentWeek = $currentWeekFollowerPercentage['averageCredibilityScore'];

        if ($lastWeek > 0) {
            $this->followerPercentage = (($currentWeek - $lastWeek) / $lastWeek) * 100;
        } else {
            $this->followerPercentage = 0;
        }
        $this->loadDashboardData();
        $this->calculateFollowersLimit();
    }
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
        }
    }
    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
    }

    public function loadDashboardData()
    {
        $this->total_credits = $this->creditTransactionService->getUserTotalCredits();

        $this->totalCount = RepostRequest::where('requester_urn', user()->urn)
            ->orWhere('status', RepostRequest::STATUS_PENDING)
            ->orWhere('status', RepostRequest::STATUS_APPROVED)
            ->orWhere('status', RepostRequest::STATUS_DECLINE)
            ->count();

        $this->repostRequests = RepostRequest::where('target_user_urn', user()->urn)
            ->with(['track', 'requester'])
            ->latest()
            ->take(5)
            ->get();

        $this->recentTracks = Track::where('user_urn', user()->urn)->orderBy('created_at_soundcloud', 'desc')->latest()->take(5)->get();

        $this->totalCams = ModelsCampaign::where('user_urn', user()->urn)
            ->orWhere('status', [ModelsCampaign::STATUS_COMPLETED, ModelsCampaign::STATUS_OPEN])
            ->count();

        // Available Credit
        $userId = user()->urn;
        $this->creditPercentage = $this->creditTransactionService->getWeeklyChangeByCredit($userId);

        // Campaign Percentage
        $this->campaignPercentage = $this->creditTransactionService->getWeeklyCampaignChange($userId);

        // Repost Request Percentage
        $this->repostRequestPercentage = $this->creditTransactionService->getWeeklyRepostRequestChange($userId);
    }




    public $tracksPage = null;
    public $playlistsPage = 1;
    public $perPage = 5;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;
    public $playListTrackShow = false;
    public $allPlaylistTracks = null;
    public $selectedPlaylistId = null;


    public function selectModalTab($tab = 'tracks')
    {
        $this->activeTab = $tab;

        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->playListTrackShow = false;
            $this->allPlaylistTracks = collect();
            $this->tracks = collect();
            $this->selectedPlaylistId = null;
            $this->fetchPlaylists();
        }
    }
    public function fetchTracks()
    {
        try {
            $this->soundCloudService->syncSelfTracks([]);

            $this->tracksPage = 1;
            $this->tracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->dispatch('alert', type: 'error', message: 'Failed to load tracks: ' . $e->getMessage());
        }
    }

    public function loadMoreTracks()
    {
        $this->tracksPage++;
        $newTracks = Track::where('user_urn', user()->urn)
            ->latest()
            ->skip(($this->tracksPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        $this->tracks = $this->tracks->concat($newTracks);
        $this->hasMoreTracks = $newTracks->count() === $this->perPage;
    }

    public function fetchPlaylists()
    {
        try {
            $this->soundCloudService->syncSelfPlaylists();

            $this->playlistsPage = 1;
            $this->playlists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->dispatch('alert', type: 'error', message: 'Failed to load playlists: ' . $e->getMessage());
        }
    }

    public function loadMorePlaylists()
    {
        $this->playlistsPage++;
        $newPlaylists = Playlist::where('user_urn', user()->urn)
            ->latest()
            ->skip(($this->playlistsPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        $this->playlists = $this->playlists->concat($newPlaylists);
        $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    }

    public function fetchPlaylistTracks()
    {
        if (!$this->playlistId) {
            $this->playlistTracks = [];
            return;
        }

        try {
            $playlist = Playlist::findOrFail($this->playlistId);

            if (!$playlist->soundcloud_urn) {
                $this->playlistTracks = [];
                $this->dispatch('alert', type: 'error', message: 'Playlist SoundCloud URN is missing.');
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'OAuth ' . user()->token,
                ])
                ->get('https://api.soundcloud.com/playlists/' . $playlist->soundcloud_urn . '/tracks');

            if ($response->successful()) {
                $tracks = $response->json();

                if (is_array($tracks)) {
                    $this->playlistTracks = collect($tracks)->filter(function ($track) {
                        return is_array($track) &&
                            isset($track['urn']) &&
                            isset($track['title']) &&
                            isset($track['user']) &&
                            is_array($track['user']) &&
                            isset($track['user']['username']);
                    })->values()->toArray();
                } else {
                    $this->playlistTracks = [];
                }
            } else {
                $this->playlistTracks = [];
                $this->dispatch('alert', type: 'error', message: 'Failed to load playlist tracks from SoundCloud: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            $this->dispatch('alert', type: 'error', message: 'Failed to fetch playlist tracks: ' . $e->getMessage());
            Log::error('Playlist tracks fetch error: ' . $e->getMessage(), [
                'playlist_id' => $this->playlistId,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }
    public function toggleCampaignsModal()
    {
        $this->reset([
            'title',
            'description',
            'showAddCreditModal',
            'addCreditCampaignId',
            'addCreditCostPerRepost',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'addCreditCreditsNeeded',
            'showEditCampaignModal',
            'editTitle',
            'editingCampaignId',
            'editDescription',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'showCancelWarningModal',
        ]);

        if ($this->myCampaignService->thisMonthCampaignsCount() >= (int) userFeatures()[Feature::KEY_SIMULTANEOUS_CAMPAIGNS]) {
            return $this->dispatch('alert', type: 'error', message: 'You have reached the maximum number of campaigns for this month.');
        }

        $this->activeTab = 'tracks';
        $this->tracks = collect();
        $this->playlists = collect();
        $this->playlistTracks = [];

        $this->showCampaignsModal = !$this->showCampaignsModal;

        if ($this->showCampaignsModal) {
            $this->selectModalTab('tracks');
        }
    }

    public function toggleSubmitModal($type, $id)
    {
        $this->reset([
            'title',
            'description',
            'showAddCreditModal',
            'addCreditCampaignId',
            'addCreditCostPerRepost',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'addCreditCreditsNeeded',
            'showEditCampaignModal',
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'showCancelWarningModal',
            // 'campaignToDeleteId',
            // 'refundAmount',
            // 'showBudgetWarning',
            // 'budgetWarningMessage',
            // 'canSubmit',
        ]);

        if (userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showSubmitModal = false;
            return;
        } else {
            $this->showLowCreditWarningModal = false;
        }

        $this->showSubmitModal = true;

        try {
            if ($type === 'track') {
                $this->track = Track::findOrFail($id);
                if (!$this->track->urn || !$this->track->title) {
                    throw new \Exception('Track data is incomplete');
                }
                $this->musicId = $this->track->id;
                $this->musicType = Track::class;
                $this->title = $this->track->title . ' Campaign';
            } elseif ($type === 'playlist') {
                $playlist = Playlist::findOrFail($id);

                if (!$playlist->title) {
                    throw new \Exception('Playlist data is incomplete');
                }

                $this->playlistId = $id;
                $this->title = $playlist->title . ' Campaign';
                $this->musicType = Playlist::class;

                $this->fetchPlaylistTracks();
                $this->musicId = null;
            }
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to load content: ' . $e->getMessage());
            $this->showSubmitModal = false;
            $this->showCampaignsModal = true;

            Log::error('Toggle submit modal error: ' . $e->getMessage(), [
                'type' => $type,
                'id' => $id,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    public function profeature($isChecked)
    {
        $this->proFeatureEnabled = $isChecked ? false : true;
        $this->proFeatureValue = $isChecked ? 0 : 1;
    }

    public function searchSoundcloud()
    {
        if (empty($this->searchQuery)) {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab == 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                }
                if ($this->activeTab == 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
            return;
        }

        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
            $this->resolveSoundcloudUrl();
        } else {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                    ->where(function ($query) {
                        $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->get();
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
        }
    }

    public function createCampaign()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled && proUser() ? 1 : 0;
                $campaign = ModelsCampaign::create([
                    'music_id' => $this->musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'budget_credits' => $this->credit,
                    'user_urn' => user()->urn,
                    'status' => ModelsCampaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollower,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'momentum_price' => $proFeatureEnabled == 1 ? $this->credit / 2 : 0,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ]);

                CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $campaign->id,
                    'source_type' => ModelsCampaign::class,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'credits' => ($campaign->budget_credits + $campaign->momentum_price),
                    'description' => 'Spent on campaign creation',
                    'metadata' => [
                        'campaign_id' => $campaign->id,
                        'music_id' => $this->musicId,
                        'music_type' => $this->musicType,
                        'start_date' => now(),
                    ],
                    'created_id' => user()->id,
                    'creater_type' => get_class(user())
                ]);
            });

            $this->reset([
                'musicId',
                'title',
                'description',
                'playlistId',
                'playlistTracks',
                'activeTab',
                'tracks',
                'track',
                'playlists',
                'maxFollower',
                'showBudgetWarning',
                'budgetWarningMessage',
                'canSubmit',
                'commentable',
                'likeable',
                'proFeatureEnabled',
                'maxRepostLast24h',
                'maxRepostsPerDay',
                'targetGenre',
                'proFeatureEnabled',
            ]);
            $this->dispatch('alert', type: 'success', message: 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create ModelsCampaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }
    
    public function directRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->repost($requestId);

            // Refresh data after successful repost
            $this->loadDashboardData();
            $this->dispatch('alert', type: 'success', message: 'Repost request sent successfully.');
        } catch (Throwable $e) {
            Log::error("Error sending repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);

            $this->dispatch('alert', type: 'error', message: 'Failed to send repost request. Please try again.');
        }
    }

    public function declineRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->declineRepostRequest($requestId);

            // Refresh data after successful decline
            $this->loadDashboardData();

            $this->dispatch('alert', type: 'success', message: 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'Failed to decline repost request. Please try again.');
        }
    }

    // Analytics data
    public function getChartData(): array
    {
        try {
            return $this->analyticsService->getChartData(
                'last_month',
                null,
                [],
                null,
                null
            );
        } catch (\Exception $e) {
            logger()->error('Chart data loading failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
