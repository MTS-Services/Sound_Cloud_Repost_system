<?php

namespace App\Livewire\User;

use Livewire\Component;

class Chart extends Component
{
    public $activeTab = 'listView';



    public function mount()
    {
        // 
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function render()
    {
        return view('livewire.user.chart');
    }
}
