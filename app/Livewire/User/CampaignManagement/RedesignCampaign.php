<?php

namespace App\Livewire\User\CampaignManagement;

use Livewire\Component;

class RedesignCampaign extends Component
{
    public $search = '';
    public $showSuggestions = false;
    public $suggestedTags = [];

    public string $activeTab = 'recommendedPro';

    public function mount()
    {
        $this->activeTab = request()->query('tab', $this->activeTab);
    }

    public function render()
    {
        return view('livewire.user.campaign-management.redesign-campaign');
    }
}
