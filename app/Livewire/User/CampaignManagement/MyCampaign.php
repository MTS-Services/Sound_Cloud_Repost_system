<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Track;
use App\Models\Playlist;
use App\Services\TrackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MyCampaign extends Component
{
    use WithPagination;

    protected TrackService $trackService;

    // Pagination URL parameters
    #[Url(as: 'allPage')]
    public ?int $allPage = 1;

    #[Url(as: 'activePage')]
    public ?int $activePage = 1;

    #[Url(as: 'completedPage')]
    public ?int $completedPage = 1;

    // Search functionality - Only for modal use (no URL binding)
    public string $searchQuery = '';

    // Main tab state
    #[Url(as: 'tab', except: 'all')]
    public string $activeMainTab = 'all';

    // Modal states
    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
    public bool $showLowCreditWarningModal = false;
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public bool $showAlreadyCancelledModal = false;
    public bool $showDetailsModal = false;

    public $proFeatureValue = null;

    // Modal tab state
    public string $activeModalTab = 'tracks';

    // Content data
    public Collection $tracks;
    public Collection $playlists;
    public array $playlistTracks = [];
    public $campaign = null;

    // Form fields - Campaign Creation
    #[Locked]
    public $playlistId = null;
    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $endDate = null;
    public $targetReposts = null;
    public $costPerRepost = null;
    public $track = null;
    public int $credit = 100;
    public array $genres = [];
    public bool $commentable = false;
    public bool $likeable = false;
    public bool $proFeatureEnabled = false;
    public $maxFollower = null;
    public $maxRepostLast24h = null;
    public $maxRepostsPerDay = null;
    public $anyGenre = '';
    public $trackGenre = '';
    public $targetGenre = '';

    // Form fields - Add Credit
    public $addCreditCampaignId = null;
    public int $addCreditCostPerRepost = 0;
    public $addCreditCurrentBudget = null;
    public $addCreditTargetReposts = null;
    public $addCreditCreditsNeeded = 0;

    // Form fields - Edit Campaign
    public $editingCampaignId = null;
    public $editTitle = null;
    public $editDescription = null;
    public $editEndDate = null;
    public $editTargetReposts = null;
    public int $editCostPerRepost = 0;
    public $editOriginalBudget = null;

    // Form fields - Delete Campaign
    public $campaignToDeleteId = null;
    public $refundAmount = 0;

    // Validation states
    public bool $showBudgetWarning = false;
    public string $budgetWarningMessage = '';
    public bool $canSubmit = false;

    // Constraints
    #[Locked]
    public int $minFollowers = 0;
    public int $maxFollowers = 0;

    protected $listeners = ['campaignCreated' => 'handleCampaignCreated'];

    // Constants
    private const MIN_BUDGET = 50;
    private const MIN_CREDIT = 100;
    private const REFUND_PERCENTAGE = 0.5;
    private const ITEMS_PER_PAGE = 2;

    public function boot(TrackService $trackService): void
    {
        $this->trackService = $trackService;
        $this->tracks = collect();
        $this->playlists = collect();
    }

    protected function rules(): array
    {
        return [
            'credit' => 'required|integer|min:' . self::MIN_CREDIT,
        ];
    }

    protected function messages(): array
    {
        return [
            'credit.required' => 'Minimum credit is ' . self::MIN_CREDIT . '.',
            'maxFollower.required' => 'Max follower is required.',
        ];
    }

    // Modal search functionality - triggered by live wire model
    public function updatedSearchQuery(): void
    {
        if (!empty($this->searchQuery)) {
            $this->performModalSearch();
        } else {
            // Reset to show all user's content when search is cleared
            if ($this->activeModalTab === 'tracks') {
                $this->fetchTracks();
            } else {
                $this->fetchPlaylists();
            }
        }
    }

    // Main search method for modal
    public function searchSoundcloud(): void
    {
        if (empty($this->searchQuery)) {
            return;
        }

        $this->performModalSearch();
    }

    // Perform search in modal
    private function performModalSearch(): void
    {
        try {
            if ($this->activeModalTab === 'tracks') {
                $this->searchTracksInModal();
            } elseif ($this->activeModalTab === 'playlists') {
                $this->searchPlaylistsInModal();
            }
        } catch (\Exception $e) {
            $this->handleError('Search failed', $e);
        }
    }

    // Search tracks by title and permalink/URL
    private function searchTracksInModal(): void
    {
        // First search local tracks by title and permalink
        $localTracks = Track::select(['id', 'title', 'urn', 'user_urn', 'artwork_url', 'author_username', 'genre', 'playback_count', 'permalink_url'])
            ->where('user_urn', user()->urn)
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            })
            ->latest()
            ->get();

        $this->tracks = $localTracks;

        // If it looks like a SoundCloud URL, try to fetch from SoundCloud API
        if ($this->isSoundCloudUrl($this->searchQuery)) {
            $this->fetchTrackFromSoundCloudAPI();
        }
    }

    // Search playlists by title and permalink/URL
    private function searchPlaylistsInModal(): void
    {
        // First search local playlists by title and permalink
        $localPlaylists = Playlist::select(['id', 'title', 'soundcloud_urn', 'user_urn', 'artwork_url', 'track_count', 'permalink_url'])
            ->where('user_urn', user()->urn)
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            })
            ->latest()
            ->get();

        $this->playlists = $localPlaylists;

        // If it looks like a SoundCloud URL, try to fetch from SoundCloud API
        if ($this->isSoundCloudUrl($this->searchQuery)) {
            $this->fetchPlaylistFromSoundCloudAPI();
        }
    }
    

    // Check if the search query is a SoundCloud URL
    private function isSoundCloudUrl(string $query): bool
    {
        return str_contains(strtolower($query), 'soundcloud.com') || 
               str_contains(strtolower($query), 'on.soundcloud.com');
    }

    // Fetch track from SoundCloud API using URL
    private function fetchTrackFromSoundCloudAPI(): void
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'OAuth ' . user()->token])
                ->get('https://api.soundcloud.com/resolve', [
                    'url' => $this->searchQuery
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['kind']) && $data['kind'] === 'track') {
                    // Check if track already exists in our collection
                    $existingTrack = $this->tracks->firstWhere('urn', $data['urn'] ?? null);
                    
                    if (!$existingTrack && isset($data['title'], $data['urn'])) {
                        // Add the track data in a format compatible with our Track model
                        $trackData = (object) [
                            'id' => $data['id'] ?? null,
                            'title' => $data['title'],
                            'urn' => $data['urn'],
                            'user_urn' => user()->urn,
                            'artwork_url' => $data['artwork_url'] ?? null,
                            'author_username' => $data['user']['username'] ?? 'Unknown',
                            'genre' => $data['genre'] ?? 'Unknown',
                            'playback_count' => $data['playback_count'] ?? 0,
                            'permalink_url' => $data['permalink_url'] ?? null,
                            'is_soundcloud_result' => true // Flag to identify SoundCloud results
                        ];
                        
                        $this->tracks->prepend($trackData);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoundCloud track search failed', [
                'query' => $this->searchQuery,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Fetch playlist from SoundCloud API using URL
    private function fetchPlaylistFromSoundCloudAPI(): void
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'OAuth ' . user()->token])
                ->get('https://api.soundcloud.com/resolve', [
                    'url' => $this->searchQuery
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['kind']) && $data['kind'] === 'playlist') {
                    // Check if playlist already exists in our collection
                    $existingPlaylist = $this->playlists->firstWhere('soundcloud_urn', $data['id'] ?? null);
                    
                    if (!$existingPlaylist && isset($data['title'], $data['id'])) {
                        // Add the playlist data in a format compatible with our Playlist model
                        $playlistData = (object) [
                            'id' => $data['id'],
                            'title' => $data['title'],
                            'soundcloud_urn' => $data['id'],
                            'user_urn' => user()->urn,
                            'artwork_url' => $data['artwork_url'] ?? null,
                            'track_count' => $data['track_count'] ?? 0,
                            'permalink_url' => $data['permalink_url'] ?? null,
                            'is_soundcloud_result' => true // Flag to identify SoundCloud results
                        ];
                        
                        $this->playlists->prepend($playlistData);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoundCloud playlist search failed', [
                'query' => $this->searchQuery,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updated($propertyName): void
    {
        $budgetValidationFields = ['costPerRepost', 'targetReposts', 'editCostPerRepost', 'editTargetReposts'];

        if (in_array($propertyName, $budgetValidationFields)) {
            $this->validateBudget($propertyName);
        }

        if ($propertyName === 'addCreditCostPerRepost') {
            $this->validateAddCreditBudget();
        }
    }

    private function validateBudget(string $propertyName): void
    {
        if (str_starts_with($propertyName, 'edit')) {
            $this->validateEditBudget();
        } else {
            $this->validateCampaignBudget();
        }
    }

    private function validateCampaignBudget(): void
    {
        $this->resetBudgetValidation();

        if (!$this->isValidBudgetInput($this->costPerRepost, $this->targetReposts)) {
            return;
        }

        $totalBudget = $this->costPerRepost * $this->targetReposts;
        $userCredits = userCredits();

        if ($totalBudget < self::MIN_BUDGET) {
            $this->setBudgetWarning("Campaign budget must be at least " . self::MIN_BUDGET . " credits.");
            return;
        }

        if ($totalBudget > $userCredits) {
            $shortage = $totalBudget - $userCredits;
            $this->setBudgetWarning("You need {$shortage} more credits to create this campaign.");
            return;
        }

        if ($this->isAllRequiredFieldsFilled()) {
            $this->canSubmit = true;
        }
    }

    private function validateEditBudget(): void
    {
        $this->resetBudgetValidation();

        if (!$this->isValidBudgetInput($this->editCostPerRepost, $this->editTargetReposts)) {
            return;
        }

        $newBudget = $this->editCostPerRepost * $this->editTargetReposts;

        if ($newBudget < $this->editOriginalBudget) {
            $this->setBudgetWarning("Campaign budget cannot be decreased.");
            return;
        }

        $creditDifference = $newBudget - $this->editOriginalBudget;
        $userCredits = userCredits();

        if ($creditDifference > $userCredits) {
            $this->setBudgetWarning("You need {$creditDifference} more credits to update this campaign.");
            return;
        }

        if ($this->isAllEditFieldsFilled()) {
            $this->canSubmit = true;
        }
    }

    private function validateAddCreditBudget(): void
    {
        $this->resetBudgetValidation();

        if (!$this->addCreditCostPerRepost || $this->addCreditCostPerRepost <= 0) {
            return;
        }

        $newBudget = $this->addCreditCostPerRepost * $this->addCreditTargetReposts;

        if ($newBudget < $this->addCreditCurrentBudget) {
            $this->setBudgetWarning("Campaign budget cannot be decreased.");
            return;
        }

        $this->addCreditCreditsNeeded = $newBudget - $this->addCreditCurrentBudget;
        $userCredits = userCredits();

        if ($this->addCreditCreditsNeeded > $userCredits) {
            $this->setBudgetWarning("You need {$this->addCreditCreditsNeeded} more credits to update this campaign.");
            return;
        }

        $this->canSubmit = true;
    }

    private function isValidBudgetInput($costPerRepost, $targetReposts): bool
    {
        return $costPerRepost && $targetReposts && $costPerRepost > 0 && $targetReposts > 0;
    }

    private function isAllRequiredFieldsFilled(): bool
    {
        return !empty($this->title) && !empty($this->description) &&
            !empty($this->endDate) && !empty($this->musicId);
    }

    private function isAllEditFieldsFilled(): bool
    {
        return !empty($this->editTitle) && !empty($this->editDescription) &&
            !empty($this->editEndDate);
    }

    private function resetBudgetValidation(): void
    {
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = false;
    }

    private function setBudgetWarning(string $message): void
    {
        $this->showBudgetWarning = true;
        $this->budgetWarningMessage = $message;
        $this->canSubmit = false;
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeMainTab = $tab;

        // Reset the relevant pager when switching tabs
        match ($tab) {
            'all' => $this->resetPage('allPage'),
            'active' => $this->resetPage('activePage'),
            'completed' => $this->resetPage('completedPage'),
            default => $this->resetPage('allPage')
        };
    }

    public function toggleCampaignsModal(): void
    {
        $this->resetAllFormData();
        $this->showCampaignsModal = !$this->showCampaignsModal;

        if ($this->showCampaignsModal) {
            $this->selectModalTab('tracks');
        }
    }

    public function selectModalTab(string $tab = 'tracks'): void
    {
        $this->activeModalTab = $tab;
        
        // Clear search when switching tabs
        $this->searchQuery = '';

        match ($tab) {
            'tracks' => $this->fetchTracks(),
            'playlists' => $this->fetchPlaylists(),
            default => $this->fetchTracks()
        };
    }

    public function fetchTracks(): void
    {
        try {
            $this->tracks = Track::select(['id', 'title', 'urn', 'user_urn', 'artwork_url', 'author_username', 'genre', 'playback_count'])
                ->where('user_urn', user()->urn)
                ->latest()
                ->get();
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->handleError('Failed to load tracks', $e);
        }
    }

    public function fetchPlaylists(): void
    {
        try {
            $this->playlists = Playlist::select(['id', 'title', 'soundcloud_urn', 'user_urn', 'artwork_url', 'track_count'])
                ->where('user_urn', user()->urn)
                ->latest()
                ->get();
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->handleError('Failed to load playlists', $e);
        }
    }

    public function fetchPlaylistTracks(): void
    {
        if (!$this->playlistId) {
            $this->playlistTracks = [];
            return;
        }

        try {
            $playlist = Playlist::select(['id', 'soundcloud_urn'])
                ->findOrFail($this->playlistId);

            if (!$playlist->soundcloud_urn) {
                $this->playlistTracks = [];
                session()->flash('error', 'Playlist SoundCloud URN is missing.');
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'OAuth ' . user()->token])
                ->get("https://api.soundcloud.com/playlists/{$playlist->soundcloud_urn}/tracks");

            if ($response->successful()) {
                $tracks = $response->json();
                $this->playlistTracks = $this->filterValidTracks($tracks);
            } else {
                $this->playlistTracks = [];
                session()->flash('error', 'Failed to load playlist tracks from SoundCloud: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->playlistTracks = [];
            $this->handleError('Failed to fetch playlist tracks', $e, [
                'playlist_id' => $this->playlistId
            ]);
        }
    }

    private function filterValidTracks(array $tracks): array
    {
        if (!is_array($tracks)) {
            return [];
        }

        return collect($tracks)->filter(function ($track) {
            return is_array($track) &&
                isset($track['urn'], $track['title'], $track['user']) &&
                is_array($track['user']) &&
                isset($track['user']['username']);
        })->values()->toArray();
    }

    public function toggleSubmitModal(string $type, int $id): void
    {
        $this->resetFormValidation();

        if (userCredits() < self::MIN_BUDGET) {
            $this->showLowCreditWarningModal = true;
            return;
        }

        $this->showSubmitModal = true;

        try {
            match ($type) {
                'track' => $this->setupTrackSubmission($id),
                'playlist' => $this->setupPlaylistSubmission($id),
                default => throw new \InvalidArgumentException("Invalid type: {$type}")
            };
        } catch (\Exception $e) {
            $this->handleSubmissionError($e, $type, $id);
        }
    }

    private function setupTrackSubmission(int $id): void
    {
        $this->track = Track::select(['id', 'title', 'urn'])
            ->findOrFail($id);

        if (!$this->track->urn || !$this->track->title) {
            throw new \Exception('Track data is incomplete');
        }

        $this->musicId = $this->track->id;
        $this->musicType = Track::class;
        $this->title = $this->track->title . ' Campaign';
    }

    private function setupPlaylistSubmission(int $id): void
    {
        $playlist = Playlist::select(['id', 'title'])
            ->findOrFail($id);

        if (!$playlist->title) {
            throw new \Exception('Playlist data is incomplete');
        }

        $this->playlistId = $id;
        $this->title = $playlist->title . ' Campaign';
        $this->musicType = Playlist::class;
        $this->fetchPlaylistTracks();
        $this->musicId = null;
    }

    private function handleSubmissionError(\Exception $e, string $type, int $id): void
    {
        session()->flash('error', 'Failed to load content: ' . $e->getMessage());
        $this->showSubmitModal = false;
        $this->showCampaignsModal = true;

        Log::error('Toggle submit modal error: ' . $e->getMessage(), [
            'type' => $type,
            'id' => $id,
            'user_urn' => user()->urn ?? 'unknown'
        ]);
    }

    public function getAllGenres(): void
    {
        $this->genres = $this->trackService->getTracks()
            ->where('user_urn', user()->urn)
            ->pluck('genre')
            ->unique()
            ->values()
            ->toArray();
    }

    public function createCampaign(): void
    {
        $this->validate();

        try {
            $totalBudget = $this->credit;

            DB::transaction(function () use ($totalBudget) {
                $campaign = $this->createCampaignRecord($totalBudget);
                $this->createCreditTransaction($campaign, $totalBudget);
            });

            $this->handleSuccessfulCampaignCreation();
        } catch (\Exception $e) {
            $this->handleError('Failed to create campaign', $e, [
                'music_id' => $this->musicId,
                'title' => $this->title,
                'total_budget' => $totalBudget ?? 0,
            ]);
        }
    }

    private function createCampaignRecord(int $totalBudget): Campaign
    {
        return Campaign::create([
            'music_id' => $this->musicId,
            'music_type' => $this->musicType,
            'title' => $this->title,
            'description' => $this->description,
            'budget_credits' => $totalBudget,
            'user_urn' => user()->urn,
            'status' => Campaign::STATUS_OPEN,
            'max_followers' => $this->maxFollower,
            'creater_id' => user()->id,
            'creater_type' => get_class(user()),
            'comentable' => 1,
            'likeable' => $this->likeable ? 1 : 0,
            'pro_feature' => $this->proFeatureEnabled ? 1 : 0,
            'max_repost_last_24h' => $this->maxRepostLast24h,
            'max_reposts_per_day' => $this->maxRepostsPerDay,
            'target_genre' => $this->targetGenre,
        ]);
    }

    private function createCreditTransaction(Campaign $campaign, int $amount): void
    {
        CreditTransaction::create([
            'receiver_urn' => user()->urn,
            'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
            'source_id' => $campaign->id,
            'source_type' => Campaign::class,
            'transaction_type' => CreditTransaction::TYPE_SPEND,
            'status' => 'succeeded',
            'credits' => $amount,
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
    }

    private function handleSuccessfulCampaignCreation(): void
    {
        session()->flash('message', 'Campaign created successfully!');
        $this->dispatch('campaignCreated');
        $this->closeAllModals();
        $this->resetAllFormData();
    }

    // Modal Management Methods
    public function openAddCreditModal(Campaign $campaign): void
    {
        if ($this->isCampaignCancelled($campaign)) return;

        $this->resetFormValidation();
        $this->addCreditCampaignId = $campaign->id;
        $this->addCreditCurrentBudget = $campaign->budget_credits;
        $this->addCreditCreditsNeeded = 0;
        $this->resetBudgetValidation();
        $this->canSubmit = true;
        $this->showAddCreditModal = true;
        $this->closeOtherModals(['showAddCreditModal']);
    }

    public function openViewDetailsModal(int $id): void
    {
        $this->campaign = Campaign::with(['music', 'user'])
            ->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeViewDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->campaign = null;
        $this->resetFormValidation();
    }

    public function openEditCampaignModal(Campaign $campaign): void
    {
        if ($this->isCampaignCancelled($campaign)) return;

        $this->resetFormValidation();
        $this->populateEditForm($campaign);
        $this->resetBudgetValidation();
        $this->canSubmit = true;
        $this->showEditCampaignModal = true;
        $this->closeOtherModals(['showEditCampaignModal']);
    }

    private function populateEditForm(Campaign $campaign): void
    {
        $this->editingCampaignId = $campaign->id;
        $this->editTitle = $campaign->title;
        $this->editDescription = $campaign->description;
        $this->editEndDate = $campaign->end_date->format('Y-m-d');
        $this->editOriginalBudget = $campaign->budget_credits;
    }

    public function openCancelWarningModal(Campaign $campaign): void
    {
        if ($this->isCampaignCancelled($campaign)) return;

        $this->resetFormValidation();
        $this->campaignToDeleteId = $campaign->id;
        $this->calculateRefundAmount($campaign);
        $this->showCancelWarningModal = true;
        $this->closeOtherModals(['showCancelWarningModal']);
    }

    private function calculateRefundAmount(Campaign $campaign): void
    {
        $remainingBudget = $campaign->budget_credits - $campaign->credits_spent;
        $this->refundAmount = floor($remainingBudget * self::REFUND_PERCENTAGE);
    }

    private function isCampaignCancelled(Campaign $campaign): bool
    {
        if ($campaign->status === Campaign::STATUS_CANCELLED) {
            $this->openAlreadyCancelledModal();
            return true;
        }
        return false;
    }

    public function openAlreadyCancelledModal(): void
    {
        $this->showAlreadyCancelledModal = true;
        $this->closeOtherModals(['showAlreadyCancelledModal']);
        $this->resetFormValidation();
    }

    public function updateCampaign(): void
    {
        $this->validate();

        try {
            $campaign = Campaign::findOrFail($this->editingCampaignId);
            $newBudgetCredits = $this->editCostPerRepost * $this->editTargetReposts;
            $creditDifference = $newBudgetCredits - $campaign->budget_credits;

            if ($creditDifference < 0) {
                session()->flash('error', 'Campaign budget cannot be decreased.');
                return;
            }

            if ($creditDifference > 0 && $creditDifference > userCredits()) {
                session()->flash('error', "You need {$creditDifference} more credits to update this campaign budget.");
                $this->showLowCreditWarningModal = true;
                $this->showEditCampaignModal = false;
                return;
            }

            DB::transaction(function () use ($campaign, $newBudgetCredits, $creditDifference) {
                $this->updateCampaignRecord($campaign, $newBudgetCredits);

                if ($creditDifference > 0) {
                    $this->createUpdateCreditTransaction($campaign, $creditDifference);
                }
            });

            session()->flash('success', 'Campaign updated successfully!');
            $this->showEditCampaignModal = false;
        } catch (\Exception $e) {
            $this->handleError('Failed to update campaign', $e, [
                'campaign_id' => $this->editingCampaignId
            ]);
        }
    }

    private function updateCampaignRecord(Campaign $campaign, int $newBudgetCredits): void
    {
        $campaign->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'end_date' => $this->editEndDate,
            'budget_credits' => $newBudgetCredits,
            'updater_id' => user()->id,
            'updater_type' => get_class(user())
        ]);
    }

    private function createUpdateCreditTransaction(Campaign $campaign, int $creditDifference): void
    {
        CreditTransaction::create([
            'receiver_urn' => user()->urn,
            'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
            'source_id' => $campaign->id,
            'source_type' => Campaign::class,
            'transaction_type' => CreditTransaction::TYPE_SPEND,
            'status' => 'succeeded',
            'credits' => $creditDifference,
            'description' => 'Spent on campaign update',
            'metadata' => [
                'campaign_id' => $campaign->id,
                'action' => 'edit_campaign',
                'updated_at' => now(),
            ],
            'created_id' => user()->id,
            'created_type' => get_class(user())
        ]);
    }

    public function cancelCampaign(): void
    {
        try {
            $campaign = Campaign::findOrFail($this->campaignToDeleteId);

            DB::transaction(function () use ($campaign) {
                if ($this->refundAmount > 0) {
                    $this->createRefundTransaction($campaign);
                }
                $this->updateCampaignStatus($campaign);
            });

            session()->flash('success', 'Campaign canceled successfully! ' . number_format($this->refundAmount) . ' credits refunded.');
            $this->showCancelWarningModal = false;
        } catch (\Exception $e) {
            $this->handleError('Failed to delete campaign', $e, [
                'campaign_id' => $this->campaignToDeleteId
            ]);
        }
    }

    private function createRefundTransaction(Campaign $campaign): void
    {
        CreditTransaction::create([
            'receiver_urn' => user()->urn,
            'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
            'source_id' => $campaign->id,
            'source_type' => Campaign::class,
            'transaction_type' => CreditTransaction::TYPE_REFUND,
            'status' => 'succeeded',
            'credits' => $this->refundAmount,
            'description' => 'Refund for canceled campaign (50% of remaining budget)',
            'metadata' => [
                'campaign_id' => $campaign->id,
                'action' => 'campaign_canceled',
                'refund_percentage' => 50,
                'canceled_at' => now(),
            ],
            'created_id' => user()->id,
            'created_type' => get_class(user())
        ]);
    }

    private function updateCampaignStatus(Campaign $campaign): void
    {
        $campaign->update([
            'status' => Campaign::STATUS_CANCELLED,
            'refund_credits' => $this->refundAmount,
            'updater_id' => user()->id,
            'updater_type' => get_class(user())
        ]);
    }

    public function handleCampaignCreated(): void
    {
        // Refresh will happen automatically in render method
    }

    private function getCampaignsQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Campaign::with(['music' => function ($query) {
            $query->select(['id', 'title', 'urn']); // Only select needed fields
        }])
            ->select([
                'id',
                'music_id',
                'music_type',
                'title',
                'description',
                'budget_credits',
                'credits_spent',
                'status',
                'created_at',
                'end_date',
                'refund_credits'
            ])
            ->where('user_urn', user()->urn);
    }

    private function closeAllModals(): void
    {
        $this->showCampaignsModal = false;
        $this->showSubmitModal = false;
        $this->closeOtherModals([]);
    }

    private function closeOtherModals(array $except = []): void
    {
        $modals = [
            'showSubmitModal',
            'showCampaignsModal',
            'showEditCampaignModal',
            'showAddCreditModal',
            'showCancelWarningModal'
        ];

        foreach ($modals as $modal) {
            if (!in_array($modal, $except)) {
                $this->$modal = false;
            }
        }
    }

    private function resetAllFormData(): void
    {
        $this->reset([
            'activeModalTab',
            'playlistId',
            'playlistTracks',
            'musicId',
            'title',
            'description',
            'endDate',
            'targetReposts',
            'costPerRepost',
            'minFollowers',
            'maxFollowers',
            'addCreditCostPerRepost',
            'addCreditCampaignId',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'addCreditCreditsNeeded',
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'editOriginalBudget',
            'campaignToDeleteId',
            'refundAmount',
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
            'searchQuery' // Reset search query when closing modal
        ]);

        $this->resetFormValidation();
        $this->tracks = collect();
        $this->playlists = collect();
        $this->activeModalTab = 'tracks';
    }

    private function resetFormValidation(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
    }

    private function handleError(string $message, \Exception $e, array $context = []): void
    {
        session()->flash('error', $message . ': ' . $e->getMessage());

        Log::error($message . ': ' . $e->getMessage(), array_merge([
            'user_urn' => user()->urn ?? 'unknown'
        ], $context));
    }

    public function render()
    {
        try {
            $campaigns = match ($this->activeMainTab) {
                'active' => $this->getCampaignsQuery()
                    ->Open()
                    ->latest()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'activePage', $this->activePage),
                'completed' => $this->getCampaignsQuery()
                    ->Completed()
                    ->latest()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'completedPage', $this->completedPage),
                default => $this->getCampaignsQuery()
                    ->latest()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'allPage', $this->allPage)
            };
        } catch (\Exception $e) {
            $campaigns = collect();
            $this->handleError('Failed to load campaigns', $e);
        }

        return view('backend.user.campaign_management.my_campaigns', [
            'campaigns' => $campaigns,
        ]);
    }
}