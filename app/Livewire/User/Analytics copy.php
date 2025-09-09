<?php

namespace App\Livewire\User;

use App\Models\Track;
use App\Models\UserGenre;
use Livewire\Component;
use App\Services\User\AnalyticsService;
use Livewire\Attributes\On;
use Carbon\Carbon;

class Analytics extends Component
{
    public bool $showGrowthTips = false;
    public bool $showFilters = false;

    public string $filter = 'last_week';

    // Date range properties
    public string $startDate = '';
    public string $endDate = '';

    // Filter properties
    public array $selectedGenres = [];
    public array $userGenres = [];

    // This will hold the full analytics data structure
    public array $data = [];
    public array $dataCache = [];
    public array $filterOptions = [];
    public array $topTracks = [];
    public array $genreBreakdown = [];

    protected AnalyticsService $analyticsService;

    public function boot(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function mount()
    {
        $this->filterOptions = $this->analyticsService->getFilterOptions();
        $this->userGenres = $this->fetchUserGenres();
        $this->selectedGenres = ['Any Genre'];
        $this->loadData();
        $this->loadAdditionalData();
    }

    public function fetchUserGenres(): array
    {
        return UserGenre::where('user_urn', user()->urn)
            ->pluck('genre')
            ->toArray();
    }

    public function updatedFilter()
    {
        $this->resetErrorBag();
        if ($this->filter === 'date_range') {
            $this->showFilters = true;
        }
        $this->loadData();
    }

    public function updatedStartDate()
    {
        if ($this->filter === 'date_range') {
            $this->loadData();
        }
    }

    public function updatedEndDate()
    {
        if ($this->filter === 'date_range') {
            $this->loadData();
        }
    }

    public function loadData()
    {
        try {
            $dateRange = null;

            if ($this->filter === 'date_range' && $this->startDate && $this->endDate) {
                $dateRange = [
                    'start' => $this->startDate,
                    'end' => $this->endDate
                ];
                if (!$this->analyticsService->validateDateRange($dateRange)) {
                    $this->addError('dateRange', 'Invalid date range selected.');
                    return;
                }
            }

            $freshData = $this->analyticsService->getAnalyticsData(
                $this->filter,
                $dateRange,
                $this->selectedGenres,
                null,
                null
            );

            // Directly store the comprehensive data structure
            $this->data = $freshData;

            $cacheKey = $this->filter . ($dateRange ? '_' . $this->startDate . '_' . $this->endDate : '');
            $this->dataCache[$cacheKey] = $this->data;
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to load analytics data. Please try again.');
            logger()->error('Analytics data loading failed', ['error' => $e->getMessage()]);
        }
    }

    private function loadAdditionalData()
    {
        try {
            $this->topTracks = $this->analyticsService->getTopTracks(5);
            $this->genreBreakdown = $this->analyticsService->getGenreBreakdown();
        } catch (\Exception $e) {
            logger()->error('Additional data loading failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Helper function to get change class for trend icons
     */
    public function getChangeClass($changeRate): string
    {
        if ($changeRate > 0) {
            return 'text-green-500 dark:text-green-400';
        } elseif ($changeRate < 0) {
            return 'text-red-500 dark:text-red-400';
        }
        return 'text-gray-500 dark:text-gray-400';
    }

    /**
     * Helper function to get change icon for trend icons
     */
    public function getChangeIcon($changeRate): string
    {
        if ($changeRate > 0) {
            return 'trending-up';
        } elseif ($changeRate < 0) {
            return 'trending-down';
        }
        return 'trending-flat';
    }

    /**
     * Helper function to format numbers for readability
     */
    public function formatNumber($number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }
}