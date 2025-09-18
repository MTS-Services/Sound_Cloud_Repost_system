<?php

namespace App\Livewire\User\CampaignManagement;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Feature;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Services\PlaylistService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PlaylistTrack;
use App\Models\UserAnalytics;

class Campaign extends Component
{
    use WithPagination;

    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    // Pagination URL parameters
    #[Url(as: 'recommended_proPage')]
    public ?int $recommended_proPage = 1;

    #[Url(as: 'recommendedPage')]
    public ?int $recommendedPage = 1;

    #[Url(as: 'allPage')]
    public ?int $allPage = 1;

    #[Url(as: 'recentPage')]
    public ?int $recentPage = 1;

    // Main tab state
    #[Url(as: 'tab', except: '')]
    public string $activeMainTab = 'recommended_pro';

    // Properties for filtering and search
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public $selectedTags = [];
    public $selecteTags = [];
    public $selectedGenre = [];
    public $searchMusicType = 'all';
    public $suggestedTags = [];
    public $showSuggestions = false;
    public $showSelectedTags = false;
    public $isLoading = false;
    public $selectedTrackId;
    public $selectedPlaylistId = null;
    public $maxFollowerEnabled = false;
    public $repostPerDayEnabled = false;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $selectedGenres = [];
    public $showTrackTypes = false;

    //=========================================
    //  Music Play Control:
    //=========================================
    // Track which campaigns are currently playing
    public $playingCampaigns = [];

    // Track play start times
    public $playStartTimes = [];

    // Track total play time for each campaign
    public $playTimes = [];

    // Track which campaigns have been played for 5+ seconds
    public $playedCampaigns = [];
    public $playcount = false;
    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded',
    ];








    // Track which campaigns have been reposted
    public $repostedCampaigns = [];



    // Constants
    private const ITEMS_PER_PAGE = 10;



    protected $queryString = [
        'selectedGenres',
        'recommended_proPage' => ['except' => 1],
        'recommendedPage' => ['except' => 1],
        'allPage' => ['except' => 1],
    ];

    ############################## Campaign Creation ##########################
    // Total number of campaigns
    public $totalCampaign;
    public $totalRecommended;
    public $totalRecommendedPro;

    public $tracks = null;
    public $playlists = null;
    public $playlistTracks = [];
    public $activeTab = 'tracks';

    public $track = null;
    public $credit = 50;
    public $totalCredit = 50;
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
    public $showOptions = false;

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
    public $followed = true;

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
    protected ?AnalyticsService $analyticsService = null;
    protected ?MyCampaignService $myCampaignService = null;

    public function boot(CampaignService $campaignService, TrackService $trackService, PlaylistService $playlistService, SoundCloudService $soundCloudService, AnalyticsService $analyticsService, MyCampaignService $myCampaignService)
    {
        $this->campaignService = $campaignService;
        $this->trackService = $trackService;
        $this->playlistService = $playlistService;
        $this->soundCloudService = $soundCloudService;
        $this->analyticsService = $analyticsService;
        $this->myCampaignService = $myCampaignService;
    }

    public function mount()
    {
        $this->getAllTrackTypes();
        $this->totalCampaigns();
        $this->calculateFollowersLimit();
        if ($this->activeMainTab === 'all') {
            $this->selectedGenres = !empty($this->selectedGenres) ? $this->selectedGenres : [];
        } else {
            $this->selectedGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
        }



        // $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
    }
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
        }
        if (in_array($propertyName, ['activeMainTab', 'campaigns'])) {
            $this->dispatch('soundcloud-widgets-reinitialize');
        }
    }
    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
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
        $this->reset();
        $this->activeMainTab = $tab;

        switch ($tab) {
            case 'recommended_pro':
                $this->resetPage('recommended_proPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
                break;

            case 'recommended':
                $this->resetPage('recommendedPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
                break;

            case 'all':
                $this->resetPage('allPage');
                $this->{$tab . 'Page'} = 1;
                $this->selectedGenres = [];
                break;

            default:
                $this->resetPage('recommended_proPage');
                $this->selectedGenres = user()->genres->pluck('genre')->toArray() ?? [];
        }

        $this->totalCampaigns();
        $this->dispatch('soundcloud-widgets-reinitialize');
    }


    /**
     * Get the base campaigns query with common filters
     */
    private function getCampaignsQuery(): Builder
    {
        $allowedTargetCredits = repostPrice(user(), true);
        return ModelsCampaign::where('budget_credits', '>=', $allowedTargetCredits)
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            })
            ->orderByRaw('CASE
            WHEN boosted_at >= ? THEN 0
            WHEN featured_at >= ? THEN 1
            ELSE 2
        END', [now()->subMinutes(15), now()->subHours(24)])
            ->orderBy('created_at', 'desc');
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
        if (!empty($this->selectedTags) && $this->selectedTags != 'all') {
            $query->whereHas('music', function ($q) {
                $q->where(function ($tagQuery) {
                    foreach ($this->selectedTags as $tag) {
                        $tagQuery->orWhere('tag_list', 'LIKE', "%$tag%");
                    }
                });
            });
        }

        if ($this->activeMainTab != 'all') {
            $this->selectedGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
        }
        if (!empty($this->selectedGenres)) {
            $query->whereHas('music', function ($q) {
                $q->whereIn('genre', $this->selectedGenres);
            });
        }



        if (!empty($this->searchMusicType) && $this->searchMusicType != 'all') {
            $query->where('music_type', 'like', "%{$this->searchMusicType}%");
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
            'recommended_pro' => 'recommended_proPage',
            'recommended' => 'recommendedPage',
            'all' => 'allPage',
            default => 'recommended_proPage'
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
    public function toggleGenre($genre)
    {
        $tab = $this->activeMainTab;
        $this->{$tab . 'Page'} = 1;
        if (in_array($genre, $this->selectedGenres)) {
            $this->selectedGenres = array_diff($this->selectedGenres, [$genre]);
        } else {
            $this->selectedGenres[] = $genre;
        }
        $this->totalCampaigns();
    }

    public function filterByTrackType($Type)
    {
        $this->searchMusicType = $Type;

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
            // $this->soundCloudService->syncSelfTracks([]);

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
            // $this->soundCloudService-> ();

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
        $this->reset();

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
        if (!proUser()) {
            return $this->dispatch('alert', type: 'error', message: 'You need to be a pro user to use this feature');;
        } elseif (($this->credit * 2) > userCredits()) {
            $this->proFeatureEnabled = $isChecked ? true : false;
            $this->proFeatureValue = $isChecked ? 1 : 0;
        } else {
            $this->proFeatureEnabled = $isChecked ? false : true;
            $this->proFeatureValue = $isChecked ? 0 : 1;
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
                    'max_followers' => $this->maxFollowerEnabled ? $this->maxFollower : 100,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'momentum_price' => $proFeatureEnabled == 1 ? $this->credit / 2 : 0,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->repostPerDayEnabled ? $this->maxRepostsPerDay : 0,
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


            $this->dispatch('alert', type: 'success', message: 'Campaign created successfully!');

            // $this->dispatch('campaignCreated');

            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->reset();
            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }

    /**========================================
     * Audio Player Event Handlers
     =========================================*/
    public function handleAudioPlay($campaignId)
    {
        // Log::info('handleAudioPlay Campaign ID: ' . $campaignId);
        $this->playingCampaigns[$campaignId] = true;
        $this->playStartTimes[$campaignId] = now()->timestamp;
    }

    public function handleAudioPause($campaignId)
    {
        // Log::info('handleAudioPlay Campaign ID: ' . $campaignId);
        $this->updatePlayTime($campaignId);
        unset($this->playingCampaigns[$campaignId]);
        unset($this->playStartTimes[$campaignId]);
    }

    public function handleAudioTimeUpdate($campaignId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            // $this->dispatch('campaignPlayedEnough', $campaignId);
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
                // $this->dispatch('campaignPlayedEnough', $campaignId);
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
                    // $this->dispatch('campaignPlayedEnough', $campaignId);
                }
            }
        }
    }

    public function startPlaying($campaignId)
    {
        // Log::info('startPlaying campaignId: ' . $campaignId);
        // $this->reset([
        //     'playcount',
        //     'playedCampaigns',
        //     'repostedCampaigns',
        //     'campaign',
        //     'showRepostConfirmationModal',
        //     'commented',
        //     'liked',
        //     'followed',
        // ]);
        $this->reset();
        $this->handleAudioPlay($campaignId);
    }

    public function stopPlaying($campaignId)
    {
        // Log::info('stopPlaying campaignId: ' . $campaignId);
        $this->handleAudioPause($campaignId);
    }

    public function simulateAudioProgress($campaignId, $seconds = 1)
    {
        // Log::info('simulateAudioProgress campaignId: ' . $campaignId);
        if (!isset($this->playTimes[$campaignId])) {
            $this->playTimes[$campaignId] = 0;
        }

        $this->playTimes[$campaignId] += $seconds;

        if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('alert', type: 'success', message: 'Campaign marked as played for 5+ seconds!');
        }
    }

    public function canRepost($campaignId): bool
    {
        // Log::info('canRepost campaignId: ' . $campaignId);
        $canRepost = in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);

        if ($canRepost && !$this->playcount) {
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId));

            if ($campaign->music_type == Track::class) {
                $this->reset('track');
                $this->track = $this->trackService->getTrack(encrypt($campaign->music_id));
            } elseif ($campaign->music_type == Playlist::class) {
                $this->reset('track');
                $playlist = PlaylistTrack::where('playlist_urn', $campaign->music_id)->with('track')->get();
                $this->track = $playlist->track;
            }

            $response = $this->analyticsService->recordAnalytics($this->track, $campaign, UserAnalytics::TYPE_PLAY, $campaign->target_genre);
            Log:
            info('response: analytics: ');
            if ($response != false || $response != null) {


                $campaign->increment('playback_count');
            }

            $this->playcount = true;
            // $this->reset([
            //     'playcount',
            //     'playedCampaigns',
            //     'repostedCampaigns',
            //     'campaign',
            //     'showRepostConfirmationModal',
            //     'commented',
            //     'liked',
            //     'followed',
            // ]);
        }
        return $canRepost;
    }

    public function isPlaying($campaignId): bool
    {
        // Log::info('isPlaying campaignId: ' . $campaignId);
        return isset($this->playingCampaigns[$campaignId]) && $this->playingCampaigns[$campaignId] === true;
    }

    public function getPlayTime($campaignId): int
    {
        // Log::info('getPlayTime campaignId: ' . $campaignId);
        $baseTime = $this->playTimes[$campaignId] ?? 0;

        if ($this->isPlaying($campaignId) && isset($this->playStartTimes[$campaignId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$campaignId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    public function getRemainingTime($campaignId): int
    {
        // Log::info('getRemainingTime campaignId: ' . $campaignId);
        $playTime = $this->getPlayTime($campaignId);
        return max(0, 5 - $playTime);
    }
    public function confirmRepost($campaignId)
    {
        if (!$this->canRepost($campaignId)) {
            $this->dispatch('alert', type: 'error', message: 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
            return;
        }
        // Log::info('confirmRepost campaignId: ' . $campaignId);
        $this->showRepostConfirmationModal = true;
        $this->campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');
        // Log::info($this->campaign);
    }

    public function repost($campaignId)
    {
        $this->soundCloudService->ensureSoundCloudConnection(user());
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        try {
            if (!$this->canRepost($campaignId)) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
                return;
            }

            $currentUserUrn = user()->urn;

            if ($this->campaignService->getCampaigns()->where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You cannot repost your own campaign.');
                return;
            }

            if (Repost::where('reposter_urn', $currentUserUrn)->where('campaign_id', $campaignId)->exists()) {
                $this->dispatch('alert', type: 'error', message: 'You have already reposted this campaign.');
                return;
            }

            $campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');

            if (!$campaign->music) {
                $this->dispatch('alert', type: 'error', message: 'Track or Playlist not found for this campaign.');
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

            $response = null;
            $like_response = null;
            $comment_response = null;
            $follow_response = null;

            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    if ($this->commented) {
                        $comment_response = $httpClient->post("{$this->baseUrl}/tracks/{$campaign->music->urn}/comments", $commentSoundcloud);
                    }
                    if ($this->liked) {
                        $like_response = $httpClient->post("{$this->baseUrl}/likes/tracks/{$campaign->music->urn}");
                    }
                    if ($this->followed) {
                        $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->user?->urn}");
                    }
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    if ($this->liked) {
                        $like_response = $httpClient->post("{$this->baseUrl}/likes/playlists/{$campaign->music->urn}");
                    }
                    if ($this->commented) {
                        $comment_response = $httpClient->post("{$this->baseUrl}/playlists/{$campaign->music->urn}/comments", $commentSoundcloud);
                    }
                    if ($this->followed) {
                        $follow_response = $httpClient->put("{$this->baseUrl}/me/followings/{$campaign->user?->urn}");
                    }
                    break;
                default:
                    $this->dispatch('alert', type: 'error', message: 'Invalid music type specified for the campaign.');
                    return;
            }
            $data = [
                'likeable' => $like_response ? $this->liked : false,
                'comment' => $comment_response ? $this->commented : false,
                'follow' => $follow_response ? $this->followed : false
            ];
            if ($response->successful()) {
                $repostEmailPermission = hasEmailSentPermission('em_repost_accepted', $campaign->user->urn);
                if ($repostEmailPermission) {
                    $datas = [
                        [
                            'email' => $campaign->user->email,
                            'subject' => 'Repost Notification',
                            'title' => 'Dear ' . $campaign->user->name,
                            'body' => 'Your ' . $campaign->title . 'campaign has been reposted successfully.',
                        ],
                    ];
                    NotificationMailSent::dispatch($datas);
                }
                $soundcloudRepostId = $campaign->music->soundcloud_track_id;
                $this->campaignService->syncReposts($campaign, user(), $soundcloudRepostId, $data);
                $this->dispatch('alert', type: 'success', message: 'Campaign music reposted successfully.');
            } else {
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                $this->dispatch('alert', type: 'error', message: 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $campaignId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', type: 'error', message: 'An unexpected error occurred. Please try again later.');
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

        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $this->searchQuery)) {
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
                $tracksFromDb = Track::where('permalink_url', $this->searchQuery)
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
                $playlistsFromDb = Playlist::where('permalink_url', $this->searchQuery)
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
        if (proUser()) {
            $response = Http::withToken(user()->token)->get("https://api.soundcloud.com/resolve?url=" . $this->searchQuery);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Please upgrade to a Pro User to use this feature.');
        }
        
        if ($response->successful()) {
            if ($this->activeTab === 'playlists') {
                $resolvedPlaylists = $response->json();
                if (isset($resolvedPlaylists['tracks']) && count($resolvedPlaylists['tracks']) > 0) {
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                    $this->soundCloudService->unknownPlaylistAdd($resolvedPlaylists);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
                }
            } else {
                $resolvedTrack = $response->json();
                $this->soundCloudService->unknownTrackAdd($resolvedTrack);
            }
            $this->processSearchData();
            Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
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
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processSearchData()
    {
        if ($this->playListTrackShow == true && $this->activeTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                ->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeTab == 'tracks') {
                $tracksFromDb = Track::where('permalink_url', 'like', '%' . $this->searchQuery . '%')
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
                $playlistsFromDb = Playlist::where('permalink_url', 'like', '%' . $this->searchQuery . '%')
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
    public function totalCampaigns()
    {
        if ($this->activeMainTab === 'all') {
            $this->totalCampaign = $this->getCampaignsQuery()
                ->whereHas('music', function ($query) {
                    if (!empty($this->selectedGenres)) {
                        $query->whereIn('genre', $this->selectedGenres);
                    }
                })->count();
        } else {
            $this->totalCampaign = $this->getCampaignsQuery()->count();
        }

        if ($this->activeMainTab === 'recommended_pro') {
            $this->totalRecommendedPro = $this->getCampaignsQuery()
                ->whereHas('user', function ($query) {
                    $query->isPro();
                })
                ->whereHas('music', function ($query) {
                    $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                    $query->whereIn('genre', $userGenres);
                })->count();
        } else {
            $this->totalRecommendedPro = $this->getCampaignsQuery()
                ->whereHas('user', function ($query) {
                    $query->isPro();
                })
                ->whereHas('music', function ($query) {
                    $userGenres = user()->genres->pluck('genre')->toArray() ?? [];
                    $query->whereIn('genre', $userGenres);
                })->count();
        }

        if ($this->activeMainTab === 'recommended') {
            $this->totalRecommended = $this->getCampaignsQuery()
                ->whereHas('music', function ($query) {
                    $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                    $query->whereIn('genre', $userGenres);
                })->count();
        } else {
            $this->totalRecommended = $this->getCampaignsQuery()
                ->whereHas('music', function ($query) {
                    $userGenres = user()->genres->pluck('genre')->toArray() ?? [];
                    $query->whereIn('genre', $userGenres);
                })->count();
        }
    }

    /**
     * Main render method with optimized data loading and pagination
     */
    public function render()
    {
        try {
            $baseQuery = $this->getCampaignsQuery();
            $baseQuery = $this->applyFilters($baseQuery);
            $campaigns = collect();
            switch ($this->activeMainTab) {
                case 'recommended_pro':
                    $campaigns = $baseQuery
                        ->whereHas('user', function ($query) {
                            $query->isPro();
                        })
                        ->whereHas('music', function ($query) {
                            $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                            $query->whereIn('genre', $userGenres);
                        })
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommended_proPage', $this->recommended_proPage);

                    break;

                case 'recommended':
                    $campaigns = $baseQuery
                        ->whereHas('music', function ($query) {
                            $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                            $query->whereIn('genre', $userGenres);
                        })
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommendedPage', $this->recommendedPage);
                    break;

                case 'all':
                    $campaigns = $baseQuery
                        ->whereHas('music', function ($query) {
                            if (!empty($this->selectedGenres)) {
                                $query->whereIn('genre', $this->selectedGenres);
                            }
                        })
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'allPage', $this->allPage);

                    break;
                default:
                    $campaigns = $baseQuery
                        ->whereHas('user', function ($query) {
                            $query->isPro();
                        })
                        ->whereHas('music', function ($query) {
                            $userGenres = !empty($this->selectedGenres) ? $this->selectedGenres : user()->genres->pluck('genre')->toArray();
                            $query->whereIn('genre', $userGenres);
                        })
                        ->paginate(self::ITEMS_PER_PAGE, ['*'], 'recommended_proPage', $this->recommended_proPage);

                    break;
            }

            return view('livewire.user.campaign-management.campaign', [
                'campaigns' => $campaigns
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load campaigns: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'unknown',
                'active_tab' => $this->activeMainTab,
                'exception' => $e
            ]);
            $campaigns = collect();
            return view('livewire.user.campaign-management.campaign', [
                'campaigns' => $campaigns
            ]);
        }
    }
}
