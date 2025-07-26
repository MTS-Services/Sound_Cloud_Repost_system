<?php

namespace App\Livewire\Backend\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use App\Models\Playlist;
use Livewire\Component;

class MyCampaign extends Component
{
    public $campaigns;
    public $activeMainTab = 'all';

    public bool $showCampaignsModal = false;
    public string $activeModalTab = 'tracks';

    public $tracks = [];
    public $playlists = [];

    public function toggleCampaignsModal()
    {
        $this->showCampaignsModal = !$this->showCampaignsModal;
        $this->activeModalTab = 'tracks';
        $this->selectModalTab($this->activeModalTab);
    }

    public function selectModalTab($tab)
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
