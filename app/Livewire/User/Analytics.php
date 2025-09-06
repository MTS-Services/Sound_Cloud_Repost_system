<?php

namespace App\Livewire\User;

use Livewire\Component;

class Analytics extends Component
{
    public bool $showGrowthTips = false;
    public bool $showFilters = true;

    public function render()
    {
        return view('livewire.user.analytics');
    }
}
