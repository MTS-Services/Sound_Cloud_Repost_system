<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use App\Services\User\AnalyticsService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class Chart extends Component
{
    use WithPagination;

    protected AnalyticsService $analyticsService;

    public $tracksPerPage = 20;
    public $activeTab = 'listView';
    // public $topTracks = [];

    public function boot(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getTopTrackData(): LengthAwarePaginator
    {
        // try {
            return $this->analyticsService->getPaginatedTrackAnalytics(
                filter: 'last_week',
                dateRange: null,
                genres: [],
                perPage: $this->tracksPerPage,
                page: $this->getPage(),
                actionType: Campaign::class
            );
        // } catch (\Exception $e) {
        //     Log::error('Paginated track data loading failed', ['error' => $e->getMessage()]);
        //     return new LengthAwarePaginator([], 0, $this->tracksPerPage, $this->getPage());
        // }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function render()
    {
        return view(
            'livewire.user.chart',
            [
                'topTracks' => $this->getTopTrackData()
            ]
        );
    }
}
