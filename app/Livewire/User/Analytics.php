<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Services\User\AnalyticsService;
use Livewire\Attributes\On;

class Analytics extends Component
{
    public bool $showGrowthTips = false;
    public bool $showFilters = false;

    public string $filter = 'last_week';

    public array $data = [];

    protected AnalyticsService $analyticsService;

    public function boot(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function mount()
    {
        $this->loadData();
    }

    #[On('updated:filter')]
    public function loadData()
    {
        $this->data = $this->analyticsService->getAnalyticsData(user()->urn, $this->filter);
    }

    // public function applyFilter()
    // {
    //     $this->dispatch('updated:filter');
    // }

    public function getFilterText()
    {
        return match ($this->filter) {
            'daily' => 'Daily',
            'last_week' => 'Last Week',
            'last_month' => 'Last Month',
            'last_90_days' => 'Last 90 Days',
            'last_year' => 'Last Year',
            default => 'Last Week',
        };
    }

    public function render()
    {
        return view('livewire.user.analytics');
    }
}
