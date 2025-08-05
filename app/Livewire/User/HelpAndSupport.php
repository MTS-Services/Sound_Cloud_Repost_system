<?php

namespace App\Livewire\User;

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

    public function render()
    {
        return view('livewire.user.help-and-support');
    }
}
