<?php

namespace App\Livewire\Backend\User\CampaignManagement;

use App\Models\Campaign;
use App\Models\Track;
use App\Models\Playlist;
use Livewire\Component;
use Illuminate\Support\Facades\Auth; // Import Auth facade for user authentication check

class MyCampaign extends Component
{
    // Properties to hold campaign data and control UI state
    public $campaigns;
    public $activeMainTab = 'all';

    public $showCampaignsModal = false;
    public $showCampaignSubmitModal = false;
    public $showCampaignDetailsModal = false;
    public $campaignDetails = null;

    public $activeModalTab = 'tracks';

    public $tracks = [];
    public $playlists = [];
    public $tracksNotFound = false;
    public $playlistsNotFound = false;


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
