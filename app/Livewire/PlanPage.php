<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('backend.user.layouts.app', ['title' => 'Plan Page', 'page_slug' => 'plan-page'])]
class PlanPage extends Component
{
    public function render()
    {
        return view('livewire.plan-page');
    }
}
