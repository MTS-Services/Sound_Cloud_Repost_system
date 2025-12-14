<?php

namespace App\Livewire\User;

use App\Services\SoundCloud\SoundCloudService;
use Livewire\Component;

class HelpAndSupport extends Component
{
    public $billing = false;
    public $privacys = false;
    public $test = true;

    public function billings()
    {
        $this->billing = true;
        $this->test = false;
    }

    public function privacy()
    {
        $this->privacys = false;
        $this->test = true;
    }

    protected SoundCloudService $soundCloudService;

    public function boot(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    public function mount()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }
    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    public function render()
    {
        return view('livewire.user.help-and-support');
    }
}
