<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('backend.user.layouts.app', ['title' => 'Plan Page', 'page_slug' => 'plan-page'])]
class PlanPage extends Component
{
    public function render()
    {
        if (Auth::guard('web')->check()) {
            $this->redirect(route('user.dashboard'), true);
        }
        return view('livewire.plan-page');
    }
}
