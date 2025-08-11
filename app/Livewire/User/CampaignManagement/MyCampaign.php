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
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MyCampaign extends Component
{

    protected TrackService $trackService;

    public $campaigns;
    public $campaign;

    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
    public bool $showLowCreditWarningModal = false;
    public string $activeModalTab = 'tracks';

    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showCancelWarningModal = false;
    public $tracks = [];
    public $playlists = [];
    #[Locked]
    public $playlistId = null;
    public $playlistTracks = [];
    #[Locked]
    public int $minFollowers = 0;
    // #[Locked]
    public int $maxFollowers = 0;
    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $endDate = null;
    public $targetReposts = null;
    public $costPerRepost = null;
    public bool $isCampaignCancelled = false;
    public bool $showAlreadyCancelledModal = false;
    public bool $showDetailsModal = false;

    // Properties for Add Credit functionality
    public $addCreditCampaignId = null;
    public int $addCreditCostPerRepost;
    public $addCreditCurrentBudget = null;
    public $addCreditTargetReposts = null;
    public $addCreditCreditsNeeded = 0; // New property for credits needed calculation

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

    protected $listeners = ['campaignCreated' => 'refreshCampaigns'];

    public $track = null;
    public $credit = 100;
    public $genres = [];
    public $commentable = false;
    public $likeable = false;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 1;
    public $maxFollower = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $anyGenre = '';
    public $trackGenre = '';
    public $targetGenre = '';


    public function boot(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }

    /**
     * Define validation rules
     */
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

    /**
     * Watch for changes in campaign creation form to validate budget
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['costPerRepost', 'targetReposts'])) {
            $this->validateCampaignBudget();
        }

        if (in_array($propertyName, ['editCostPerRepost', 'editTargetReposts'])) {
            $this->validateEditBudget();
        }

        if ($propertyName === 'addCreditCostPerRepost') {
            $this->validateAddCreditBudget();
        }
    }

    /**
     * Validate campaign creation budget
     */
    protected function validateCampaignBudget()
    {
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = false;

        if (!$this->costPerRepost || !$this->targetReposts || $this->costPerRepost <= 0 || $this->targetReposts <= 0) {
            return;
        }

        $totalBudget = $this->costPerRepost * $this->targetReposts;
        $userCredits = userCredits();

        // Check if all required fields are filled
        $allFieldsFilled = !empty($this->title) && !empty($this->description) &&
            !empty($this->endDate) && !empty($this->musicId);

        if ($totalBudget < 50) {
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "Campaign budget must be at least 50 credits.";
            $this->canSubmit = false;
            return;
        }

        if ($totalBudget > $userCredits) {
            $shortage = $totalBudget - $userCredits;
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "You need {$shortage} more credits to create this campaign.";
            $this->canSubmit = false;
        } else if ($allFieldsFilled) {
            $this->canSubmit = true;
        }
    }

    /**
     * Validate edit campaign budget
     */
    protected function validateEditBudget()
    {
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = false;

        if (
            !$this->editCostPerRepost || !$this->editTargetReposts ||
            $this->editCostPerRepost <= 0 || $this->editTargetReposts <= 0
        ) {
            return;
        }

        $newBudget = $this->editCostPerRepost * $this->editTargetReposts;

        // Check if budget is being decreased
        if ($newBudget < $this->editOriginalBudget) {
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "Campaign budget cannot be decreased.";
            $this->canSubmit = false;
            return;
        }

        $creditDifference = $newBudget - $this->editOriginalBudget;
        $userCredits = userCredits();

        // Check if all required fields are filled
        $allFieldsFilled = !empty($this->editTitle) && !empty($this->editDescription) &&
            !empty($this->editEndDate);

        if ($creditDifference > $userCredits) {
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "You need {$creditDifference} more credits to update this campaign.";
            $this->canSubmit = false;
        } else if ($allFieldsFilled) {
            $this->canSubmit = true;
        }
    }

    /**
     * Validate add credit budget
     */
    protected function validateAddCreditBudget()
    {
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = false;

        if (!$this->addCreditCostPerRepost || $this->addCreditCostPerRepost <= 0) {
            return;
        }

        $newBudget = $this->addCreditCostPerRepost * $this->addCreditTargetReposts;

        // Check if budget is being decreased
        if ($newBudget < $this->addCreditCurrentBudget) {
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "Campaign budget cannot be decreased.";
            $this->canSubmit = false;
            return;
        }

        $this->addCreditCreditsNeeded = $newBudget - $this->addCreditCurrentBudget;
        $userCredits = userCredits();

        if ($this->addCreditCreditsNeeded > $userCredits) {
            $this->showBudgetWarning = true;
            $this->budgetWarningMessage = "You need {$this->addCreditCreditsNeeded} more credits to update this campaign.";
            $this->canSubmit = false;
        } else {
            $this->canSubmit = true;
        }
    }

    public function toggleCampaignsModal()
    {
        // Reset all form data and validation
        $this->reset([
            'activeModalTab',
            'tracks',
            'playlists',
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
            'showAddCreditModal',
            'showEditCampaignModal',
            'showCancelWarningModal',
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
            'canSubmit'
        ]);

        $this->resetValidation();
        $this->resetErrorBag();

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
            $this->tracks = Track::where('user_urn', user()->urn)
                ->latest()
                ->get();
        } catch (\Exception $e) {
            $this->tracks = collect();
            session()->flash('error', 'Failed to load tracks: ' . $e->getMessage());
        }
    }

    public function fetchPlaylists()
    {
        try {
            $this->playlists = Playlist::where('user_urn', user()->urn)
                ->latest()
                ->get();
        } catch (\Exception $e) {
            $this->playlists = collect();
            session()->flash('error', 'Failed to load playlists: ' . $e->getMessage());
        }
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

    public function toggleSubmitModal($type, $id)
    {
        $this->resetValidation();
        $this->resetErrorBag();

        // Reset form fields
        $this->reset([
            'title',
            'description',
            'endDate',
            'targetReposts',
            'costPerRepost',
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

        // Check if user has minimum credits
        if (userCredits() < 100) {
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
    public function profeature($isChecked)
    {
        $this->proFeatureEnabled = $isChecked ? true : false;
        $this->proFeatureValue = $isChecked ? 0 : 1;
    }
    public function createCampaign()
    {
        $this->validate();

        try {
            $totalBudget = $this->credit;
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
                $campaign = Campaign::create([
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
                    'commentable' => $commentable,
                    'likeable' => $likeable,
                    'pro_feature' => $proFeatureEnabled,
                    'max_repost_last_24_h' => $this->maxRepostLast24h,
                    'max_repost_per_day' => $this->maxRepostsPerDay,
                    'target_genre' => $this->targetGenre,
                ]);
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

    public function openAlreadyCancelledModal()
    {
        $this->showAlreadyCancelledModal = true;

        // Close other modals
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showEditCampaignModal = false;
        $this->showAddCreditModal = false;
        $this->showCancelWarningModal = false;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Methods for Add Credit functionality
    public function openAddCreditModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        if ($campaign->status === Campaign::STATUS_CANCELLED) {
            $this->openAlreadyCancelledModal();
            return;
        }

        $this->addCreditCampaignId = $campaign->id;
        $this->addCreditCurrentBudget = $campaign->budget_credits;
        $this->addCreditCreditsNeeded = 0;

        // Reset warning states
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = true; // Default to true for add credits

        $this->showAddCreditModal = true;

        // Close other modals
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showEditCampaignModal = false;
        $this->showCancelWarningModal = false;
    }


    public function openViewDetailsModal($id)
    {
        $this->showDetailsModal = true;
        $this->campaign = Campaign::findOrFail($id)->load(['music', 'user']);
        // dd($this->campaign);
        return;
    }

    public function closeViewDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->reset(['campaign']);
        $this->resetValidation();
        $this->resetErrorBag();
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
    public function openEditCampaignModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();

        if ($campaign->status === Campaign::STATUS_CANCELLED) {
            $this->openAlreadyCancelledModal();
            return;
        }

        $this->editingCampaignId = $campaign->id;
        $this->editTitle = $campaign->title;
        $this->editDescription = $campaign->description;
        $this->editEndDate = $campaign->end_date->format('Y-m-d');
        $this->editOriginalBudget = $campaign->budget_credits;

        // Reset warning states
        $this->showBudgetWarning = false;
        $this->budgetWarningMessage = '';
        $this->canSubmit = true; // Default to true for editing

        $this->showEditCampaignModal = true;

        // Close other modals
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showAddCreditModal = false;
        $this->showCancelWarningModal = false;
    }

    public function updateCampaign()
    {
        $this->validate();

        try {
            $campaign = Campaign::findOrFail($this->editingCampaignId);

            $newBudgetCredits = $this->editCostPerRepost * $this->editTargetReposts;
            $creditDifference = $newBudgetCredits - $campaign->budget_credits;

            // Prevent budget decrease
            if ($creditDifference < 0) {
                session()->flash('error', 'Campaign budget cannot be decreased.');
                return;
            }

            // Check if user has enough credits for increase
            if ($creditDifference > 0 && $creditDifference > userCredits()) {
                session()->flash('error', 'You need ' . $creditDifference . ' more credits to update this campaign budget.');
                $this->showLowCreditWarningModal = true;
                $this->showEditCampaignModal = false;
                return;
            }

            DB::transaction(function () use ($campaign, $newBudgetCredits, $creditDifference) {
                $campaign->update([
                    'title' => $this->editTitle,
                    'description' => $this->editDescription,
                    'end_date' => $this->editEndDate,
                    'budget_credits' => $newBudgetCredits,
                    'updater_id' => user()->id,
                    'updater_type' => get_class(user())
                ]);

                // Create credit transaction only if budget increased
                if ($creditDifference > 0) {
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
            });

            session()->flash('success', 'Campaign updated successfully!');
            $this->showEditCampaignModal = false;
            $this->refreshCampaigns();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update campaign: ' . $e->getMessage());
            Log::error('Campaign update error: ' . $e->getMessage(), [
                'campaign_id' => $this->editingCampaignId,
                'user_urn' => user()->urn ?? 'unknown',
            ]);
        }
    }

    // Methods for Delete functionality
    public function openCancelWarningModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();

        if ($campaign->status === Campaign::STATUS_CANCELLED) {
            $this->openAlreadyCancelledModal();
            return;
        }

        $this->campaignToDeleteId = $campaign->id;

        // Calculate remaining budget and 50% refund
        $remainingBudget = $campaign->budget_credits - $campaign->credits_spent;
        $this->refundAmount = floor($remainingBudget * 0.5);

        $this->showCancelWarningModal = true;

        // Close other modals
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showAddCreditModal = false;
        $this->showEditCampaignModal = false;
    }

    public function cancelCampaign()
    {
        try {
            $campaign = Campaign::findOrFail($this->campaignToDeleteId);

            DB::transaction(function () use ($campaign) {
                // Refund 50% of remaining budget if any
                if ($this->refundAmount > 0) {
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

                // update the status of the campaign
                $campaign->update([
                    'status' => Campaign::STATUS_CANCELLED,
                    'refund_credits' => $this->refundAmount,
                    'updater_id' => user()->id,
                    'updater_type' => get_class(user())
                ]);
            });

            session()->flash('success', 'Campaign canceled successfully! ' . number_format($this->refundAmount) . ' credits refunded.');
            $this->showCancelWarningModal = false;
            $this->refreshCampaigns();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete campaign: ' . $e->getMessage());
            Log::error('Campaign cancellation error: ' . $e->getMessage(), [
                'campaign_id' => $this->campaignToDeleteId,
                'user_urn' => user()->urn ?? 'unknown',
            ]);
        }
    }

    public $activeMainTab = 'all';

    public function setActiveTab($tab)
    {
        $this->activeMainTab = $tab;
        $this->refreshCampaigns();
    }

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

    public function mount()
    {
        $this->refreshCampaigns();
    }

    public function render()
    {
        return view('backend.user.campaign_management.my_campaigns');
    }
}
