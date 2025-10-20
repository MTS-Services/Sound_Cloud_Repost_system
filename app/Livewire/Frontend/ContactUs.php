<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ContactUs extends Component
{


    public function render()
    {
        if (Auth::guard('web')->check()) {
            $this->redirect(route('user.dashboard'), true);
        }
        return view('livewire.frontend.contact-us');
    }
}
