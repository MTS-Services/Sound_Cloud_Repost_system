<?php

namespace App\Livewire\Backend\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MyCampaign extends Component
{
    public $campaigns;
    public $activeMainTab = 'all';

    public bool $showCampaignsModal = false;
    public string $activeModalTab = 'tracks';

    public $tracks = [];
    public $playlists = [];

    // #[Locked]
    public $playlistId = null;
    public $playlistTracks = [];

    public bool $showSubmitModal = false;

    public function toggleCampaignsModal()
    {
        $this->showCampaignsModal = !$this->showCampaignsModal;
        $this->activeModalTab = 'tracks';
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
        $playlist = Playlist::findorFail($this->playlistId);
        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . user()->token,
        ])->get('https://api.soundcloud.com/playlists/' . $playlist->soundcloud_urn . '/tracks');
        if ($response->successful()) {
            $this->playlistTracks = $response->json();
            dd('success', $response->json());
        } else {
            $this->playlistTracks = [];
            dd('error', $response->json());
        }
    }

    public function toggleSubmitModal($type, $id)
    {
        $this->showCampaignsModal = false;
        $this->showSubmitModal = true;

        if ($type === 'track') {
            dd('track');
        }

        if ($type === 'playlist') {
            $this->playlistId = $id;
            $this->playlistTracks = $this->fetchPlaylistTracks();
            dd('playlist', $this->playlistTracks);
        }
    }


    public function mount()
    {
        $this->campaigns = Campaign::with(['music'])
            ->where('user_urn', user()->urn)
            ->latest()
            ->get();
    }


    public function render()
    {
        return view('backend.user.campaign_management.campaigns.my_campaigns');
    }
}
