<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign as ModelsCampaign;
use App\Models\CreditTransaction;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\TrackService;
use App\Services\User\CampaignManagement\CampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

class Campaign extends Component
{
    protected ?CampaignService $campaignService = null;
    protected ?TrackService $trackService = null;
    public $featuredCampaigns;
    public $campaigns;
    #[Locked]
    protected string $baseUrl = 'https://api.soundcloud.com';

    // Properties for filtering and search
    public $search = '';
    public $selectedTags = [];
    public $selecteTags = [];
    public $suggestedTags = [];
    public $showSuggestions = false;
    public $showSelectedTags = false;
    public $isLoading = false;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $genres = [];
    public $activeMainTab = 'recommended_pro';

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

    // Listeners for browser events
    protected $listeners = [
        'audioPlay' => 'handleAudioPlay',
        'audioPause' => 'handleAudioPause',
        'audioTimeUpdate' => 'handleAudioTimeUpdate',
        'audioEnded' => 'handleAudioEnded'
    ];
    ############################## Campaign Creation ##########################
    public $tracks = [];
    public $playlists = [];
    public $playlistTracks = [];
    public $activeModalTab = 'tracks';



    public $track = null;
    public $credit = 100;
    public $commentable = false;
    public $likeable = false;
    public $proFeatureEnabled = false;
    public $maxFollower = null;
    public $maxRepostLast24h = null;
    public $maxRepostsPerDay = null;
    public $anyGenre = '';
    public $trackGenre = '';
    public $targetGenre = '';

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
    public $editOriginalBudget = null; // Track original budget to prevent decreases

    // Properties for Delete functionality
    public $campaignToDeleteId = null;
    public $refundAmount = 0;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;

    public $showSubmitModal = false;
    public $showCampaignsModal = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    ################################loadmore########################################

    // Properties for "Load More"
    public $tracksPage = 1;
    public $playlistsPage = 1;
    public $perPage = 4; // Number of items to load per page
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;
    ############################## Campaign Creation ##########################
    public function boot(CampaignService $campaignService, TrackService $trackService)
    {
        $this->campaignService = $campaignService;
        $this->trackService = $trackService;
    }


    public function mount()
    {
        $this->loadData();
        $this->loadInitialData();
    }
    protected function rules()
    {
        $rules = [
            'credit' => 'required|integer|min:100',
        ];
        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'credit.required' => 'Minimum credit is 100.',
            'maxFollower.required' => 'Max follower is required.',
        ];
    }
    public function updatedSearch()
    {
        if (strlen($this->search) >= 0) {
            $this->selectedTags = [];
            $this->suggestedTags = [];
            $this->loadTagSuggestions();
            $this->showSuggestions = true;
        } else {
            $this->suggestedTags = [];
            $this->showSuggestions = false;
        }
        $this->loadData();
    }
    public function getAllTags()
    {
        $this->showSelectedTags = true;

        // Get all tag_list values from tracks
        $this->suggestedTags = $this->trackService->getTracks()
            ->pluck('tag_list') // get all tag_list values
            ->flatten()
            ->map(function ($tagString) {
                // Split comma-separated strings into arrays
                return array_map('trim', explode(',', $tagString));
            })
            ->flatten() // flatten the resulting array of arrays
            ->unique() // remove duplicates
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selecteTags);
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function loadTagSuggestions()
    {
        // Get unique tags from tracks table that match the search query
        $this->suggestedTags = $this->campaignService->getCampaigns()
            ->with('music')
            ->get()
            ->pluck('music.tag_list')
            ->flatten()
            ->map(function ($tagString) {
                // Split comma-separated strings into arrays
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
        // If no tags found, set showSuggestions to false
        if (empty($this->suggestedTags)) {
            $this->showSuggestions = false;
        } else {
            $this->showSuggestions = true;
        }
    }

    public function selectTag($tag)
    {
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
            $this->search = $tag; // Set search to the selected tag
            $this->suggestedTags = [];
            $this->showSuggestions = false;
            $this->searchByTags();
        }
    }
    public function removeTag($tag)
    {
        unset($this->selecteTags[$tag]);
        $this->selecteTags = array_values($this->selecteTags);
        // $this->searchByTags();
    }
    public function hideSuggestions()
    {
        $this->showSuggestions = false;
    }
    public function searchByTags()
    {
        $this->isLoading = true;

        if (empty($this->selectedTags)) {
            $this->loadInitialData();
        } else {
            $this->featuredCampaigns = $this->campaignService->getCampaigns()
                ->where('cost_per_repost', repostPrice(user()))
                ->featured()
                ->withoutSelf()
                ->with(['music.user.userInfo', 'reposts'])
                ->whereDoesntHave('reposts', function ($query) {
                    $query->where('reposter_urn', user()->urn);
                })
                ->whereHas('music', function ($query) {
                    $query->where(function ($q) {
                        foreach ($this->selectedTags as $tag) {
                            $q->orWhere('tag_list', 'LIKE', "%$tag%");
                        }
                    });
                })
                ->get();

            $this->campaigns = $this->campaignService->getCampaigns()
                ->where('cost_per_repost', repostPrice(user()))
                ->notFeatured()
                ->withoutSelf()
                ->with(['music.user.userInfo', 'reposts'])
                ->whereDoesntHave('reposts', function ($query) {
                    $query->where('reposter_urn', user()->urn);
                })
                ->whereHas('music', function ($query) {
                    $query->where(function ($q) {
                        foreach ($this->selectedTags as $tag) {
                            $q->orWhere('tag_list', 'LIKE', "%$tag%");
                        }
                    });
                })
                ->get();
        }

        $this->isLoading = false;
        $this->selectedTags = [];
    }

    public function loadInitialData()
    {

        $allowed_target_credits = repostPrice(user());
        $this->featuredCampaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', $allowed_target_credits)
            ->featured()
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            })
            ->get();

        $this->campaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', $allowed_target_credits)
            ->notFeatured()
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            })
            ->get();

        // Initialize tracking arrays
        foreach ($this->featuredCampaigns as $campaign) {
            $this->playTimes[$campaign->id] = 0;
        }

        foreach ($this->campaigns as $campaign) {
            $this->playTimes[$campaign->id] = 0;
        }
    }

    public function searchTags($tags)
    {
        $this->selectedTags = $tags;
        $this->featuredCampaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', repostPrice(user()))
            ->featured()
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            })
            ->filterByTags($this->selectedTags)
            ->get();

        $this->campaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', repostPrice(user()))
            ->notFeatured()
            ->withoutSelf()
            ->with(['music.user.userInfo', 'reposts'])
            ->whereDoesntHave('reposts', function ($query) {
                $query->where('reposter_urn', user()->urn);
            })
            ->filterByTags($this->selectedTags)
            ->get();
    }
    // public function hideSuggestions()
    // {
    //     // Delay hiding to allow click events on suggestions
    //     $this->dispatchBrowserEvent('hide-suggestions-delayed');
    // }

    /**
     * ###########################################
     * ******* Start Tabs Events ********
     * ###########################################
     */
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
        $this->loadInitialData();
    }
    public function setactiveModalTab($tab)
    {
        $this->activeMainTab = $tab;
    }

    /**
     * ###########################################
     * ******* End Tabs Events ********
     * ###########################################
     */
    /**
     * ###########################################
     * ******* Start Campaign Create Events ********
     * ###########################################
     */
    public function selectModalTab($tab = 'tracks')
    {
        $this->activeModalTab = $tab;

        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->fetchPlaylists();
        }
    }
    public function fetchTracks()
    {
        try {
            $this->tracksPage = 1; // Reset page on initial fetch
            $this->tracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMoreTracks = $this->tracks->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            session()->flash('error', 'Failed to load tracks: ' . $e->getMessage());
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
            $this->playlistsPage = 1; // Reset page on initial fetch
            $this->playlists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->take($this->perPage)
                ->get();
            $this->hasMorePlaylists = $this->playlists->count() === $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            session()->flash('error', 'Failed to load playlists: ' . $e->getMessage());
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
                session()->flash('error', 'Playlist SoundCloud URN is missing.');
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
                session()->flash('error', 'Failed to load playlist tracks from SoundCloud: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            session()->flash('error', 'Failed to fetch playlist tracks: ' . $e->getMessage());
            Log::error('Playlist tracks fetch error: ' . $e->getMessage(), [
                'playlist_id' => $this->playlistId,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }
    public function toggleCampaignsModal()
    {
        // Reset all form data and validation
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

        // Set default values
        $this->activeModalTab = 'tracks';
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

        // Reset form fields
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
            session()->flash('error', 'Failed to load content: ' . $e->getMessage());
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
        $this->genres = $this->trackService->getTracks()->where('user_urn', user()->urn)->pluck('genre')->unique()->values()->toArray();
    }
    public function createCampaign()
    {
        $this->validate();

        try {
            $totalBudget = $this->credit;

            DB::transaction(function () use ($totalBudget) {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled ? 1 : 0;
                $campaign = ModelsCampaign::create([
                    'music_id' => $this->musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'cost_per_repost' => 0,
                    'budget_credits' => $totalBudget,
                    'user_urn' => user()->urn,
                    'status' => ModelsCampaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollower,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'comentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'max_repost_last_24h' => $this->maxRepostLast24h,
                    'max_reposts_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ]);
                CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $campaign->id,
                    'source_type' => ModelsCampaign::class,
                    'transaction_type' => CreditTransaction::TYPE_SPEND,
                    'status' => 'succeeded',
                    'credits' => $totalBudget,
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

            session()->flash('message', 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            // Close modal and complete reset
            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;
            $this->showPlaylistTracksModal = false;

            $this->reset([
                'musicId',
                'title',
                'description',
                'playlistId',
                'playlistTracks',
                'activeModalTab',
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
                'maxFollower',
                'proFeatureEnabled',
            ]);

            $this->resetValidation();
            $this->resetErrorBag();
            session()->flash('message', 'Campaign created successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }
    /**
     * ###########################################
     * ******* End Campaign Create Events ********
     * ###########################################
     */


    /**
     * ###########################################
     * ******* Start Audio Player Events ********
     * ###########################################
     */
    public function handleAudioPlay($campaignId)
    {
        $this->playingCampaigns[$campaignId] = true;
        $this->playStartTimes[$campaignId] = now()->timestamp;
    }

    /**
     * Handle audio pause event
     */
    public function handleAudioPause($campaignId)
    {
        $this->updatePlayTime($campaignId);
        unset($this->playingCampaigns[$campaignId]);
        unset($this->playStartTimes[$campaignId]);
    }

    /**
     * Handle audio time update event
     */
    public function handleAudioTimeUpdate($campaignId, $currentTime)
    {
        if ($currentTime >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            $this->dispatch('campaignPlayedEnough', $campaignId);
        }
    }

    /**
     * Handle audio ended event
     */
    public function handleAudioEnded($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    /**
     * Update play time for a campaign
     */
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

    /**
     * Polling method to update play times for currently playing campaigns
     */
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

    /**
     * Start playing a campaign manually
     */
    public function startPlaying($campaignId)
    {
        $this->handleAudioPlay($campaignId);
    }

    /**
     * Stop playing a campaign manually
     */
    public function stopPlaying($campaignId)
    {
        $this->handleAudioPause($campaignId);
    }

    /**
     * Simulate audio progress (for testing without actual audio)
     */
    public function simulateAudioProgress($campaignId, $seconds = 1)
    {
        if (!isset($this->playTimes[$campaignId])) {
            $this->playTimes[$campaignId] = 0;
        }

        $this->playTimes[$campaignId] += $seconds;

        if ($this->playTimes[$campaignId] >= 5 && !in_array($campaignId, $this->playedCampaigns)) {
            $this->playedCampaigns[] = $campaignId;
            session()->flash('success', 'Campaign marked as played for 5+ seconds!');
        }
    }

    /**
     * Check if campaign can be reposted
     */
    public function canRepost($campaignId): bool
    {
        $canRepost = in_array($campaignId, $this->playedCampaigns) &&
            !in_array($campaignId, $this->repostedCampaigns);

        if ($canRepost && !$this->playcount) {
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId));
            $campaign->increment('playback_count');
            $this->playcount = true; // Set playcount to true if repost is allowed
        }
        return $canRepost;
    }

    /**
     * Check if campaign is currently playing
     */
    public function isPlaying($campaignId): bool
    {
        return isset($this->playingCampaigns[$campaignId]) && $this->playingCampaigns[$campaignId] === true;
    }

    /**
     * Get total play time for a campaign
     */
    public function getPlayTime($campaignId): int
    {
        $baseTime = $this->playTimes[$campaignId] ?? 0;

        if ($this->isPlaying($campaignId) && isset($this->playStartTimes[$campaignId])) {
            $currentSessionTime = now()->timestamp - $this->playStartTimes[$campaignId];
            return $baseTime + $currentSessionTime;
        }

        return $baseTime;
    }

    /**
     * Get remaining time until repost is enabled
     */
    public function getRemainingTime($campaignId): int
    {
        $playTime = $this->getPlayTime($campaignId);
        return max(0, 5 - $playTime);
    }

    /**
     * Handle repost action
     */
    public function repost($campaignId)
    {
        try {
            if (!$this->canRepost($campaignId)) {
                session()->flash('error', 'You cannot repost this campaign. Please play it for at least 5 seconds first.');
                return;
            }
            $currentUserUrn = user()->urn;

            // Check if the current user owns the campaign
            if ($this->campaignService->getCampaigns()->where('id', $campaignId)->where('user_urn', $currentUserUrn)->exists()) {
                session()->flash('error', 'You cannot repost your own campaign.');
                return;
            }

            // Check if the user has already reposted this specific campaign
            if (
                Repost::where('reposter_urn', $currentUserUrn)
                ->where('campaign_id', $campaignId)
                ->exists()
            ) {
                session()->flash('error', 'You have already reposted this campaign.');
                return;
            }

            // Find the campaign and eager load its music and the music's user
            // $campaign = Campaign::with('music.user')->findOrFail($campaignId);
            $campaign = $this->campaignService->getCampaign(encrypt($campaignId))->load('music.user.userInfo');

            // Ensure music is associated with the campaign
            if (!$campaign->music) {
                session()->flash('error', 'Track or Playlist not found for this campaign.');
                return;
            }

            $soundcloudRepostId = null;

            // Prepare HTTP client with authorization header
            $httpClient = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ]);

            // Determine the SoundCloud API endpoint based on music type
            switch ($campaign->music_type) {
                case Track::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/tracks/{$campaign->music->urn}");
                    break;
                case Playlist::class:
                    $response = $httpClient->post("{$this->baseUrl}/reposts/playlists/{$campaign->music->urn}");
                    break;
                default:
                    session()->flash('error', 'Invalid music type specified for the campaign.');
                    return;
            }

            if ($response->successful()) {
                // If SoundCloud returns a repost ID, capture it (example, adjust based on actual SoundCloud API response)
                $soundcloudRepostId = $response->json('id');
                $this->campaignService->syncReposts($campaign, $currentUserUrn, $soundcloudRepostId);
                session()->flash('success', 'Campaign music reposted successfully.');
            } else {
                // Log the error response from SoundCloud for debugging
                Log::error("SoundCloud Repost Failed: " . $response->body(), [
                    'campaign_id' => $campaignId,
                    'user_urn' => $currentUserUrn,
                    'status' => $response->status(),
                ]);
                session()->flash('error', 'Failed to repost campaign music to SoundCloud. Please try again.');
            }
        } catch (Throwable $e) {
            Log::error("Error in repost method: " . $e->getMessage(), [
                'exception' => $e,
                'campaign_id_input' => $campaignId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
            return;
        }
    }
       
    public $searchQuery = '';
    public $allTracks = [];
    public $users = [];
    public $allPlaylists = [];
    public $playlistLimit = [];
    public $allPlaylistTracks = [];
    public $userinfo = [];
    public $showPlaylistTracksModal = false;
    public $trackLimit = 4;
    private $soundcloudClientId = 'YOUR_SOUNDCLOUD_CLIENT_ID';
    private $soundcloudApiUrl = 'https://api-v2.soundcloud.com';



    // This method is triggered when the `activeModalTab` property is updated
    public function updatedActiveModalTab($tab)
    {
        $this->activeModalTab = $tab;
        $this->searchQuery = '';
        if ($tab === 'tracks') {
            $this->fetchTracks();
        } elseif ($tab === 'playlists') {
            $this->fetchPlaylists();
        }
    }

    public function loadData()
    {
         $query = User::where('urn', '!=', user()->urn);
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $this->users = $query->get();
        $this->userinfo = UserInformation::where('user_urn', user()->urn)->first();
    }

    // public function updatedSearch()
    // {
    //     $this->loadData();
    // }

    public function updatedGenreFilter()
    {
        $this->loadData();
    }

    public function updatedCostFilter()
    {
        $this->loadData();
    }

    // Main search method for SoundCloud tracks and playlists
    public function searchSoundcloud()
    {
        // If the search query is empty, reset to local data
        if (empty($this->searchQuery)) {
            if ($this->showPlaylistTracksModal == true) {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
                $this->playlistTracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeModalTab == 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                }
                if ($this->activeModalTab == 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
            return;
        }

        // Check if the query is a SoundCloud permalink URL
        if (preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
            $this->resolveSoundcloudUrl();
        } else {
            // If not a URL, perform a text-based "as-like" search on the local database
            if ($this->showPlaylistTracksModal == true) {
                $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                    ->where(function ($query) {
                        $query->Where('permalink_url', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->get();
                $this->playlistTracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
            } else {
                if ($this->activeModalTab === 'tracks') {
                    $this->allTracks = Track::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%')
                                ->orWhere('author_soundcloud_permalink_url', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                } elseif ($this->activeModalTab === 'playlists') {
                    $this->allPlaylists = Playlist::where('user_urn', user()->urn)
                        ->where(function ($query) {
                            $query->where('permalink_url', 'like', '%' . $this->searchQuery . '%');
                        })
                        ->get();
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                }
            }
        }
    }

    // Resolves a SoundCloud URL to find the corresponding track or playlist
    protected function resolveSoundcloudUrl()
    {
        // Search the local database for matching permalink URLs first
        if ($this->showPlaylistTracksModal == true) {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('permalink_url', $this->searchQuery)
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->playlistTracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                return;
            }
        } else {
            if ($this->activeModalTab == 'tracks') {
                $tracksFromDb = Track::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->orWhere('author_soundcloud_permalink_url', $this->searchQuery)
                    ->get();
                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    return;
                }
            }

            if ($this->activeModalTab == 'playlists') {
                $playlistsFromDb = Playlist::where('user_urn', user()->urn)
                    ->where('permalink_url', $this->searchQuery)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    return;
                }
            }
        }

        // If not found locally, use SoundCloud's API to resolve the URL
        $response = Http::get("{$this->soundcloudApiUrl}/resolve", [
            'url' => $this->searchQuery,
            'client_id' => $this->soundcloudClientId,
        ]);

        if ($response->successful()) {
            $resolvedData = $response->json();
            $this->processResolvedData($resolvedData);
        } else {
            $this->allTracks = collect();
            $this->tracks = collect();
            session()->flash('error', 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    // Processes the data returned from the SoundCloud API
    protected function processResolvedData($data)
    {
        switch ($data['kind']) {
            case 'track':
                $this->activeModalTab = 'tracks';
                $this->allTracks = collect([$data]);
                $this->tracks = $this->allTracks->take($this->trackLimit);
                break;
            case 'user':
                $this->activeTab = 'tracks';
                $this->fetchUserTracks($data['id']);
                break;
            default:
                $this->allTracks = collect();
                $this->tracks = collect();
                session()->flash('error', 'The provided URL is not a track or playlist.');
                break;
        }
    }

    public function openRepostsModal($trackId)
    {
        $this->toggleSubmitModal('track', $trackId);
    }
    public function openPlaylistTracksModal($playlistId)
    {
        $this->toggleSubmitModal('playlist', $playlistId);
    }
    public function render()
    {
        return view('backend.user.campaign_management.campaign');
    }
}
