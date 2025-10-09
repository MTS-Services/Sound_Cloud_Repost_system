<?php

namespace App\Livewire\User\CampaignManagement;

use App\Events\UserNotificationSent;
use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\Faq;
use App\Models\Track;
use App\Models\Playlist;
use App\Services\Admin\UserManagement\UserService;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\TrackService;
use App\Services\User\CampaignManagement\MyCampaignService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class MyCampaign extends Component
{
    use WithPagination;

    protected TrackService $trackService;
    protected UserService $userService;
    protected SoundCloudService $soundCloudService;

    protected MyCampaignService $myCampaignService;

    public $baseUrl = 'https://soundcloud.com/';

    // Pagination URL parameters
    #[Url(as: 'allPage')]
    public ?int $allPage = 1;
    public $faqs;

    #[Url(as: 'activePage')]
    public ?int $activePage = 1;

    #[Url(as: 'completedPage')]
    public ?int $completedPage = 1;

    // Main tab state
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
    public $campaign = null;

    // Form fields - Campaign Creation
    #[Locked]
    public $playlistId = null;
    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $track = null;
    public int $credit = 50;
    public array $genres = [];
    public bool $commentable = true;
    public bool $likeable = true;
    public bool $proFeatureEnabled = false;
    public $maxFollower = 1000;
    public $followersLimit = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $targetGenre = 'anyGenre';
    public $user = null;
    public $showOptions = false;
    public $maxFollowerEnabled = false;
    public $repostPerDayEnabled = false;
    public $trackLimit = 4;
    public $playlistLimit = 4;
    public $playlistTrackLimit = 4;

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

    // Campaign edit
    public $isEditing = false;
    public $editingCampaign = null;

    // Search and pagination properties
    #[Url(as: 'q', except: '')]
    public string $searchQuery = '';
    public $selectedPlaylistId = null;
    public $allTracks;
    public $allPlaylists;
    public $allPlaylistTracks;
    public $perPage = 4;
    public $tracksPage = 1;
    public $playlistsPage = 1;
    public $hasMoreTracks = false;
    public $hasMorePlaylists = false;
    public $playListTrackShow = false;

    // Constants
    private const MIN_BUDGET = 50;
    private const MIN_CREDIT = 50;
    private const REFUND_PERCENTAGE = 0.5;
    private const ITEMS_PER_PAGE = 10;
    private const SOUNDCLOUD_API_URL = 'https://api-v2.soundcloud.com';

    public function boot(TrackService $trackService, UserService $userService, SoundCloudService $soundCloudService, MyCampaignService $myCampaignService): void
    {
        $this->trackService = $trackService;
        $this->userService = $userService;
        $this->soundCloudService = $soundCloudService;
        $this->myCampaignService = $myCampaignService;
        $this->tracks = collect();
        $this->playlists = collect();
    }

    protected function rules(): array
    {
        return [
            'credit' => [
                'required',
                'integer',
                'min:50',
                function ($attribute, $value, $fail) {
                    if ($this->isEditing) {
                        if ($value - $this->editingCampaign->budget_credits > userCredits()) {
                            $fail('The credit is not available.');
                        }
                    } elseif ($value > userCredits()) {
                        $fail('The credit is not available.');
                    }
                    if ($this->isEditing && $this->editingCampaign->budget_credits > $value) {
                        $fail('The Credit is No Decrease.');
                    }
                },
            ],
        ];
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
        // $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $budgetValidationFields = ['costPerRepost', 'targetReposts', 'editCostPerRepost', 'editTargetReposts'];

        if (in_array($propertyName, $budgetValidationFields)) {
            $this->validateBudget($propertyName);
        }

        if ($propertyName === 'addCreditCostPerRepost') {
            $this->validateAddCreditBudget();
        }

        if (in_array($propertyName, ['credit', 'likeable', 'commentable'])) {
            $this->calculateFollowersLimit();
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

    // public function setActiveTab($tab = 'all'): void
    // {
    //     $this->activeMainTab = $tab;
    //     $this->resetPage('allPage');
    //     $this->resetPage('activePage');
    //     $this->resetPage('completedPage');
    // }
    public function updatedActiveMainTab()
    {
        return $this->redirect(route('user.cm.my-campaigns') . '?tab=' . $this->activeMainTab, navigate: true);
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
        return !empty($this->title) && !empty($this->description) && !empty($this->musicId);
    }

    private function isAllEditFieldsFilled(): bool
    {
        return !empty($this->editTitle) && !empty($this->editDescription) && !empty($this->editEndDate);
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

    // public function setactiveModalTab(string $tab): void
    // {
    //     $this->activeMainTab = $tab;

    //     match ($tab) {
    //         'all' => $this->resetPage('allPage'),
    //         'active' => $this->resetPage('activePage'),
    //         'completed' => $this->resetPage('completedPage'),
    //         default => $this->resetPage('allPage')
    //     };
    // }

    public function toggleCampaignsModal()
    {
        if (!is_email_verified()) {
            $this->dispatch('alert', type: 'error', message: 'Please verify your email to create a campaign.');
            return;
        }
        // $this->resetAllFormData();
        if ($this->myCampaignService->thisMonthCampaignsCount() >= (int) userFeatures()[Feature::KEY_SIMULTANEOUS_CAMPAIGNS]) {
            return $this->dispatch('alert', type: 'error', message: 'You have reached the maximum number of campaigns for this month.');
        }
        $this->showCampaignsModal = !$this->showCampaignsModal;

        if ($this->showCampaignsModal) {
            $this->selectModalTab('tracks');
        }
    }

    public function selectModalTab(string $tab = 'tracks'): void
    {
        $this->activeModalTab = $tab;
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
            $this->soundCloudService->syncUserTracks(user(), []);
            $this->allTracks = Track::self()->latest()->get();
            $this->tracksPage = 1;
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->resetTrackCollections();
            $this->handleError('Failed to load tracks', $e);
        }
    }

    public function fetchPlaylists(): void
    {
        try {
            $this->soundCloudService->syncUserPlaylists(user());
            $this->allPlaylists = Playlist::self()->latest()->get();
            $this->playlistsPage = 1;
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        } catch (\Exception $e) {
            $this->resetPlaylistCollections();
            $this->handleError('Failed to load playlists', $e);
        }
    }

    public function toggleSubmitModal(string $type, int $id): void
    {
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
        $this->musicId = null;
    }

    private function handleSubmissionError(\Exception $e, string $type, int $id): void
    {
        $this->dispatch('alert', type: 'error', message: 'Failed to load content: ' . $e->getMessage());
        $this->showSubmitModal = false;
        $this->showCampaignsModal = true;

        Log::error('Toggle submit modal error: ' . $e->getMessage(), [
            'type' => $type,
            'id' => $id,
            'user_urn' => user()->urn ?? 'unknown'
        ]);
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
            $musicId = $this->musicType === Track::class ? $this->musicId : $this->playlistId;
            $oldBudget = 0;
            if ($this->isEditing) {
                $oldBudget = $this->editingCampaign->budget_credits + $this->editingCampaign->momentum_price;
            }
            DB::transaction(function () use ($oldBudget, $musicId) {
                $commentable = $this->commentable ? 1 : 0;
                $likeable = $this->likeable ? 1 : 0;
                $proFeatureEnabled = $this->proFeatureEnabled && proUser() ? 1 : 0;
                $editingProFeature = $this->isEditing && $this->editingCampaign->pro_feature == 1 ? $this->editingCampaign->pro_feature : $proFeatureEnabled;

                $campaignData = [
                    'music_id' => $musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'budget_credits' => $this->credit,
                    'user_urn' => user()->urn,
                    'status' => Campaign::STATUS_OPEN,
                    'max_followers' => $this->maxFollowerEnabled ? $this->maxFollower : null,
                    'creater_id' => user()->id,
                    'creater_type' => get_class(user()),
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $editingProFeature,
                    'momentum_price' => $editingProFeature == 1 ? $this->credit / 2 : 0,
                    'max_repost_per_day' => $this->repostPerDayEnabled ? $this->maxRepostsPerDay : 0,
                    'max_repost_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ];

                if (!$this->isEditing) {
                    $campaign = Campaign::create($campaignData);
                } else {
                    $this->editingCampaign->update($campaignData);
                    $campaign = $this->editingCampaign;
                }

                $calculation = $campaign->budget_credits + $campaign->momentum_price;

                if (($this->isEditing && $calculation > $oldBudget) || !$this->isEditing) {
                    $transaction = CreditTransaction::create([
                        'receiver_urn' => user()->urn,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                        'source_id' => $campaign->id,
                        'source_type' => Campaign::class,
                        'transaction_type' => CreditTransaction::TYPE_SPEND,
                        'status' => CreditTransaction::STATUS_SUCCEEDED,
                        'credits' => $calculation - $oldBudget,
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
                    $campaign->load('music.user');
                    $data = [];
                    if ($this->isEditing && $calculation > $oldBudget) {
                        $data['Appended Budget'] = $calculation - $oldBudget;
                    }
                    $notification = CustomNotification::create([
                        'receiver_id' => user()->id,
                        'receiver_type' => get_class(user()),
                        'type' => CustomNotification::TYPE_USER,
                        'url' => route('user.cm.my-campaigns'),
                        'message_data' => [
                            'title' => ($this->isEditing ? 'Campaign updated.' : 'New campaign created.'),
                            'message' => 'Your campaign has been ' . ($this->isEditing ? 'updated' : 'created') . ' successfully',
                            'description' => 'Your campaign has been ' . ($this->isEditing ? 'updated' : 'created') . ' successfully',
                            'icon' => 'audio-lines',
                            'additional_data' => [
                                'Track Title' => $campaign->music->title,
                                'Track Artist' => $campaign->music->user->name ?? 'Unknown Artist',
                                'Total Budget' => $calculation,

                                'Moentum' => $campaign->momentum_price > 0 ? 'Enabled' : 'Disabled',
                                'Exclude frequent reposters (24h)' => $campaign->max_repost_last_24_h > 0 ? $campaign->max_repost_last_24_h : 'Not Applicable',
                                'Limit max followers count' => $campaign->max_followers > 0 ? $campaign->max_followers : 'Not Applicable',
                                'Limit avg repost per day' => $campaign->max_repost_per_day > 0 ? $campaign->max_repost_per_day : 'Not Applicable',
                                'Target Genre' => $campaign->target_genre ?? 'N/A',
                            ] + $data
                        ]
                    ]);
                    broadcast(new UserNotificationSent($notification));
                }
            });

            $this->dispatch('alert', type: 'success', message: 'Campaign ' . ($this->isEditing ? 'updated' : 'created') . ' successfully!');
            $this->resetAfterCampaignCreation();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Failed to create campaign: ' . $e->getMessage());
            $this->logCampaignError($e);
        }
    }

    public function editCampaign($userId)
    {
        $this->editingCampaign = Campaign::where('id', $userId)
            ->where('creater_id', user()->id)
            ->open()
            ->first();

        if (!$this->editingCampaign) {
            $this->dispatch('alert', type: 'error', message: 'Campaign not found or cannot be edited.');
            return;
        }

        $this->loadCampaignData();
        $this->isEditing = true;
        $this->showSubmitModal = true;
    }

    private function loadCampaignData()
    {
        if (!$this->editingCampaign)
            return;

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
        $this->loadTrackData();
    }

    private function loadTrackData()
    {
        if ($this->musicType === Track::class && $this->musicId) {
            $this->track = Track::find($this->musicId);
        }
    }

    public function openViewDetailsModal(int $id): void
    {
        $this->campaign = Campaign::with(['music', 'user'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeViewDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->campaign = null;
        $this->resetFormValidation();
    }

    public function searchSoundcloud()
    {
        if (empty($this->searchQuery)) {
            $this->resetSearchData();
            return;
        }
        if ($this->isSoundCloudUrl($this->searchQuery)) {
            if (proUser()) {
                $this->resolveSoundcloudUrl();
            } else {
                $this->dispatch('alert', type: 'error', message: 'Please upgrade to a Pro User to use this feature.');
            }
            return;
        }

        $this->performLocalSearch();
    }

    private function isSoundCloudUrl(string $query): bool
    {
        return preg_match('/^https?:\/\/(www\.)?soundcloud\.com\/[a-zA-Z0-9\-_]+(\/[a-zA-Z0-9\-_]+)*(\/)?(\?.*)?$/i', $query);
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
            $this->allTracks = Track::where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            })
                ->get();
            $this->tracks = $this->allTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allTracks->count() > $this->perPage;
        } elseif ($this->activeModalTab === 'playlists') {
            $this->allPlaylists = Playlist::where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('permalink_url', 'like', '%' . $this->searchQuery . '%');
            })
                ->get();
            $this->playlists = $this->allPlaylists->take($this->perPage);
            $this->hasMorePlaylists = $this->allPlaylists->count() > $this->perPage;
        }
    }

    protected function resolveSoundcloudUrl()
    {

        if ($this->playListTrackShow == true && $this->activeModalTab === 'tracks') {
            $baseUrl = strtok($this->searchQuery, '?');
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                ->get();
            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeModalTab == 'tracks') {
                $baseUrl = strtok($this->searchQuery, '?');
                $tracksFromDb = Track::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                    ->get();

                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeModalTab == 'playlists') {
                $baseUrl = strtok($this->searchQuery, '?');
                $playlistsFromDb = Playlist::whereRaw("SUBSTRING_INDEX(permalink_url, '?', 1) = ?", [$baseUrl])
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
            }
        }
        $resolvedData = $this->soundCloudService->makeResolveApiRequest($this->searchQuery, 'Failed to resolve SoundCloud URL');

        if (isset($resolvedData)) {
            $urn = $resolvedData['urn'];
            if ($this->activeModalTab === 'playlists') {
                if (isset($resolvedData['tracks']) && count($resolvedData['tracks']) > 0) {
                    $this->soundCloudService->unknownPlaylistAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Playlist URL.');
                }
            } elseif ($this->activeModalTab === 'tracks') {
                if (!isset($resolvedData['tracks'])) {
                    $this->soundCloudService->unknownTrackAdd($resolvedData);
                    Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
                } else {
                    $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the Track URL.');
                }
            }
            $this->processSearchData($urn);
            Log::info('Resolved SoundCloud URL: ' . "Successfully resolved SoundCloud URL: " . $this->searchQuery);
        } else {
            $this->dispatch('alert', type: 'error', message: 'Could not resolve the SoundCloud link. Please check the URL.');
        }
    }

    protected function processSearchData($urn)
    {
        if ($this->playListTrackShow == true && $this->activeModalTab === 'tracks') {
            $tracksFromDb = Playlist::findOrFail($this->selectedPlaylistId)->tracks()
                ->where('soundcloud_urn', $urn)
                ->get();

            if ($tracksFromDb->isNotEmpty()) {
                $this->allPlaylistTracks = $tracksFromDb;
                $this->tracks = $this->allPlaylistTracks->take($this->playlistTrackLimit);
                $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                return;
            }
        } else {
            if ($this->activeModalTab == 'tracks') {
                $tracksFromDb = Track::where('urn', $urn)
                    ->get();

                if ($tracksFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'tracks';
                    $this->allTracks = $tracksFromDb;
                    $this->tracks = $this->allTracks->take($this->trackLimit);
                    $this->hasMoreTracks = $this->tracks->count() === $this->trackLimit;
                    return;
                }
            }

            if ($this->activeModalTab == 'playlists') {
                $playlistsFromDb = Playlist::where('soundcloud_urn', $urn)
                    ->get();

                if ($playlistsFromDb->isNotEmpty()) {
                    $this->activeModalTab = 'playlists';
                    $this->allPlaylists = $playlistsFromDb;
                    $this->playlists = $this->allPlaylists->take($this->playlistLimit);
                    $this->hasMorePlaylists = $this->playlists->count() === $this->playlistLimit;
                    return;
                }
            }
        }
    }

    private function resetSearchData()
    {
        if ($this->activeModalTab === 'tracks') {
            if ($this->playListTrackShow && $this->selectedPlaylistId) {
                $this->allTracks = Playlist::findOrFail($this->selectedPlaylistId)->tracks()->get();
            } else {
                $this->allTracks = Track::self()->get();
            }
            $this->tracks = $this->allTracks->take($this->perPage);
        } elseif ($this->activeModalTab === 'playlists') {
            $this->allPlaylists = Playlist::self()->get();
            $this->playlists = $this->allPlaylists->take($this->perPage);
        }
    }

    public function showPlaylistTracks($playlistId)
    {
        $this->selectedPlaylistId = $playlistId;
        $playlist = Playlist::with('tracks')->find($playlistId);

        if ($playlist) {
            $this->allPlaylistTracks = $playlist->tracks;
            $this->tracks = $this->allPlaylistTracks->take($this->perPage);
            $this->hasMoreTracks = $this->allPlaylistTracks->count() > $this->perPage;
        } else {
            $this->resetTrackCollections();
        }

        $this->activeModalTab = 'tracks';
        $this->playListTrackShow = true;
        $this->tracksPage = 1;
        $this->searchQuery = '';
    }

    public function loadMoreTracks()
    {
        $this->tracksPage++;
        $sourceCollection = ($this->playListTrackShow) ? $this->allPlaylistTracks : $this->allTracks;
        $startIndex = ($this->tracksPage - 1) * $this->perPage;
        $newTracks = $sourceCollection->slice($startIndex, $this->perPage);
        $this->tracks = $this->tracks->concat($newTracks);
        $this->hasMoreTracks = $newTracks->count() === $this->perPage;
    }

    public function loadMorePlaylists()
    {
        $this->playlistsPage++;
        $startIndex = ($this->playlistsPage - 1) * $this->perPage;
        $newPlaylists = $this->allPlaylists->slice($startIndex, $this->perPage);
        $this->playlists = $this->playlists->concat($newPlaylists);
        $this->hasMorePlaylists = $newPlaylists->count() === $this->perPage;
    }

    private function getCampaignsQuery(): Builder
    {
        return Campaign::with(['music']);
    }

    private function resetAllFormData(): void
    {
        $this->reset([
            'musicId',
            'title',
            'description',
            'playlistId',
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
            'editingCampaign',
            'isEditing',
            'user',
        ]);
        $this->resetValidation();
        $this->resetErrorBag();
    }

    private function resetAfterCampaignCreation(): void
    {
        $this->showCampaignsModal = false;
        $this->showSubmitModal = false;
        $this->resetAllFormData();
        $this->dispatch('campaignCreated');
    }

    private function resetFormValidation(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
    }

    private function resetTrackCollections(): void
    {
        $this->tracks = collect();
        $this->allTracks = collect();
        $this->hasMoreTracks = false;
    }

    private function resetPlaylistCollections(): void
    {
        $this->playlists = collect();
        $this->allPlaylists = collect();
        $this->hasMorePlaylists = false;
    }

    private function handleError(string $message, \Exception $e, array $context = []): void
    {
        $this->dispatch('alert', type: 'error', message: $message . ': ' . $e->getMessage());

        Log::error($message . ': ' . $e->getMessage(), array_merge([
            'user_urn' => user()->urn ?? 'unknown'
        ], $context));
    }

    private function logCampaignError(\Exception $e): void
    {
        Log::error('Campaign creation error: ' . $e->getMessage(), [
            'music_id' => $this->musicId,
            'user_urn' => user()->urn ?? 'unknown',
            'title' => $this->title,
        ]);
    }

    public function setFeatured($id)
    {
        try {
            $campaign = Campaign::find($id);

            if (!$campaign) {
                $this->dispatch('alert', type: 'error', message: 'Campaign not found.');
                return;
            }

            if (featuredAgain() == false) {
                $this->dispatch('alert', type: 'error', message: 'Campaign is already featured previously.');
                return;
            }

            if (featuredAgain() == false) {
                $this->dispatch('alert', type: 'error', message: 'You can feature a campaign only once in 24 hours.');
                return;
            }
            Campaign::self()->featured()->update(['is_featured' => 0]);

            $campaign->is_featured = Campaign::FEATURED;
            $campaign->featured_at = now();
            $campaign->update();
            $this->dispatch('alert', type: 'success', message: 'Campaign featured successfully.');
            $this->mount();
        } catch (\Exception $e) {
            $this->handleError('Failed to feature campaign', $e, ['campaign_id' => $id]);
        }
    }


    public function freeBoost($id)
    {
        try {
            $campaign = Campaign::find($id);
            if (!$campaign) {
                $this->dispatch('alert', type: 'error', message: 'Campaign not found.');
                return;
            }
            if (boostAgain($id) == false) {
                $this->dispatch('alert', type: 'error', message: 'Campaign is already boosted previously.');
                return;
            }

            if (boostAgain($id) == false) {
                $this->dispatch('alert', type: 'error', message: 'You can boost a campaign only once in 24 hours.');
                return;
            }
            Campaign::self()->where('is_boost', 1)->update(['is_boost' => Campaign::NOT_BOOSTED]);

            $campaign->is_boost = Campaign::BOOSTED;
            $campaign->boosted_at = now();
            $campaign->update();
            $this->dispatch('alert', type: 'success', message: 'Campaign boosted successfully.');
            $this->mount();
        } catch (\Exception $e) {
            $this->handleError('Failed to feature campaign', $e, ['campaign_id' => $id]);
        }
    }

    public function mount($categoryId = null)
    {
        // $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $this->activeMainTab = request()->query('tab', 'all');
        $this->resetPage('allPage');
        $this->resetPage('activePage');
        $this->resetPage('completedPage');

        $this->faqs = Faq::when($categoryId, function ($query) use ($categoryId) {
            $query->where('faq_category_id', $categoryId);
        })->get();
        $this->calculateFollowersLimit();
    }

    public function calculateFollowersLimit()
    {
        $this->followersLimit = ($this->credit - ($this->likeable ? 2 : 0) - ($this->commentable ? 2 : 0)) * 100;
    }

    public function stopCampaign($id)
    {
        try {
            $campaign = Campaign::where('id', $id)->orWhere('status', Campaign::STATUS_OPEN)->first();

            if (!$campaign) {
                $this->dispatch('alert', type: 'error', message: 'Campaign not found.');
                return;
            }
            Log::info('Found Campaign for stopped. id: '. $campaign->id);
            DB::transaction(function () use ($campaign) {
                $campaign->status = Campaign::STATUS_STOP;
                $campaign->save();
                $campaign->load('user');    
                $remainingBudget = $campaign->budget_credits - $campaign->credits_spent;
                if ($remainingBudget > 0) {
                    CreditTransaction::create([
                        'receiver_urn' => $campaign->user_urn,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                        'source_id' => $campaign->id,
                        'source_type' => Campaign::class,
                        'transaction_type' => CreditTransaction::TYPE_REFUND,
                        'status' => CreditTransaction::STATUS_REFUNDED,
                        'amount'=> 0,
                        'credits' => $remainingBudget,
                        'description' => 'Refund for stopped campaign',
                        'metadata' => [
                            'campaign_id' => $campaign->id,
                            'music_id' => $campaign->music_id,
                            'music_type' => $campaign->music_type,
                            'start_date' => $campaign->created_at,
                        ],
                    ]);
                }

                $notification = CustomNotification::create([
                    'receiver_id' => $campaign->user->id,
                    'receiver_type' => User::class,
                    'type' => CustomNotification::TYPE_USER,
                    'message_data' => [
                        'title' => 'Campaign stopped.',
                        'message' => 'Your campaign has been stopped successfully.',
                        'description' => 'Your campaign has been stopped successfully.',
                        'icon' => 'audio-lines',
                    ],
                ]);
                broadcast(new UserNotificationSent($notification));
                $this->dispatch('alert', type: 'success', message: 'Campaign stopped successfully.');
                $this->mount();
            });
 
        } catch (\Exception $e) {
            Log::error('failed to stop campaign. error: '. $e->getMessage());
            $this->handleError('Failed to stop campaign', $e, ['campaign_id' => $id]);
            return;
        }
    }

    public function render()
    {
        try {
            $data['campaigns'] = match ($this->activeMainTab) {
                'active' => $this->getCampaignsQuery()
                    ->open()
                    ->latest()
                    ->self()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'activePage', $this->activePage),
                'completed' => $this->getCampaignsQuery()
                    ->completed()
                    ->latest()
                    ->self()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'completedPage', $this->completedPage),
                    
                'cancelled' => $this->getCampaignsQuery()
                    ->cancelled()
                    ->latest()
                    ->self()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'completedPage', $this->completedPage),
                default => $this->getCampaignsQuery()
                    ->latest()
                    ->self()
                    ->paginate(self::ITEMS_PER_PAGE, ['*'], 'allPage', $this->allPage)
            };
            $data['latestCampaign'] = Campaign::with('music')->self()->where('featured_at', null)->latest()->first();
            $data['featuredCampaign'] = Campaign::with('music')->self()->featured()->whereTime('featured_at', "<=", now()->subHours(24))->first();
        } catch (\Exception $e) {
            $data['campaigns'] = collect();
            $this->handleError('Failed to load campaigns', $e);
        }

        return view('livewire.user.campaign-management.my-campaign', $data);
    }
}
