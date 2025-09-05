<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        if (Auth::guard('web')->check()) {
            $this->redirect(route('user.dashboard'), true);
        }
        return view('frontend.pages.landing-page.landing');
    }
}
