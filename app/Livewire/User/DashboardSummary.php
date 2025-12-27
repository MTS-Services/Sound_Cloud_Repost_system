<?php

namespace App\Livewire\User;

use App\Models\Repost;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\UserSetting;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\UserSettingsService;

class DashboardSummary extends Component
{
    protected UserSettingsService $userSettingsService;
    protected SoundCloudService $soundCloudService;


    public function boot(UserSettingsService $userSettingsService, SoundCloudService $soundCloudService)
    {
        $this->userSettingsService = $userSettingsService;
        $this->soundCloudService = $soundCloudService;
    }



    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

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

    public function responseReset()
    {
        $responseAt = UserSetting::self()->value('response_rate_reset');
        if ($responseAt && Carbon::parse($responseAt)->greaterThan(now()->subDays(30))) {
            $this->dispatch('alert', type: 'error', message: 'You can only reset your response rate once every 30 days.');
            return;
        }
        $userUrn = user()->urn;
        $this->userSettingsService->createOrUpdate($userUrn, ['response_rate_reset' => now()]);
        $this->dispatch('alert', type: 'success', message: 'Your response rate has been reset.');
    }
}
