<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\CampaignManagement\CampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

class Campaign extends Component
{
    use WithPagination;



    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    // Pagination URL parameters
    #[Url(as: 'recommendedProPage')]
    public ?int $recommendedProPage = 1;

    #[Url(as: 'recommendedPage')]
    public ?int $recommendedPage = 1;

    #[Url(as: 'allPage')]
    public ?int $allPage = 1;

    #[Url(as: 'recentPage')]
    public ?int $recentPage = 1;

    // Main tab state
    #[Url(as: 'tab', except: 'recommended_pro')]
    public string $activeMainTab = 'recommended_pro';

    // Properties for filtering and search
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public $selectedTags = [];
    public $selecteTags = [];
    public $selectedGenre = [];
    public $searchtTrackType = [];
    public $suggestedTags = [];
    public $showSuggestions = false;
    public $showSelectedTags = false;
    public $isLoading = false;
    public $selectedTrackId;
    public $selectedPlaylistId = null;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $genres = [];
    public $showTrackTypes = false;

    // Track which campaigns are currently playing
    public $playingCampaigns = [];

    // Track play start times
    public $playStartTimes = [];

    // Track total play time for each campaign
    public $playTimes = [];

    // Track which campaigns have been played for 5+ seconds
    public $playedCampaigns = [];

    // Track which campaigns have been reposted
    public $repostedCampaigns = [];

    public $playcount = false;

    // Constants
    private const ITEMS_PER_PAGE = 10;

    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];

    ############################## Campaign Creation ##########################
    // Total number of campaigns
    public $totalCampaign;
    public $totalRecommended;
    public $totalRecommendedPro;

    public $tracks = [];
    public $playlists = [];
    public $playlistTracks = [];
    public $activeTab = 'tracks';

    public $track = null;
    public $credit = 50;
    public $commentable = true;
    public $likeable = true;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 0;
    public $maxFollower = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $anyGenre = 'anyGenre';
    public $trackGenre = '';
    public $targetGenre = '';
    public $user = null;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

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

    // Properties for Delete functionality
    public $campaignToDeleteId = null;
    public $refundAmount = 0;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;

    // Confirmation Repost
    public $totalRepostPrice = 0;
    public $campaign = null;
    public $liked = false;
    public $commented = null;

    public $showSubmitModal = false;
    public $showCampaignsModal = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $showRepostConfirmationModal = false;
    ################################loadmore########################################

    // Properties for "Load More"
    public $tracksPage = 4;
    public $playlistsPage = 4;
    public $perPage = 4;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;

    ############################## Campaign Creation ##########################

    protected ?CampaignService $campaignService = null;
    protected ?TrackService $trackService = null;
    protected ?PlaylistService $playlistService = null;
    protected ?SoundCloudService $soundCloudService = null;

    public function boot(CampaignService $campaignService, TrackService $trackService, PlaylistService $playlistService, SoundCloudService $soundCloudService)
    {
        $this->campaignService = $campaignService;
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->soundCloudService = $soundCloudService;
    }

    public function mount()
    {
        $this->getAllGenres();
        $this->getAllTrackTypes();
        $this->totalCampaigns();
        // Initialize play tracking for campaigns (will be done in render method)
    }

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

    /**
     * Set the active main tab and reset pagination for that tab
     */
    public function setActiveMainTab(string $tab): void
    {
        $this->activeMainTab = $tab;

        // Reset the relevant pager when switching tabs
        match ($tab) {
            'recommended_pro' => $this->resetPage('recommendedProPage'),
            'recommended' => $this->resetPage('recommendedPage'),
            'all' => $this->resetPage('allPage'),
            default => $this->resetPage('recommendedProPage')
        };
    }

    /**
     * Get the base campaigns query with common filters
     */
    private function getCampaignsQuery(): Builder
    {
        $allowedTargetCredits = repostPrice(user(), true);

        return $this->campaignService->getCampaigns()
            ->where('budget_credits', '>=', $allowedTargetCredits)
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            });
    }

    /**
     * Apply search and filter conditions to the query
     */
    private function applyFilters(Builder $query): Builder
    {
        // Apply search filter
        if (!empty($this->search)) {
            $query->whereHas('music', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply tag filters
        if (!empty($this->selectedTags)) {
            $query->whereHas('music', function ($q) {
                $q->where(function ($tagQuery) {
                    foreach ($this->selectedTags as $tag) {
                        $tagQuery->orWhere('tag_list', 'LIKE', "%$tag%");
                    }
                });
            });
        }

        // Apply genre filter
        if (!empty($this->selectedGenre)) {
            $query->whereHas('music', function ($q) {
                $q->where('genre', $this->selectedGenre);
            });
        }

        // Apply track type filter
        if (!empty($this->searchtTrackType) && $this->searchtTrackType !== 'all') {
            $query->whereHas('music', function ($q) {
                $q->where('type', $this->searchtTrackType);
            });
        }

        return $query;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 0) {
            $this->loadTagSuggestions();
            $this->showSuggestions = true;
        } else {
            $this->suggestedTags = [];
            $this->showSuggestions = false;
        }

        // Reset pagination when search changes
        $this->resetPage($this->getActivePageName());
    }

    /**
     * Get the current active page parameter name
     */
    private function getActivePageName(): string
    {
        return match ($this->activeMainTab) {
            'recommended_pro' => 'recommendedProPage',
            'recommended' => 'recommendedPage',
            'all' => 'allPage',
            default => 'recommendedProPage'
        };
    }

    public function getAllTags()
    {
        $this->showSelectedTags = true;

        $this->suggestedTags = $this->trackService->getTracks()
            ->pluck('tag_list')
            ->flatten()
            ->map(function ($tagString) {
                return array_map('trim', explode(',', $tagString));
            })
            ->flatten()
            ->unique()
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selecteTags);
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function loadTagSuggestions()
    {
        $this->suggestedTags = $this->campaignService->getCampaigns()
            ->with('music')
            ->get()
            ->pluck('music.tag_list')
            ->flatten()
            ->map(function ($tagString) {
                return array_map('trim', explode(',', $tagString));
            })
            ->flatten()
            ->unique()
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selectedTags);
            })
            ->take(10)
            ->values()
            ->toArray();

        $this->showSuggestions = !empty($this->suggestedTags);
    }

    public function selectTag($tag)
    {
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
            $this->search = $tag;
            $this->suggestedTags = [];
            $this->showSuggestions = false;
            $this->resetPage($this->getActivePageName());
        }
    }

    public function filterByGenre($genre)
    {
        $this->selectedGenre = $genre;
        $this->resetPage($this->getActivePageName());
    }

    public function filterByTrackType($trackType)
    {
        $this->searchtTrackType = $trackType;
        $this->resetPage($this->getActivePageName());
    }

    public function removeTag($tag)
    {
        $key = array_search($tag, $this->selectedTags);
        if ($key !== false) {
            unset($this->selectedTags[$key]);
            $this->selectedTags = array_values($this->selectedTags);
            $this->resetPage($this->getActivePageName());
        }
    }

    public function hideSuggestions()
    {
        $this->showSuggestions = false;
    }

    public function getAllTrackTypes()
    {
        $this->selectedTrackTypes = $this->trackService->getTracks()
            ->where('user_urn', user()->urn)
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();
    }

    public function selectTrackType($type)
    {
        $this->selectedTrackType = $type;
        $this->resetPage($this->getActivePageName());
    }

    public function setactiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Campaign Creation Methods
     */
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

        $this->reset(['searchQuery']);
    }

    public function fetchTracks()
    {
        try {
            $this->soundCloudService->syncUserTracks(user(), []);
            $this->tracksPage = 1;
            $this->tracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->dispatch('alert', 'error', 'Failed to load tracks: ' . $e->getMessage());
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
            $this->playlistsPage = 1;
            $this->playlists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->dispatch('alert', 'error', 'Failed to load playlists: ' . $e->getMessage());
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
                $this->dispatch('alert', 'error', 'Playlist SoundCloud URN is missing.');
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
                $this->dispatch('alert', 'error', 'Failed to load playlist tracks from SoundCloud: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            $this->dispatch('alert', 'error', 'Failed to fetch playlist tracks: ' . $e->getMessage());
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
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'showCancelWarningModal',
            'campaignToDeleteId',
            'refundAmount',
            'showBudgetWarning',
            'budgetWarningMessage',
            'canSubmit'
        ]);

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
            'campaignToDeleteId',
            'refundAmount',
            'showBudgetWarning',
            'budgetWarningMessage',
            'canSubmit',
        ]);

        $this->user = User::where('urn', user()->urn)->first()->activePlan();

        if (userCredits() < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showSubmitModal = false;
            return;
        } else {
            $this->showLowCreditWarningModal = false;
        }

        $this->showSubmitModal = true;
        $this->getAllGenres();

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
            $this->dispatch('alert', 'error', 'Failed to load content: ' . $e->getMessage());
            $this->showSubmitModal = false;
            $this->showCampaignsModal = true;

            Log::error('Toggle submit modal error: ' . $e->getMessage(), [
                'type' => $type,
                'id' => $id,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    public function getAllGenres()
    {
        $this->genres = User::where('urn', user()->urn)->first()->genres()->pluck('genre')->toArray();
    }

    public function profeature($isChecked)
    {
        $this->proFeatureEnabled = $isChecked ? false : true;
        $this->proFeatureValue = $isChecked ? 0 : 1;
    }

    public function createCampaign()
    {
        $this->validate();

        try {
            if ($this->anyGenre == 'anyGenre') {
                $this->targetGenre = $this->anyGenre;
            }
            if ($this->trackGenre == 'trackGenre') {
                $this->targetGenre = $this->trackGenre;
            }

            DB::transaction(function () {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled && $this->user->status == User::STATUS_ACTIVE ? 1 : 0;
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
                'anyGenre',
                'trackGenre',
                'proFeatureEnabled',
            ]);
            $this->dispatch('alert', 'success', 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            $this->dispatch('alert', 'error', 'Failed to create campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }

    /**
     * Audio Player Event Handlers
     */
    public function handleAudioPlay($campaignId)
    {
        $this->playingCampaigns[$campaignId] = true;
        $this->playStartTimes[$campaignId] = now()->timestamp;
    }

    public function handleAudioPause($campaignId)
    {
        $this->updatePlayTime($campaignId);
        unset($this->playingCampaigns[$campaignId]);
        unset($this->playStartTimes[$campaignId]);
    }

    public function handleAudioTimeUpdate($campaignId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('campaignPlayedEnough', $campaignId);
        }
    }

    public function handleAudioEnded($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    private function updatePlayTime($campaignId)
    {
        if (isset($this->playStartTimes[$campaignId])) {
            $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
            $this->playTimes[$campaignId] = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

            if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                $this->playedCampaigns[] = $campaignId;
                $this->dispatch('campaignPlayedEnough', $campaignId);
            }
        }
    }

    public function updatePlayingTimes()
    {
        foreach ($this->playingCampaigns as $campaignId => $isPlaying) {
            if ($isPlaying && isset($this->playStartTimes[$campaignId])) {
                $playDuration = now()->timestamp - $this->playStartTimes[$campaignId];
                $totalPlayTime = ($this->playTimes[$campaignId] ?? 0) + $playDuration;

                if ($totalPlayTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
                    $this->playedCampaigns[] = $campaignId;
                    $this->dispatch('campaignPlayedEnough', $campaignId);
                }
            }
        }
    }

    public function startPlaying($campaignId)
    {
        $this->handleAudioPlay($campaignId);
    }

    public function stopPlaying($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    public function simulateAudioProgress($campaignId, $seconds = 1)
    {
        if (!isset($this->playTimes[$campaignId])) {
            $this->playTimes[$campaignId] = 0;
        }

        $this->playTimes[$campaignId] += $seconds;

        if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('alert', 'success', 'Campaign marked as played for 5+ seconds!');
        }
    }

    public function canRepost($campaignId): bool
    {
        $canRepost = in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);

        if ($canRepost && !$this->playcount) {
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId));
            $campaign->increment('playback_count');
            $this->playcount = true;
        }
        return $canRepost;
    }

    public function isPlaying($campaignId): bool
    {
        return isset($this->playingCampaigns[$campaignId]) && $this->playingCampaigns[$campaignId] === true;
    }

    public function getPlayTime($campaignId): int
    {
        $baseTime = $this->playTimes[$campaignId] ?? 0;

        if ($this->isPlaying($campaignId) && isset($this->playStartTimes[$campaignId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$campaignId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    public function getRemainingTime($campaignId): int
    {
        $playTime = $this->getPlayTime($campaignId);
        return max(0, 5 - $playTime);
    }
    public function confirmRepost($campaignId)
    {
        $this->showRepostConfirmationModal = true;
        $this->campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');
    }

    public function repost($campaignId)
    {
        $this->soundCloudService->ensureSoundCloudConnection(user());
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        try {
            if (!$this->canRepost($campaignId)) {
                $this->dispatch('alert', 'error', 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
                return;
            }

            $currentUserUrn = user()->urn;

            if ($this->campaignService->getCampaigns()->where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                $this->dispatch('alert', 'error', 'You cannot repost your own campaign.');
                return;
            }

            if (Repost::where('reposter_urn', $currentUserUrn)->where('campaign_id', $campaignId)->exists()) {
                $this->dispatch('alert', 'error', 'You have already reposted this campaign.');
                return;
            }

            $campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');

            if (!$campaign->music) {
                $this->dispatch('alert', 'error', 'Track or Playlist not found for this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);
            $commentSoundcloud = [
                'comment' => [
                    'body' => $this->commented,
                    'timestamp' => time()
                ]
            ];


            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    $response = $httpClient->post("{$this->baseUrl}/tracks/{$campaign->music->urn}/comments", $commentSoundcloud);
                    if ($this->liked) {
                        $response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    }
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    $response = $httpClient->post("{$this->baseUrl}/playlists/{$campaign->music->urn}/comments", $commentSoundcloud);
                    break;
                default:
                    $this->dispatch('alert', 'error', 'Invalid music type specified for the campaign.');
                    return;
            }
            $data = [
                'likeable' => $this->liked,
                'commentable' => $this->commented
            ];
            if ($response->successful()) {
                $soundcloudRepostId = $response->json('id');
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);
                $this->dispatch('alert', 'success', 'Campaign music reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                $this->dispatch('alert', 'error', 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $campaignId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', 'error', 'An unexpected error occurred. Please try again later.');
            return;
        }
    }

    // Search and SoundCloud integration methods
    public $searchQuery = '';
    public $allTracks;
    public $users;
    public $allPlaylists;
    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;
    public $allPlaylistTracks;
    public $userinfo;
    public $trackLimit = 4;
    private $soundcloudClientId = 'YOUR_SOUNDCLOUD_CLIENT_ID';
    private $soundcloudApiUrl = 'https://api-v2.soundcloud.com';
    public $playListTrackShow = false;

    public function updatedactiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->searchQuery = '';
        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->fetchPlaylists();
        }
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

    protected function resolveSoundcloudUrl()
    {
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('permalink_url', $this->searchQuery)
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->get();
                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeTab == 'playlists') {
                $playlistsFromDb = Playlist::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
            }
        }

        $response = Http::get("{$this->soundcloudApiUrl}/resolve", [
            'url' => $this->searchQuery,
            'client_id' => $this->soundcloudClientId,
        ]);

        if ($response->successful()) {
            $resolvedData = $response->json();
            $this->processResolvedData($resolvedData);
        } else {
            if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
                $this->allPlaylistTracks = collect();
                $this->tracks = collect();
            } else {
                if ($this->activeTab === 'tracks') {
                    $this->allTracks = collect();
                    $this->tracks = collect();
                } elseif ($this->activeTab === 'playlists') {
                    $this->allPlaylists = collect();
                    $this->playlists = collect();
                }
            }
            $this->dispatch('alert', 'error', 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processResolvedData($data)
    {
        switch ($data['kind']) {
            case 'track':
                $this->activeTab = 'tracks';
                if ($this->playListTrackShow && $this->selectedPlaylistId) {
                    $playlistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                    $this->allPlaylistTracks = $playlistTracks->filter(function ($track) use ($data) {
                        return $track->urn === $data['urn'];
                    });
                    $this->tracks = $this->allPlaylistTracks->take($this->trackLimit);
                    $this->hasMoreTracks = false;
                } else {
                    $this->allTracks = collect([$data]);
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = false;
                }
                break;
            case 'user':
                $this->activeTab = 'tracks';
                $this->fetchUserTracks($data['id']);
                break;
            default:
                $this->allTracks = collect();
                $this->tracks = collect();
                $this->dispatch('alert', 'error', 'The provided URL is not a track or playlist.');
                break;
        }
    }

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);
        if ($playlist) {
            $this->allTracks = $playlist->tracks;
            $this->tracks = $this->allTracks->take($this->trackLimit);
            $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
        } else {
            $this->tracks = collect();
            $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
        }
        $this->activeTab = 'tracks';
        $this->playListTrackShow = true;

        $this->reset([
            'searchQuery',
        ]);
    }

    public function openRepostsModal($trackId)
    {
        $this->toggleSubmitModal('track', $trackId);
    }

    /**
     * Initialize play times for campaigns
     */
    // private function initializePlayTimes($campaigns)
    // {
    //     foreach ($campaigns as $campaign) {
    //         if (!isset($this->playTimes[$campaign->id])) {
    //             $this->playTimes[$campaign->id] = 0;
    //         }
    //     }
    // }

    public function totalCampaigns()
    {
        $this->totalCampaign = $this->getCampaignsQuery()->count();
        $this->totalRecommended = $this->getCampaignsQuery()->featured()->count();
        $this->totalRecommendedPro = $this->getCampaignsQuery()->proFeatured()->count();
    }

    /**
     * Main render method with optimized data loading and pagination
     */
    public function render()
    {
        try {
            // Get base query
            $baseQuery = $this->getCampaignsQuery();

            // Apply filters to the query
            $baseQuery = $this->applyFilters($baseQuery);
            $campaigns = collect();
            // dd($baseQuery->get());
            // $filteredQuery = $this->applyFilters(clone $baseQuery);

            // Initialize campaigns variables
            // $featuredCampaigns = collect();
            // $campaigns = null;
            // Get campaigns based on active tab with pagination
            switch ($this->activeMainTab) {
                case 'recommended_pro':

                    // Get featured campaigns (no pagination for featured)


                    // Get regular campaigns with pagination
                    // $campaigns = $filteredQuery
                    //     ->NotFeatured()
                    //     ->latest()
                    //     ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedProPage', $this->recommendedProPage);


                    $campaigns = $baseQuery->proFeatured()
                        ->latest()
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedProPage', $this->recommendedProPage);
                    break;

                case 'recommended':

                    $campaigns = $baseQuery->featured()
                        ->latest()
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedPage', $this->recommendedPage);
                    break;

                case 'all':
                    $campaigns = $baseQuery->latest()
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'allPage', $this->allPage);
                    break;
                default:
                    $campaigns = $baseQuery->proFeatured()
                        ->latest()
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedProPage', $this->recommendedProPage);
                    break;
            }

            return view('backend.user.campaign_management.campaign', [
                // 'featuredCampaigns' => $featuredCampaigns,
                'campaigns' => $campaigns
            ]);
        } catch (\Exception $e) {
            // Handle errors gracefully
            Log::error('Failed to load campaigns: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'unknown',
                'active_tab' => $this->activeMainTab,
                'exception' => $e
            ]);
            $campaigns = collect();


            return view('backend.user.campaign_management.campaign', [
                'campaigns' => $campaigns
            ]);
        }
    }
}
