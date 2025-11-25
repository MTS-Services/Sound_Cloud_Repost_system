<?php

namespace App\Livewire\User;

use Livewire\Attributes\On;
use Livewire\Component;

class Repost extends Component
{

    public $showRepostActionModal = false;
    public function render()
    {
        return view('livewire.user.repost');
    }

    #[On('callRepostAction')]
    public function callRepostAction($campaignId)
    {
        $this->showRepostActionModal = true;
        dd($campaignId);
    }
}
