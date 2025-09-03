<?php

namespace App\Livewire\User;

use Livewire\Component;

class Chart extends Component
{
    public $activeTab = 'compactView';



    public function mount()
    {
        $this->activeTab = request()->query('tab', 'compactView');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->emit('updateQueryString', ['tab' => $tab]);
    }
    public function render()
    {
        return view('livewire.user.chart');
    }
}
