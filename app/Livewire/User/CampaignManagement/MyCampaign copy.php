<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MyCampaign extends Component
{
    public $campaigns;

    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
    public bool $showLowCreditWarningModal = false;
    // {{-- NEW FUNCTIONALITY --}}
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showDeleteWarningModal = false;
    // {{-- END NEW FUNCTIONALITY --}}

    public string $activeMainTab = 'all';
    public string $activeModalTab = 'tracks';

    public $tracks = [];
    public $playlists = [];

    #[Locked]
    public $playlistId = null;
    public $playlistTracks = [];

    #[Locked]
    public int $minFollowers = 0;
    #[Locked]
    public int $maxFollowers = 0;

    // Input fields for campaign creation
    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $endDate = null;
    public $targetReposts = null;
    public $costPerRepost = null;

    // {{-- NEW FUNCTIONALITY: Properties for Add Credit functionality --}}
    public $addCreditCampaignId = null;
    public $addCreditCostPerRepost = null;
    public $addCreditCurrentBudget = null; // Stored value for display
    public $addCreditTargetReposts = null; // Stored value for budget calculation
    // {{-- END NEW FUNCTIONALITY --}}

    // {{-- NEW FUNCTIONALITY: Properties for Edit functionality --}}
    public $editingCampaignId = null;
    public $editTitle = null;
    public $editDescription = null;
    public $editEndDate = null;
    public $editTargetReposts = null;
    public $editCostPerRepost = null;
    // {{-- END NEW FUNCTIONALITY --}}

    // {{-- NEW FUNCTIONALITY: Properties for Delete functionality --}}
    public $campaignToDeleteId = null;
    public $refundAmount = 0;
    // {{-- END NEW FUNCTIONALITY --}}

    protected $listeners = ['campaignCreated' => 'refreshCampaigns'];

    public function mount()
    {
        $this->refreshCampaigns();
    }

    /**
     * Define validation rules
     */
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'endDate' => 'required|date|after_or_equal:today',
            'targetReposts' => 'required|integer|min:1',
            'costPerRepost' => 'required|integer|min:1',
            'musicId' => 'required|integer',
        ];

        // {{-- NEW FUNCTIONALITY: Rules for editing a campaign --}}
        if ($this->showEditCampaignModal) {
            $rules = [
                'editTitle' => 'required|string|max:255',
                'editDescription' => 'required|string|max:1000',
                'editEndDate' => 'required|date|after_or_equal:today',
                'editTargetReposts' => 'required|integer|min:1',
                'editCostPerRepost' => 'required|integer|min:1',
            ];
            // Remove original campaign creation rules as they are not needed for edit validation
            unset($rules['title'], $rules['description'], $rules['endDate'], $rules['targetReposts'], $rules['costPerRepost'], $rules['musicId']);
        }
        // {{-- END NEW FUNCTIONALITY --}}

        // {{-- NEW FUNCTIONALITY: Rules for adding credits (only cost per repost is editable in this context) --}}
        if ($this->showAddCreditModal) {
            $rules = [
                'addCreditCostPerRepost' => 'required|integer|min:1',
            ];
        }
        // {{-- END NEW FUNCTIONALITY --}}

        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'musicId.required' => 'Please select a track for your campaign.',
            'title.required' => 'Campaign name is required.',
            'title.max' => 'Campaign name cannot exceed 255 characters.',
            'description.required' => 'Campaign description is required.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'endDate.required' => 'Please select an expiration date.',
            'endDate.after_or_equal' => 'Expiration date must be today or later.',
            'targetReposts.required' => 'Target repost count is required.',
            'targetReposts.min' => 'Target reposts must be at least 1.',
            'costPerRepost.required' => 'Cost per repost is required.',
            'costPerRepost.min' => 'Cost per repost must be at least 1 credit.',
            // {{-- NEW FUNCTIONALITY: Edit specific messages --}}
            'editTitle.required' => 'Campaign name is required.',
            'editDescription.required' => 'Campaign description is required.',
            'editEndDate.required' => 'Campaign expiration date is required.',
            'editTargetReposts.required' => 'Target repost count is required.',
            'editCostPerRepost.required' => 'Cost per repost is required.',
            // {{-- END NEW FUNCTIONALITY --}}
            // {{-- NEW FUNCTIONALITY: Add credit specific messages --}}
            'addCreditCostPerRepost.required' => 'Cost per repost is required.',
            'addCreditCostPerRepost.min' => 'Cost per repost must be at least 1 credit.',
            // {{-- END NEW FUNCTIONALITY --}}
        ];
    }

    public function refreshCampaigns()
    {
        if ($this->activeMainTab === 'all') {
            $this->campaigns = Campaign::self()->latest()->get();
        } elseif ($this->activeMainTab === 'active') {
            $this->campaigns = Campaign::self()->active_completed()->latest()->get();
        } elseif ($this->activeMainTab === 'completed') {
            $this->campaigns = Campaign::self()->completed()->latest()->get();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeMainTab = $tab;
        $this->refreshCampaigns();
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
            // {{-- NEW FUNCTIONALITY: Reset new modals and their data --}}
            'showAddCreditModal',
            'showEditCampaignModal',
            'showDeleteWarningModal',
            'addCreditCostPerRepost',
            'addCreditCampaignId',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'campaignToDeleteId',
            'refundAmount',
            // {{-- END NEW FUNCTIONALITY --}}
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
            // {{-- NEW FUNCTIONALITY: Reset new modals and their data --}}
            'showAddCreditModal',
            'addCreditCampaignId',
            'addCreditCostPerRepost',
            'addCreditCurrentBudget',
            'addCreditTargetReposts',
            'showEditCampaignModal',
            'editingCampaignId',
            'editTitle',
            'editDescription',
            'editEndDate',
            'editTargetReposts',
            'editCostPerRepost',
            'showDeleteWarningModal',
            'campaignToDeleteId',
            'refundAmount',
            // {{-- END NEW FUNCTIONALITY --}}
        ]);

        // Explicitly close the campaign selection modal when opening the submit modal
        $this->showCampaignsModal = false; // Ensures the first modal closes

        // Simulate user credits for demonstration
        $userCredits = user()->credits;
        if ($userCredits < 50) {
            $this->showLowCreditWarningModal = true;
            $this->showSubmitModal = false;
            return;
        } else {
            $this->showLowCreditWarningModal = false;
        }

        $this->showSubmitModal = true;
        try {
            if ($type === 'track') {
                $track = Track::findOrFail($id);
                if (!$track->urn || !$track->title) {
                    throw new \Exception('Track data is incomplete');
                }
                $this->musicId = $track->id;
                $this->musicType = Track::class;
                $this->title = $track->title . ' Campaign';
            } elseif ($type === 'playlist') {
                $playlist = Playlist::findOrFail($id);
                if (!$playlist->title) {
                    throw new \Exception('Playlist data is incomplete');
                }
                $this->playlistId = $playlist->id;
                $this->fetchPlaylistTracks();
                $this->musicType = Playlist::class;
                $this->title = $playlist->title . ' Campaign';
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to prepare campaign: ' . $e->getMessage());
            $this->showSubmitModal = false;
        }
    }

    public function submitCampaign()
    {
        $this->validate();

        try {
            $budgetCredits = $this->costPerRepost * $this->targetReposts;

            $user = user();
            if ($user->credits < $budgetCredits) {
                session()->flash('error', 'You do not have enough credits to create this campaign. You need ' . $budgetCredits . ' credits.');
                $this->showSubmitModal = false;
                $this->showLowCreditWarningModal = true;
                return;
            }

            $campaign = Campaign::create([
                'user_urn' => user()->urn,
                'music_id' => $this->musicId,
                'music_type' => $this->musicType,
                'title' => $this->title,
                'description' => $this->description,
                'target_reposts' => $this->targetReposts,
                'completed_reposts' => 0,
                'cost_per_repost' => $this->costPerRepost,
                'budget_credits' => $budgetCredits,
                'credits_spent' => 0,
                'min_followers' => $this->minFollowers,
                'max_followers' => $this->maxFollowers,
                'is_featured' => false,
                'status' => Campaign::STATUS_OPEN,
                'start_date' => now(),
                'end_date' => $this->endDate,
                'auto_approve' => Campaign::AUTO_APPROVE_NO,
                'creater_id' => user()->id,
                'creater_type' => get_class(user()),
            ]);

            $user->decrement('credits', $budgetCredits);

            session()->flash('success', 'Campaign created successfully!');
            $this->resetForm();
            $this->showSubmitModal = false;
            $this->refreshCampaigns();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create campaign: ' . $e->getMessage());
            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'user_urn' => user()->urn ?? 'unknown',
                'music_id' => $this->musicId,
                'music_type' => $this->musicType,
                'title' => $this->title,
                'description' => $this->description,
                'target_reposts' => $this->targetReposts,
                'cost_per_repost' => $this->costPerRepost,
                'endDate' => $this->endDate,
            ]);
        }
    }

    private function resetForm()
    {
        $this->reset([
            'musicId',
            'musicType',
            'title',
            'description',
            'endDate',
            'targetReposts',
            'costPerRepost',
            'minFollowers',
            'maxFollowers',
            'playlistId',
            'playlistTracks',
            'activeModalTab'
        ]);
        $this->resetValidation();
        $this->resetErrorBag();
    }


    // {{-- NEW FUNCTIONALITY: Methods for Add Credit functionality --}}
    public function openAddCreditModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->addCreditCampaignId = $campaign->id;
        $this->addCreditCostPerRepost = $campaign->cost_per_repost;
        $this->addCreditCurrentBudget = $campaign->budget_credits;
        $this->addCreditTargetReposts = $campaign->target_reposts;
        $this->showAddCreditModal = true;
        // Close other modals if they are open
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showEditCampaignModal = false;
        $this->showDeleteWarningModal = false;
    }

    public function addCreditsToCampaign()
    {
        $this->validate([
            'addCreditCostPerRepost' => 'required|numeric|min:1',
        ]);

        try {
            $campaign = Campaign::findOrFail($this->addCreditCampaignId);

            $oldTotalBudget = $campaign->budget_credits;
            $newTargetBudget = $this->addCreditCostPerRepost * $campaign->target_reposts;

            $creditsNeeded = $newTargetBudget - $oldTotalBudget;

            if ($creditsNeeded < 0) {
                session()->flash('warning', 'Reducing the cost per repost will not add credits. Please adjust your campaign via the "Edit" function if you wish to change the cost per repost without adding credits.');
                $this->showAddCreditModal = false;
                $this->refreshCampaigns();
                return;
            }

            $user = user();
            if ($creditsNeeded > 0 && $user->credits < $creditsNeeded) {
                session()->flash('error', 'You need ' . $creditsNeeded . ' more credits to update this campaign budget.');
                $this->showLowCreditWarningModal = true;
                $this->showAddCreditModal = false;
                return;
            }

            $campaign->cost_per_repost = $this->addCreditCostPerRepost;
            $campaign->budget_credits = $newTargetBudget;
            $campaign->save();

            if ($creditsNeeded > 0) {
                $user->decrement('credits', $creditsNeeded);
            }

            session()->flash('success', 'Campaign budget updated successfully!');
            $this->showAddCreditModal = false;
            $this->refreshCampaigns();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add credits: ' . $e->getMessage());
            Log::error('Add credit error: ' . $e->getMessage(), [
                'campaign_id' => $this->addCreditCampaignId,
                'user_urn' => user()->urn ?? 'unknown',
            ]);
        }
    }
    // {{-- END NEW FUNCTIONALITY --}}


    // {{-- NEW FUNCTIONALITY: Methods for Edit functionality --}}
    public function openEditCampaignModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->editingCampaignId = $campaign->id;
        $this->editTitle = $campaign->title;
        $this->editDescription = $campaign->description;
        $this->editEndDate = $campaign->end_date->format('Y-m-d');
        $this->editTargetReposts = $campaign->target_reposts;
        $this->editCostPerRepost = $campaign->cost_per_repost;
        $this->showEditCampaignModal = true;
        // Close other modals if they are open
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showAddCreditModal = false;
        $this->showDeleteWarningModal = false;
    }

    public function updateCampaign()
    {
        $this->validate(); // Uses the conditional rules defined in rules() method

        try {
            $campaign = Campaign::findOrFail($this->editingCampaignId);

            $oldBudgetCredits = $campaign->budget_credits;
            $newBudgetCredits = $this->editCostPerRepost * $this->editTargetReposts;

            $creditDifference = $newBudgetCredits - $oldBudgetCredits;

            $user = user();
            if ($creditDifference > 0 && $user->credits < $creditDifference) {
                session()->flash('error', 'You need ' . $creditDifference . ' more credits to update this campaign budget.');
                $this->showLowCreditWarningModal = true;
                $this->showEditCampaignModal = false;
                return;
            }

            // Update campaign attributes
            $campaign->title = $this->editTitle;
            $campaign->description = $this->editDescription;
            $campaign->end_date = $this->editEndDate;
            $campaign->target_reposts = $this->editTargetReposts;
            $campaign->cost_per_repost = $this->editCostPerRepost;
            $campaign->budget_credits = $newBudgetCredits;
            $campaign->updater_id = user()->id;
            $campaign->updater_type = get_class(user());
            $campaign->save();

            // Adjust user credits if budget changed
            if ($creditDifference > 0) {
                $user->decrement('credits', $creditDifference);
            } elseif ($creditDifference < 0) {
                $user->increment('credits', abs($creditDifference));
            }

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
    // {{-- END NEW FUNCTIONALITY --}}

    // {{-- NEW FUNCTIONALITY: Methods for Delete functionality --}}
    public function openDeleteWarningModal(Campaign $campaign)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->campaignToDeleteId = $campaign->id;

        // Calculate remaining budget
        $remainingBudget = $campaign->budget_credits - $campaign->credits_spent;
        // Calculate 50% refund of remaining budget
        $this->refundAmount = floor($remainingBudget * 0.5);
        $this->showDeleteWarningModal = true;
        // Close other modals if they are open
        $this->showSubmitModal = false;
        $this->showCampaignsModal = false;
        $this->showAddCreditModal = false;
        $this->showEditCampaignModal = false;
    }

    public function deleteCampaign()
    {
        try {
            $campaign = Campaign::findOrFail($this->campaignToDeleteId);

            // Refund 50% of the remaining budget
            $user = user();
            if ($this->refundAmount > 0) {
                $user->increment('credits', $this->refundAmount);
            }

            $campaign->delete();

            session()->flash('success', 'Campaign deleted successfully! ' . $this->refundAmount . ' credits refunded.');
            $this->showDeleteWarningModal = false;
            $this->refreshCampaigns();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete campaign: ' . $e->getMessage());
            Log::error('Campaign deletion error: ' . $e->getMessage(), [
                'campaign_id' => $this->campaignToDeleteId,
                'user_urn' => user()->urn ?? 'unknown',
            ]);
        }
    }
    // {{-- END NEW FUNCTIONALITY --}}
    public function render()
    {
        return view('backend.user.campaign_management.campaigns.my_campaigns');
    }
}
