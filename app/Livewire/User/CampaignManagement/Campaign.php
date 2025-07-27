<?php

namespace App\Livewire\User\CampaignManagement;

use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;

class Campaign extends Component
{
    protected CampaignService $campaignService;
    public $featuredCampaigns;
    public $campaigns;

    public function mount(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
        // $this->featuredCampaigns = $this->campaignService->getCampaigns()
        //     ->featured()
        //     ->withoutSelf()
        //     ->with(['music'])
        //     ->get();

        $this->campaigns = $this->campaignService->getCampaigns()
            ->notFeatured()
            // ->withoutSelf()
            ->with(['music'])
            ->get();
    }






    public function render()
    {
        return view('backend.user.campaign_management.campaigns.campaign');
    }
}
