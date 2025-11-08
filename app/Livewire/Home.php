<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        if (Auth::guard('web')->check() && Auth::user()->banned_at == null) {
            $this->redirect(route('user.dashboard'), true);
        }
        return view('frontend.pages.landing-page.landing');
    }
}
