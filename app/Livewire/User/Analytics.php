<?php

namespace App\Livewire\User;

use App\Models\Track;
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
    public array $selectedCampaignTypes = [];

    public array $data = [];
    public array $dataCache = [];
    public array $filterOptions = [];

    protected AnalyticsService $analyticsService;

    public function boot(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function mount()
    {
        $this->filterOptions = $this->analyticsService->getFilterOptions();
        $this->initializeDateRange();
        $this->loadData();
        dd($this->data);
    }

    public function updatedFilter()
    {
        $this->resetErrorBag();
        if ($this->filter === 'date_range') {
            $this->initializeDateRange();
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

    private function initializeDateRange()
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            $this->endDate = Carbon::now()->format('Y-m-d');
            $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        }
    }

    public function loadData()
    {
        try {
            $dateRange = null;

            // Prepare date range for custom filter
            if ($this->filter === 'date_range' && $this->startDate && $this->endDate) {
                $dateRange = [
                    'start' => $this->startDate,
                    'end' => $this->endDate
                ];

                // Validate date range
                if (!$this->analyticsService->validateDateRange($dateRange)) {
                    $this->addError('dateRange', 'Invalid date range selected.');
                    return;
                }
            }

            // Get fresh analytics data
            $freshData = $this->analyticsService->getAnalyticsData(
                $this->filter,
                $dateRange,
                null, // trackUrn - can be added later for specific track filtering
                null  // actionType - can be added later for specific action filtering
            );

            // Transform data for your existing UI structure
            $this->data = $this->transformDataForUI($freshData);

            // Store in cache for optimistic UI updates
            $cacheKey = $this->filter . ($dateRange ? '_' . $this->startDate . '_' . $this->endDate : '');
            $this->dataCache[$cacheKey] = $this->data;
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to load analytics data. Please try again.');
            logger()->error('Analytics data loading failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Transform the comprehensive analytics data to match your existing UI expectations
     */
    private function transformDataForUI(array $analyticsData): array
    {
        // Extract the metrics you're currently displaying
        $streams = $analyticsData['total_views']['current_total'] ?? 0;
        $likes = $analyticsData['total_likes']['current_total'] ?? 0;
        $reposts = $analyticsData['total_reposts']['current_total'] ?? 0;

        // Calculate engagement rate (you can customize this formula)
        $totalEngagements = $likes + $reposts + ($analyticsData['total_comments']['current_total'] ?? 0);
        $avgEngagementRate = $streams > 0 ? round(($totalEngagements / $streams) * 100, 1) : 0;

        return [
            'streams' => $this->formatNumber($streams),
            'likes' => $this->formatNumber($likes),
            'reposts' => $this->formatNumber($reposts),
            'avgEngagementRate' => $avgEngagementRate,

            // Include detailed analytics for potential future use
            'detailed' => $analyticsData,

            // Change rates for trend indicators
            'streams_change' => $analyticsData['total_views']['change_rate'] ?? 0,
            'likes_change' => $analyticsData['total_likes']['change_rate'] ?? 0,
            'reposts_change' => $analyticsData['total_reposts']['change_rate'] ?? 0,
            'engagement_change' => $this->calculateEngagementRateChange($analyticsData),
        ];
    }

    /**
     * Calculate engagement rate change between periods
     */
    private function calculateEngagementRateChange(array $analyticsData): float
    {
        $currentStreams = $analyticsData['total_views']['current_total'] ?? 0;
        $previousStreams = $analyticsData['total_views']['previous_total'] ?? 0;

        $currentEngagements = ($analyticsData['total_likes']['current_total'] ?? 0) +
            ($analyticsData['total_reposts']['current_total'] ?? 0) +
            ($analyticsData['total_comments']['current_total'] ?? 0);

        $previousEngagements = ($analyticsData['total_likes']['previous_total'] ?? 0) +
            ($analyticsData['total_reposts']['previous_total'] ?? 0) +
            ($analyticsData['total_comments']['previous_total'] ?? 0);

        $currentRate = $currentStreams > 0 ? ($currentEngagements / $currentStreams) * 100 : 0;
        $previousRate = $previousStreams > 0 ? ($previousEngagements / $previousStreams) * 100 : 0;

        if ($previousRate == 0) {
            return $currentRate > 0 ? 100.0 : 0.0;
        }

        return round((($currentRate - $previousRate) / $previousRate) * 100, 1);
    }

    /**
     * Format numbers for display
     */
    private function formatNumber($number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return number_format($number);
    }

    /**
     * Get chart data for the performance overview
     */
    public function getChartData(): array
    {
        try {
            $dateRange = null;
            if ($this->filter === 'date_range' && $this->startDate && $this->endDate) {
                $dateRange = [
                    'start' => $this->startDate,
                    'end' => $this->endDate
                ];
            }

            return $this->analyticsService->getChartData(
                $this->filter,
                $dateRange,
                null,
                null
            );
        } catch (\Exception $e) {
            logger()->error('Chart data loading failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get change indicator class for styling
     */
    public function getChangeClass($changeRate): string
    {
        if ($changeRate > 0) {
            return 'text-green-400';
        } elseif ($changeRate < 0) {
            return 'text-red-400';
        }
        return 'text-gray-500';
    }

    /**
     * Get change icon based on rate
     */
    public function getChangeIcon($changeRate): string
    {
        if ($changeRate > 0) {
            return 'trending-up';
        } elseif ($changeRate < 0) {
            return 'trending-down';
        }
        return 'minus';
    }

    /**
     * Apply advanced filters (for future implementation)
     */
    public function applyFilters()
    {
        // This method can be expanded to handle genre and campaign type filtering
        $this->loadData();
        $this->showFilters = false;
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->selectedGenres = [];
        $this->selectedCampaignTypes = [];
        $this->filter = 'last_week';
        $this->initializeDateRange();
        $this->loadData();
    }

    public function getFilterText(): string
    {
        return match ($this->filter) {
            'daily' => 'Daily',
            'last_week' => 'Last Week',
            'last_month' => 'Last Month',
            'last_90_days' => 'Last 90 Days',
            'last_year' => 'Last Year',
            'date_range' => 'Custom Range',
            default => 'Last Week',
        };
    }

    /**
     * Get period information for display
     */
    public function getPeriodInfo(): array
    {
        return $this->data['detailed']['period_info'] ?? [];
    }

    /**
     * Check if we have detailed analytics data
     */
    public function hasDetailedData(): bool
    {
        return !empty($this->data['detailed']);
    }

    public function render()
    {
        return view('livewire.user.analytics');
    }
}
