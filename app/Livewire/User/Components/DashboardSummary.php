<?php

namespace App\Livewire\User\Components;

use App\Models\User;
use App\Models\UserSetting;
use App\Services\User\UserSettingsService;
use Carbon\Carbon;
use Livewire\Component;

class DashboardSummary extends Component
{
    public $earnings, $dailyRepostCurrent, $dailyRepostMax, $responseRate;
    public $pendingRequests, $requestLimit, $credits, $campaigns, $campaignLimit, $resetResponseRateAction, $canResetResponseRate;
    protected $user;

    protected UserSettingsService $userSettingsService;

    public function boot(UserSettingsService $userSettingsService)
    {
        $this->userSettingsService = $userSettingsService;
    }

    public function mount()
    {
        //  $user = User::withCount([
        //     'reposts as reposts_count_today' => function ($query) {
        //         $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
        //     },
        //     'campaigns',
        //     'requests' => function ($query) {
        //         $query->pending();
        //     },
        // ])->find(user()->id);

        // $data['dailyRepostCurrent'] = $user->reposts_count_today ?? 0;
        // $data['totalMyCampaign'] = $user->campaigns_count ?? 0;
        // $data['pendingRequests'] = $user->requests_count ?? 0;

        $this->user = User::withCount([
            'reposts as reposts_count_today' => function ($query) {
                $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
            },
            'campaigns',
            'requests' => function ($query) {
                $query->pending();
            },
        ])->find(user()->id);

        $this->earnings = $this->user->repost_price;
        $this->dailyRepostCurrent = $this->user->reposts_count_today ?? 0;
        $this->dailyRepostMax = 20;

        $this->responseRate = $this->user->responseRate();
        $this->pendingRequests = $this->user->requests_count ?? 0;
        $this->requestLimit = 25;

        $this->credits = userCredits();
        $this->campaigns = $this->user->campaigns_count ?? 0;
        $this->campaignLimit = proUser() ? 10 : 2;
        $responseAt = UserSetting::self()->value('response_rate_reset');

        $this->canResetResponseRate = Carbon::parse($responseAt)->greaterThan(now()->subMonth(1));
        $this->resetResponseRateAction = 'responseReset';
    }

    public function responseReset()
    {
        $responseAt = UserSetting::self()->value('response_rate_reset');
        if ( $responseAt && $this->canResetResponseRate) {
            $this->dispatch('alert', type: 'error', message: 'You can only reset your response rate once every 30 days.');
            return;
        }

        $userUrn = user()->urn;
        $this->userSettingsService->createOrUpdate($userUrn, ['response_rate_reset' => now()]);
        $this->dispatch('alert', type: 'success', message: 'Your response rate has been reset.');
    }

    public function render()
    {
        return view('livewire.user.components.dashboard-summary');
    }
}
