<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Faq;
use App\Models\Track;
use App\Models\Playlist;
use App\Models\User;
use App\Models\UserInformation;
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
    // public $campaigns;
    public $faqs;

    #[Url(as: 'activePage')]
    public ?int $activePage = 1;

    #[Url(as: 'completedPage')]
    public ?int $completedPage = 1;

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

    public $proFeatureValue = 1;

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
    public bool $commentable = true;
    public bool $likeable = true;
    public bool $proFeatureEnabled = false;
    public $maxFollower = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $anyGenre = '';
    public $trackGenre = '';
    public $targetGenre = '';

    // Form fields - Add Credit
    public $addCreditCampaignId = null;
    public int $addCreditCostPerRepost = 0;
    public $addCreditCurrentBudget = null;
    public $addCreditTargetReposts = null;
    public int $addCreditCreditsNeeded = 0;

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

    // Campaign edit 
    public $isEditing = false;
    public $editingCampaign = null;

    // Search and pagination properties
    #[Url(as: 'q', except: '')]
    public string $searchQuery = '';
    public $selectedPlaylistId = null;
    public $allTracks;
    public $users;
    public $allPlaylists;
    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;
    public $allPlaylistTracks;
    public $userinfo;
    public $trackLimit = 4;
    public $tracksPage = 1; // Fixed: Initialize to 1
    public $playlistsPage = 1; // Fixed: Initialize to 1
    public $perPage = 4;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;
    private $soundcloudClientId = 'YOUR_SOUNDCLOUD_CLIENT_ID';
    private $soundcloudApiUrl = 'https://api-v2.soundcloud.com';
    public $playListTrackShow = false;

    public function boot(TrackService $trackService): void
    {
        $this->trackService = $trackService;
        $this->tracks = collect();
        $this->playlists = collect();
    }

    protected function rules(): array
    {
        $rules = [
            'credit' => [
                'required',
                'integer',
                'min:100',
                function ($attribute, $value, $fail) {
                    if ($value > userCredits()) {
                        $fail('The credit is not available.');
                    }
                    if($this->editingCampaign->budget_credits > $value){
                        $fail('The credit is not available.');
                    }
                },
            ],
        ];

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'credit.required' => 'Minimum credit is ' . self::MIN_CREDIT . '.',
            'maxFollower.required' => 'Max follower is required.',
        ];
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

        // Reset pagination when switching tabs
        $this->tracksPage = 1;
        $this->playlistsPage = 1;
        $this->searchQuery = '';
        $this->playListTrackShow = false;

        match ($tab) {
            'tracks' => $this->fetchTracks(),
            'playlists' => $this->fetchPlaylists(),
            default => $this->fetchTracks()
        };
    }

    public function fetchTracks(): void
    {
        try {
            $this->allTracks = Track::self()
                ->latest()
                ->get();

            // Reset pagination and load initial tracks
            $this->tracksPage = 1;
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->tracks = collect();
            $this->allTracks = collect();
            $this->hasMoreTracks = false;
            $this->handleError('Failed to load tracks', $e);
        }
    }

    public function fetchPlaylists(): void
    {
        try {
            $this->allPlaylists = Playlist::self()
                ->latest()
                ->get();

            // Reset pagination and load initial playlists
            $this->playlistsPage = 1;
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->playlists = collect();
            $this->allPlaylists = collect();
            $this->hasMorePlaylists = false;
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
            $playlist = Playlist::findOrFail($this->playlistId);

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
        $this->track = Track::findOrFail($id);

        if (!$this->track->urn || !$this->track->title) {
            throw new \Exception('Track data is incomplete');
        }

        $this->musicId = $this->track->id;
        $this->musicType = Track::class;
        $this->title = $this->track->title . ' Campaign';
    }

    private function setupPlaylistSubmission(int $id): void
    {
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

    public function getAllGenres()
    {
        $this->genres = $this->trackService->getTracks()->where('user_urn', '!=', user()->urn)->pluck('genre')->unique()->values()->toArray();
    }

    public function profeature($isChecked)
    {
        $this->proFeatureEnabled = $isChecked ? true : false;
        $this->proFeatureValue = $isChecked ? 0 : 1;
    }

    public function createCampaign()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                $totalBudget = $this->editingCampaign->budget_credits - $this->credit;
            } else {
                $totalBudget = $this->credit;
            }
            if ($this->anyGenre == 'anyGenre') {
                $this->targetGenre = $this->anyGenre;
            }
            if ($this->trackGenre == 'trackGenre') {
                $this->targetGenre = $this->trackGenre;
            }
            DB::transaction(function () use ($totalBudget) {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled ? 1 : 0;
                $editingProFeature = $this->isEditing && $this->editingCampaign->pro_feature == 1 ? $this->editingCampaign->pro_feature : $proFeatureEnabled;
                $campaign = [
                    'music_id' => $this->musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'budget_credits' => $this->credit,
                    'user_urn' => user()->urn,
                    'status' => Campaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollower,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $this->isEditing && $this->editingCampaign ? $editingProFeature : $proFeatureEnabled,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ];
                if (!$this->isEditing) {
                    $campaign = Campaign::create($campaign);
                } else {
                    $this->editingCampaign->update($campaign);
                    $campaign = $this->editingCampaign;
                }
                CreditTransaction::create([
                    'receiver_urn' => user()->urn,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                    'source_id' => $campaign->id,
                    'source_type' => Campaign::class,
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
                'maxFollower',
                'editingCampaign',
                'isEditing'
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
    public function editCampaign($userId)
    {
        $this->editingCampaign = Campaign::where('id', $userId)
            ->where('creater_id', user()->id)->open()
            ->first();

        if (!$this->editingCampaign) {
            session()->flash('error', 'Campaign not found or cannot be edited.');
            return;
        }

        // Load campaign data into component properties
        $this->loadCampaignData();
        $this->isEditing = true;
        $this->showSubmitModal = true;
    }

    // Method to load campaign data into form fields
    private function loadCampaignData()
    {
        if (!$this->editingCampaign) return;

        $this->musicId = $this->editingCampaign->music_id;
        $this->musicType = $this->editingCampaign->music_type;
        $this->title = $this->editingCampaign->title;
        $this->description = $this->editingCampaign->description;
        $this->credit = $this->editingCampaign->budget_credits;
        $this->maxFollower = $this->editingCampaign->max_followers;
        $this->commentable = $this->editingCampaign->commentable;
        $this->likeable = $this->editingCampaign->likeable;
        $this->proFeatureEnabled = $this->editingCampaign->pro_feature;
        $this->maxRepostLast24h = $this->editingCampaign->max_repost_last_24_h;
        $this->maxRepostsPerDay = $this->editingCampaign->max_repost_per_day;
        $this->targetGenre = $this->editingCampaign->target_genre;

        // Set genre radio buttons based on target_genre
        if ($this->targetGenre === 'anyGenre') {
            $this->anyGenre = 'anyGenre';
        } elseif ($this->targetGenre === 'trackGenre') {
            $this->trackGenre = 'trackGenre';
        }
        $this->loadTrackData();
    }
    private function loadTrackData()
    {
        if ($this->musicType === 'track' && $this->musicId) {
            // Assuming you have a method to get track data
            $this->track = $this->getTrackById($this->musicId);
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
            $query->self();
        }]);
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
            'searchQuery',
            'tracksPage',
            'playlistsPage'
        ]);

        $this->resetFormValidation();
        $this->tracks = collect();
        $this->playlists = collect();
        $this->activeModalTab = 'tracks';
        $this->tracksPage = 1;
        $this->playlistsPage = 1;
        $this->hasMoreTracks = false;
        $this->hasMorePlaylists = false;
        $this->playListTrackShow = false;
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


    // This method is triggered when the `activeTab` property is updated
    public function updatedactiveTab($tab)
    {
        $this->activeModalTab = $tab;
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
            $this->resetSearchData();
            return;
        }
        // if (filter_var($this->searchQuery, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery)) {
        $this->performLocalSearch();
    }

    private function performLocalSearch()
    {
        if ($this->activeModalTab === 'tracks' && $this->playListTrackShow === true) {
            $this->allPlaylistTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where(function ($query) {
                    $query->where('permalink_url', $this->searchQuery)
                        ->orWhere('title', 'like', '%' . $this->searchQuery . '%');
                })
                ->get();
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } elseif ($this->activeModalTab === 'tracks') {
            $query = Track::self()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
                });

            $this->allTracks = $query->get();
            if ($this->allTracks->isEmpty() && $this->isSoundcloudUrl()) {
                $this->resolveSoundcloudUrl();
            }
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } elseif ($this->activeModalTab === 'playlists') {
            $query = Playlist::self()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
                });

            $this->allPlaylists = $query->get();
            if ($this->allPlaylists->isEmpty() && $this->isSoundcloudUrl()) {
                $this->resolveSoundcloudUrl();
            }
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        }
    }
    private function isSoundcloudUrl()
    {
        return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\//', $this->searchQuery);
    }

    private function resetSearchData()
    {
        if ($this->activeModalTab === 'tracks') {
            if ($this->playListTrackShow && $this->selectedPlaylistId) {
                $this->allTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
            } else {
                $this->allTracks = Track::self()->get();
            }
            $this->tracks = $this->allTracks->take($this->trackLimit);
        } elseif ($this->activeModalTab === 'playlists') {
            $this->allPlaylists = Playlist::self()->get();
            $this->playlists = $this->allPlaylists->take($this->playlistLimit);
        }
    }

    protected function resolveSoundcloudUrl()
    {
        $response = Http::get("{$this->soundcloudApiUrl}/resolve", [
            'url' => $this->searchQuery,
            'client_id' => $this->soundcloudClientId,
        ]);

        if ($response->successful()) {
            $this->processResolvedData($response->json());
        } else {
            $this->resetCollections();
            session()->flash('error', 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processResolvedData(array $data)
    {
        if ($this->activeModalTab === 'tracks') {
            if ($data['kind'] === 'track') {
                $localTrack = Track::self()->where('urn', $data['urn'])->first();
                $this->allTracks = $localTrack ? collect([$localTrack]) : collect();
                $this->tracks = $this->allTracks->take($this->trackLimit);
            } elseif ($data['kind'] === 'user') {
                $this->fetchUserTracks($data['id']);
            } else {
                $this->resetCollections();
                session()->flash('error', 'The provided URL is not a track or user profile.');
            }
        } elseif ($this->activeModalTab === 'playlists') {
            if ($data['kind'] === 'playlist') {
                $localPlaylist = Playlist::self()->where('soundcloud_urn', $data['urn'])->first();
                $this->allPlaylists = $localPlaylist ? collect([$localPlaylist]) : collect();
                $this->playlists = $this->allPlaylists->take($this->playlistLimit);
            } else {
                $this->resetCollections();
                session()->flash('error', 'The provided URL is not a playlist.');
            }
        }
    }

    protected function resetCollections()
    {
        $this->allTracks = collect();
        $this->tracks = collect();
        $this->allPlaylists = collect();
        $this->playlists = collect();
    }
    // Resolves a SoundCloud URL to find the corresponding track or playlist

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);
        if ($playlist) {
            $this->allPlaylistTracks = $playlist->tracks;
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } else {
            $this->allPlaylistTracks = collect();
            $this->tracks = collect();
            $this->hasMoreTracks = false;
        }
        $this->activeModalTab = 'tracks';
        $this->playListTrackShow = true;
        $this->tracksPage = 1; // Reset pagination

        $this->reset([
            'searchQuery',
        ]);
    }


    // Fixed loadMoreTracks method
    public function loadMoreTracks()
    {
        $this->tracksPage++;

        $sourceCollection = ($this->playListTrackShow) ? $this->allPlaylistTracks : $this->allTracks;

        $startIndex = ($this->tracksPage - 1) * $this->perPage;
        $newTracks = $sourceCollection->slice($startIndex, $this->perPage);

        $this->tracks = $this->tracks->concat($newTracks);
        $this->hasMoreTracks = $newTracks->count() === $this->perPage;
    }
    // Fixed loadMorePlaylists method
    public function loadMorePlaylists()
    {
        $this->playlistsPage++;

        $startIndex = ($this->playlistsPage - 1) * $this->perPage;
        $newPlaylists = $this->allPlaylists->slice($startIndex, $this->perPage);

        $this->playlists = $this->playlists->concat($newPlaylists);
        $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    }



    // public function addCreditsToCampaign()
    // {
    //     $this->validate([
    //         'addCreditCostPerRepost' => 'required|numeric|min:1',
    //     ]);

    //     try {
    //         $campaign = Campaign::findOrFail($this->addCreditCampaignId);

    //         $newTotalBudget = $this->addCreditCostPerRepost * $campaign->target_reposts;
    //         $creditsNeeded = $newTotalBudget - $campaign->budget_credits;

    //         if ($creditsNeeded <= 0) {
    //             session()->flash('warning', 'Campaign budget cannot be reduced.');
    //             $this->showAddCreditModal = false;
    //             $this->refreshCampaigns();
    //             return;
    //         }

    //         if ($creditsNeeded > userCredits()) {
    //             session()->flash('error', 'You need ' . $creditsNeeded . ' more credits to update this campaign budget.');
    //             $this->showLowCreditWarningModal = true;
    //             $this->showAddCreditModal = false;
    //             return;
    //         }

    //         DB::transaction(function () use ($campaign, $newTotalBudget, $creditsNeeded) {
    //             $campaign->update([
    //                 'budget_credits' => $newTotalBudget,
    //                 'updater_id' => user()->id,
    //                 'updater_type' => get_class(user())
    //             ]);

    //             CreditTransaction::create([
    //                 'receiver_urn' => user()->urn,
    //                 'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
    //                 'source_id' => $campaign->id,
    //                 'source_type' => Campaign::class,
    //                 'transaction_type' => CreditTransaction::TYPE_SPEND,
    //                 'status' => 'succeeded',
    //                 'credits' => $creditsNeeded,
    //                 'description' => 'Spent on campaign budget increase',
    //                 'metadata' => [
    //                     'campaign_id' => $campaign->id,
    //                     'action' => 'add_credits',
    //                     'updated_at' => now(),
    //                 ],
    //                 'created_id' => user()->id,
    //                 'created_type' => get_class(user())
    //             ]);
    //         });

    //         session()->flash('success', 'Campaign budget updated successfully!');
    //         $this->showAddCreditModal = false;
    //         $this->refreshCampaigns();
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Failed to add credits: ' . $e->getMessage());
    //         Log::error('Add credit error: ' . $e->getMessage(), [
    //             'campaign_id' => $this->addCreditCampaignId,
    //             'user_urn' => user()->urn ?? 'unknown',
    //         ]);
    //     }
    // }

    // Methods for Edit functionality
    // public function openEditCampaignModal(Campaign $campaign)
    // {
    //     $this->resetValidation();
    //     $this->resetErrorBag();

    //     if ($campaign->status === Campaign::STATUS_CANCELLED) {
    //         $this->openAlreadyCancelledModal();
    //         return;
    //     }

    //     $this->editingCampaignId = $campaign->id;
    //     $this->editTitle = $campaign->title;
    //     $this->editDescription = $campaign->description;
    //     $this->editEndDate = $campaign->end_date->format('Y-m-d');
    //     $this->editOriginalBudget = $campaign->budget_credits;

    //     // Reset warning states
    //     $this->showBudgetWarning = false;
    //     $this->budgetWarningMessage = '';
    //     $this->canSubmit = true; // Default to true for editing

    //     $this->showEditCampaignModal = true;

    //     // Close other modals
    //     $this->showSubmitModal = false;
    //     $this->showCampaignsModal = false;
    //     $this->showAddCreditModal = false;
    //     $this->showCancelWarningModal = false;
    // }

    // public function updateCampaign()
    // {
    //     $this->validate();

    //     try {
    //         $campaign = Campaign::findOrFail($this->editingCampaignId);

    //         $newBudgetCredits = $this->editCostPerRepost * $this->editTargetReposts;
    //         $creditDifference = $newBudgetCredits - $campaign->budget_credits;

    //         // Prevent budget decrease
    //         if ($creditDifference < 0) {
    //             session()->flash('error', 'Campaign budget cannot be decreased.');
    //             return;
    //         }

    //         // Check if user has enough credits for increase
    //         if ($creditDifference > 0 && $creditDifference > userCredits()) {
    //             session()->flash('error', 'You need ' . $creditDifference . ' more credits to update this campaign budget.');
    //             $this->showLowCreditWarningModal = true;
    //             $this->showEditCampaignModal = false;
    //             return;
    //         }

    //         DB::transaction(function () use ($campaign, $newBudgetCredits, $creditDifference) {
    //             $campaign->update([
    //                 'title' => $this->editTitle,
    //                 'description' => $this->editDescription,
    //                 'end_date' => $this->editEndDate,
    //                 'budget_credits' => $newBudgetCredits,
    //                 'updater_id' => user()->id,
    //                 'updater_type' => get_class(user())
    //             ]);

    //             // Create credit transaction only if budget increased
    //             if ($creditDifference > 0) {
    //                 CreditTransaction::create([
    //                     'receiver_urn' => user()->urn,
    //                     'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
    //                     'source_id' => $campaign->id,
    //                     'source_type' => Campaign::class,
    //                     'transaction_type' => CreditTransaction::TYPE_SPEND,
    //                     'status' => 'succeeded',
    //                     'credits' => $creditDifference,
    //                     'description' => 'Spent on campaign update',
    //                     'metadata' => [
    //                         'campaign_id' => $campaign->id,
    //                         'action' => 'edit_campaign',
    //                         'updated_at' => now(),
    //                     ],
    //                     'created_id' => user()->id,
    //                     'created_type' => get_class(user())
    //                 ]);
    //             }
    //         });

    //         session()->flash('success', 'Campaign updated successfully!');
    //         $this->showEditCampaignModal = false;
    //         $this->refreshCampaigns();
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Failed to update campaign: ' . $e->getMessage());
    //         Log::error('Campaign update error: ' . $e->getMessage(), [
    //             'campaign_id' => $this->editingCampaignId,
    //             'user_urn' => user()->urn ?? 'unknown',
    //         ]);
    //     }
    // }

    // Methods for Delete functionality
    // public function openCancelWarningModal(Campaign $campaign)
    // {
    //     $this->resetValidation();
    //     $this->resetErrorBag();

    //     if ($campaign->status === Campaign::STATUS_CANCELLED) {
    //         $this->openAlreadyCancelledModal();
    //         return;
    //     }

    //     $this->campaignToDeleteId = $campaign->id;

    //     // Calculate remaining budget and 50% refund
    //     $remainingBudget = $campaign->budget_credits - $campaign->credits_spent;
    //     $this->refundAmount = floor($remainingBudget * 0.5);

    //     $this->showCancelWarningModal = true;

    //     // Close other modals
    //     $this->showSubmitModal = false;
    //     $this->showCampaignsModal = false;
    //     $this->showAddCreditModal = false;
    //     $this->showEditCampaignModal = false;
    // }

    // public function cancelCampaign()
    // {
    //     try {
    //         $campaign = Campaign::findOrFail($this->campaignToDeleteId);

    //         DB::transaction(function () use ($campaign) {
    //             // Refund 50% of remaining budget if any
    //             if ($this->refundAmount > 0) {
    //                 CreditTransaction::create([
    //                     'receiver_urn' => user()->urn,
    //                     'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
    //                     'source_id' => $campaign->id,
    //                     'source_type' => Campaign::class,
    //                     'transaction_type' => CreditTransaction::TYPE_REFUND,
    //                     'status' => 'succeeded',
    //                     'credits' => $this->refundAmount,
    //                     'description' => 'Refund for canceled campaign (50% of remaining budget)',
    //                     'metadata' => [
    //                         'campaign_id' => $campaign->id,
    //                         'action' => 'campaign_canceled',
    //                         'refund_percentage' => 50,
    //                         'canceled_at' => now(),
    //                     ],
    //                     'created_id' => user()->id,
    //                     'created_type' => get_class(user())
    //                 ]);
    //             }

    //             // update the status of the campaign
    //             $campaign->update([
    //                 'status' => Campaign::STATUS_CANCELLED,
    //                 'refund_credits' => $this->refundAmount,
    //                 'updater_id' => user()->id,
    //                 'updater_type' => get_class(user())
    //             ]);
    //         });

    //         session()->flash('success', 'Campaign canceled successfully! ' . number_format($this->refundAmount) . ' credits refunded.');
    //         $this->showCancelWarningModal = false;
    //         $this->refreshCampaigns();
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Failed to delete campaign: ' . $e->getMessage());
    //         Log::error('Campaign cancellation error: ' . $e->getMessage(), [
    //             'campaign_id' => $this->campaignToDeleteId,
    //             'user_urn' => user()->urn ?? 'unknown',
    //         ]);
    //     }
    // }

    // public $activeMainTab = 'all';

    // public function setActiveTab($tab)
    // {
    //     $this->activeMainTab = $tab;
    //     $this->refreshCampaigns();
    // }

    public function refreshCampaigns()
    {
        try {
            if ($this->activeMainTab == 'all') {
                $this->campaigns = Campaign::with(['music'])
                    ->where('user_urn', user()->urn)
                    ->latest()
                    ->get();
            } elseif ($this->activeMainTab == 'active') {
                $this->campaigns = Campaign::with(['music'])
                    ->where('user_urn', user()->urn)
                    ->Open()
                    ->latest()
                    ->get();
            } elseif ($this->activeMainTab == 'completed') {
                $this->campaigns = Campaign::with(['music'])
                    ->where('user_urn', user()->urn)
                    ->Completed()
                    ->latest()
                    ->get();
            }
        } catch (\Exception $e) {
            $this->campaigns = collect();
            session()->flash('error', 'Failed to refresh campaigns: ' . $e->getMessage());
        }
    }
    public function getFaqs($categoryId = null)
    {
        return Faq::when($categoryId, function ($query) use ($categoryId) {
            $query->where('faq_category_id', $categoryId);
        })->get();
    }


    public function mount($categoryId = null)
    {
        $this->refreshCampaigns();
        $this->faqs = $this->getFaqs($categoryId);
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
