<?php

namespace App\Livewire\User;

use App\Models\Repost;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class DashboardSummary extends Component
{
    public function render()
    {
        $user = User::withCount([
            'reposts as reposts_count_today' => function ($query) {
                $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
            },
            'campaigns' => function ($query) {
                $query->open();
            },
            'requests' => function ($query) {
                $query->pending();
            },
        ])->find(user()->id);
        $user->load('userInfo');

        $dailyReposts = $user->reposts_count_today ?? 0;
        $campaigns = $user->campaigns_count ?? 0;
        $pendingRequests = $user->requests_count ?? 0;
        return view('livewire.user.dashboard-summary', [
            'dailyTotalReposts' => $dailyReposts,
            'totalMyCampaign' => $campaigns,
            'pendingRequests' => $pendingRequests
        ]);
    }
}
