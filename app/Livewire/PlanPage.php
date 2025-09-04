<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('backend.user.layouts.app', ['title' => 'Plan Page', 'page_slug' => 'plan-page'])]
class PlanPage extends Component
{

    public $isYearly = false;


    public function togglePlanType()
    {
        $this->isYearly = !$this->isYearly;
    }

    public function render()
    {
        return view('livewire.plan-page');
    }
}
