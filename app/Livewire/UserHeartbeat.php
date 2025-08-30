<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserHeartbeat extends Component
{


    public function ping()
    {
        if (Auth::check()) {
            Auth::user()->update(['last_seen_at' => now()]);
        }
    }
    public function render()
    {
        return view('livewire.user-heartbeat');
    }
}
