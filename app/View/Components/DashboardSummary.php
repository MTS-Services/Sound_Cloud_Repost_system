<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardSummary extends Component
{
    public $earnings, $dailyRepostCurrent, $dailyRepostMax, $responseRate, $pendingRequests, $requestLimit, $credits, $campaigns, $campaignLimit;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $earnings = 0,
        $dailyRepostCurrent = 0,
        $dailyRepostMax = 0,
        $responseRate = 0,
        $pendingRequests = 0,
        $requestLimit = 0,
        $credits = 0,
        $campaigns = 0,
        $campaignLimit = 0
    ) {
        $this->earnings = $earnings;
        $this->dailyRepostCurrent = $dailyRepostCurrent;
        $this->dailyRepostMax = $dailyRepostMax;
        $this->responseRate = $responseRate;
        $this->pendingRequests = $pendingRequests;
        $this->requestLimit = $requestLimit;
        $this->credits = $credits;
        $this->campaigns = $campaigns;
        $this->campaignLimit = $campaignLimit;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-summary');
    }
}
