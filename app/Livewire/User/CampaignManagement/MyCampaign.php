<?php

namespace App\Livewire\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MyCampaign extends Component
{
    public $campaigns;
    public $activeMainTab = 'all'; // Default active tab for campaign list

    public bool $showCampaignsModal = false;
    public bool $showSubmitModal = false;
    public string $activeModalTab = 'tracks';

    public $tracks = [];
    public $playlists = [];

    #[Locked]
    public $playlistId = null;
    public $playlistTracks = [];

    #[Locked]
    public $minFollowers = 0;
    #[Locked]
    public $maxFollowers = 0;

    // Input fields for campaign creation
    #[Validate('required')]
    public $trackUrn = null;
    #[Validate('required|string|max:255')]
    public $title = null;
    #[Validate('required|string|max:1000')]
    public $description = null;
    #[Validate('required|date|after_or_equal:today')]
    public $endDate = null;
    #[Validate('required|integer|min:1')]
    public $targetReposts = null;
    #[Validate('required|integer|min:1')]
    public $totalBudget = null;

    protected $listeners = ['campaignCreated' => 'refreshCampaigns'];


    public function toggleCampaignsModal()
    {
        $this->reset(['activeModalTab', 'tracks', 'playlists', 'playlistId', 'playlistTracks', 'trackUrn', 'title', 'description', 'endDate', 'targetReposts', 'totalBudget']);
        $this->showCampaignsModal = !$this->showCampaignsModal;
        $this->selectModalTab($this->activeModalTab);
    }

    public function selectModalTab($tab = 'tracks')
    {
        $this->activeModalTab = $tab;
        if ($tab == 'tracks') {
            $this->fetchTracks();
        } else if ($tab == 'playlists') {
            $this->fetchPlaylists();
        }
    }

    public function fetchTracks()
    {
        $this->tracks = Track::where('user_urn', user()->urn)->latest()->get();
    }

    public function fetchPlaylists()
    {
        $this->playlists = Playlist::where('user_urn', user()->urn)->latest()->get();
    }

    public function fetchPlaylistTracks()
    {
        if ($this->playlistId) {
            $playlist = Playlist::findOrFail($this->playlistId);
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . user()->token,
            ])->get('https://api.soundcloud.com/playlists/' . $playlist->soundcloud_urn . '/tracks');

            if ($response->successful()) {
                $this->playlistTracks = $response->json();
            } else {
                $this->playlistTracks = [];
                session()->flash('error', 'Failed to load playlist tracks from SoundCloud.');
            }
        } else {
            $this->playlistTracks = [];
        }
    }

    public function toggleSubmitModal($type, $id)
    {
        $this->resetValidation();
        $this->reset(['title', 'description', 'endDate', 'targetReposts', 'totalBudget']);
        $this->showCampaignsModal = false;
        $this->showSubmitModal = true;

        if ($type === 'track') {
            $track = Track::findOrFail($id);
            $this->trackUrn = $track->urn;
            $this->title = $track->title . ' Campaign';
        } elseif ($type === 'playlist') {
            $this->playlistId = $id;
            $this->fetchPlaylistTracks();
            $playlist = Playlist::findOrFail($id);
            $this->title = $playlist->title . ' Campaign';
        }
    }

    public function submitCampaign()
    {
        $rules = $this->rules();
        if ($this->activeModalTab === 'playlists') {
            $rules['trackUrn'] = ['required', 'string'];
        }
        $this->validate($rules);

        try {

            $creditsPerRepost = ($this->totalBudget / $this->targetReposts);
            if ($creditsPerRepost >= 1) {
                $this->minFollowers = $creditsPerRepost * 100;
                $this->maxFollowers = $this->minFollowers + 99;
            }

            Campaign::create([
                'track_urn' => $this->trackUrn,
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
            $this->showSubmitModal = false;
            $this->reset(['trackUrn', 'title', 'description', 'endDate', 'targetReposts', 'totalBudget', 'playlistId', 'playlistTracks']); // Clear form and modal state
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create campaign: ' . $e->getMessage());
        }
    }

    public function refreshCampaigns()
    {
        $this->campaigns = Campaign::with(['track'])
            ->where('user_urn', user()->urn)
            ->latest()
            ->get();
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
