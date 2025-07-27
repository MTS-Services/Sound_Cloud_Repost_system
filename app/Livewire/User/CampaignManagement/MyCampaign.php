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
    public $activeMainTab = 'all';

    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
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
    public $totalBudget = null;

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
            'totalBudget' => 'required|integer|min:1',
            'musicId' => 'required|integer',
        ];

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
            'totalBudget.required' => 'Total budget is required.',
            'totalBudget.min' => 'Budget must be at least 1 credit.',
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
            'totalBudget',
            'minFollowers',
            'maxFollowers'
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
            'totalBudget'
        ]);

        $this->showCampaignsModal = false;
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
            $this->showCampaignsModal = true; // Go back to selection modal

            Log::error('Toggle submit modal error: ' . $e->getMessage(), [
                'type' => $type,
                'id' => $id,
                'user_urn' => user()->urn ?? 'unknown'
            ]);
        }
    }

    public function submitCampaign()
    {
        // dd('here');
        // $this->validate();

        try {
            // Ensure we have a valid track URN
            if (!$this->musicId) {
                throw new \Exception('Please select a track for your campaign.');
            }

            // Calculate credits per repost
            if ($this->totalBudget <= 0 || $this->targetReposts <= 0) {
                throw new \Exception('Budget and target reposts must be greater than 0.');
            }

            $creditsPerRepost = ($this->totalBudget / $this->targetReposts);

            if ($creditsPerRepost >= 1) {
                $this->minFollowers = $creditsPerRepost * 100;
                $this->maxFollowers = $this->minFollowers + 99;
            }

            Campaign::create([
                'music_id' => $this->musicId,
                'music_type' => $this->musicType,
                'title' => $this->title,
                'description' => $this->description,
                'target_reposts' => $this->targetReposts,
                'cost_per_repost' => $creditsPerRepost,
                'budget_credits' => $this->totalBudget,
                'end_date' => $this->endDate,
                'user_urn' => user()->urn,
                'status' => Campaign::STATUS_OPEN,
                'min_followers' => $this->minFollowers,
                'max_followers' => $this->maxFollowers,
            ]);


            session()->flash('message', 'Campaign created successfully!');
            $this->dispatch('campaignCreated');

            // Close modal and reset everything
            $this->showSubmitModal = false;

            // Complete reset of all form and modal state
            $this->reset([
                'musicId',
                'title',
                'description',
                'endDate',
                'targetReposts',
                'totalBudget',
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
                'total_budget' => $this->totalBudget,
                'target_reposts' => $this->targetReposts
            ]);
        }
    }

    public function updatedActiveMainTab($tab)
    {
        $this->activeMainTab = $tab;
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
                    ->where('status', Campaign::STATUS_OPEN)
                    ->latest()
                    ->get();
            } elseif ($this->activeMainTab == 'completed') {
                $this->campaigns = Campaign::with(['music'])
                    ->where('user_urn', user()->urn)
                    ->where('status', Campaign::STATUS_COMPLETED)
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
        if ($this->activeMainTab == 'active') {
            dd('active');
        } elseif ($this->activeMainTab == 'completed') {
            dd('completed');
        }
        $this->refreshCampaigns();
    }

    public function render()
    {
        return view('backend.user.campaign_management.campaigns.my_campaigns');
    }
}
