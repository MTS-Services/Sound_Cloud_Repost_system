<?php

namespace App\Livewire\User;

use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Attributes\On;
use Livewire\Component;

class Repost extends Component
{

    public $showRepostActionModal = false;

    public $campaign;

    protected CampaignService $campaignService;
    public function boot(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function render()
    {
        return view('livewire.user.repost');
    }

    #[On('callRepostAction')]
    public function callRepostAction($campaignId)
    {
        $this->showRepostActionModal = true;
        $this->campaign = $this->campaignService->getCampaign($campaignId);
        $this->campaign->load('track', 'user');
    }
}
