<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\CreditTransaction;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MyCampaignorinal extends Component
{
    public $campaigns;

    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
    public bool $showLowCreditWarningModal = false;
    public string $activeModalTab = 'tracks';

    // {{-- NEW FUNCTIONALITY --}}
    public bool $showAddCreditModal = false;
    public bool $showEditCampaignModal = false;
    public bool $showDeleteWarningModal = false;
    // {{-- END NEW FUNCTIONALITY --}}

    public $tracks = [];
    public $playlists = [];

    #[Locked]
    public $playlistId = null;
    public $playlistTracks = [];

    #[Locked]
    public int $minFollowers = 0;
    #[Locked]
    public int $maxFollowers = 0;

    // Properties for campaign creation
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
            'costPerRepost.required' => 'Budget per repost is required.',
            'costPerRepost.min' => 'Budget per repost must be at least 1 credit.',

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

                // Ensure we have valid track data
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
                $track = Track::findOrFail($id);

                // Ensure track has required data
                if (!$track->urn || !$track->title) {
                    throw new \Exception('Track data is incomplete');
                }

                $this->musicId = $track->id;
                $this->musicType = Track::class;
                $this->title = $track->title . ' Campaign';
            } elseif ($type === 'playlist') {
                $playlist = Playlist::findOrFail($id);

                // Ensure playlist has required data
                if (!$playlist->title) {
                    throw new \Exception('Playlist data is incomplete');
                }

                $this->playlistId = $id;
                $this->title = $playlist->title . ' Campaign';
                $this->musicType = Playlist::class;

                // Fetch playlist tracks with error handling
                $this->fetchPlaylistTracks();

                // Reset trackUrn when switching to playlist mode
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

    public function submitCampaign()
    {
        $this->validate();

        try {
            if (!$this->musicId) {
                throw new \Exception('Please select a track for your campaign.');
            }

            // Calculate credits per repost
            if ($this->costPerRepost <= 0 || $this->targetReposts <= 0) {
                throw new \Exception('Cost per repost and target reposts must be greater than 0.');
            }

            $totalBudget = ($this->costPerRepost * $this->targetReposts);

            if ($this->costPerRepost >= 1) {
                $this->minFollowers = $this->costPerRepost * 100;
                $this->maxFollowers = $this->minFollowers + 99;
            }

            DB::transaction(function () use ($totalBudget) {
                $campaign = Campaign::create([
                    'music_id' => $this->musicId,
                    'music_type' => $this->musicType,
                    'title' => $this->title,
                    'description' => $this->description,
                    'target_reposts' => $this->targetReposts,
                    'cost_per_repost' => $this->costPerRepost,
                    'budget_credits' => $totalBudget,
                    'end_date' => $this->endDate,
                    'user_urn' => user()->urn,
                    'status' => Campaign::STATUS_OPEN,
                    'min_followers' => $this->minFollowers,
                    'max_followers' => $this->maxFollowers,
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
                    ]
                ]);
            });


            session()->flash('message', 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            // Close modal and reset everything
            $this->showCampaignsModal = false;
            $this->showSubmitModal = false;

            // Complete reset of all form and modal state
            $this->reset([
                'musicId',
                'title',
                'description',
                'endDate',
                'targetReposts',
                'costPerRepost',
                'playlistId',
                'playlistTracks',
                'activeModalTab',
                'tracks',
                'playlists',
                'minFollowers',
                'maxFollowers'
            ]);


            $this->resetValidation();
            $this->resetErrorBag();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create campaign: ' . $e->getMessage());

            Log::error('Campaign creation error: ' . $e->getMessage(), [
                'music_id' => $this->musicId,
                'user_urn' => user()->urn ?? 'unknown',
                'title' => $this->title,
                'total_budget' => $totalBudget,
                'target_reposts' => $this->targetReposts
            ]);
        }
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
                session()->flash('warning', 'Campaign budget cannot be reduced.');
                $this->showAddCreditModal = false;
                $this->refreshCampaigns();
                return;
            }

            if ($creditsNeeded > 0 && userCredits() < $creditsNeeded) {
                session()->flash('error', 'You need ' . $creditsNeeded . ' more credits to update this campaign budget.');
                $this->showLowCreditWarningModal = true;
                $this->showAddCreditModal = false;
                return;
            }

            DB::transaction(function () use ($campaign, $newTargetBudget, $creditsNeeded) {
                $campaign->update([
                    'budget_credits' => $newTargetBudget,
                    'cost_per_repost' => $this->addCreditCostPerRepost
                ]);
                if ($creditsNeeded > 0) {
                    CreditTransaction::create([
                        'receiver_urn' => user()->urn,
                        'calculation_type' => CreditTransaction::CALCULATION_TYPE_CREDIT,
                        'source_id' => $campaign->id,
                        'source_type' => Campaign::class,
                        'transaction_type' => CreditTransaction::TYPE_SPEND,
                        'status' => 'succeeded',
                        'credits' => $creditsNeeded,
                        'description' => 'Spent on campaign update for Add Credits',
                        'metadata' => [
                            'campaign_id' => $campaign->id,
                            'start_date' => now(),
                        ]
                    ]);
                }
            });

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
        return view('backend.user.campaign_management.campaigns.my_campaigns');
    }
}
