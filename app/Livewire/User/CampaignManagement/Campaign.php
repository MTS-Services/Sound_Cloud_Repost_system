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
        $followers_count = user()->userInfo->followers_count;
        $allowed_target_credits = ceil($followers_count / 100);
        $this->featuredCampaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', $allowed_target_credits)
            ->featured()
            ->withoutSelf()
            ->with(['music.user.userInfo'])
            ->get();

        $this->campaigns = $this->campaignService->getCampaigns()
            ->where('cost_per_repost', $allowed_target_credits)
            ->notFeatured()
            ->withoutSelf()
            ->with(['music.user.userInfo'])
            ->get();
    }

    public function render()
    {
        return view('backend.user.campaign_management.campaigns.campaign');
    }
}
