<?php

namespace App\Livewire\User;

use App\Models\Track;
use App\Models\UserGenre;
use Livewire\Component;
use App\Services\User\AnalyticsService;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;

class Analytics extends Component
{
    use WithPagination;

    public bool $showGrowthTips = false;
    public bool $showFilters = false;
    public bool $isLoading = false;

    #[Url]
    public string $filter = 'last_week';
    #[Url]
    public string $startDate = '';
    #[Url]
    public string $endDate = '';
    #[Url]
    public array $selectedGenres = [];

    public array $userGenres = [];
    public array $data = [];
    public array $dataCache = [];
    public array $filterOptions = [];
    public array $topSources = [];
    public array $genreBreakdown = [];

    protected AnalyticsService $analyticsService;

    // Track performance pagination
    public int $tracksPerPage = 10;

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
        $this->getChartData();
    }

    /**
     * Optimized user genres fetching
     */
    public function fetchUserGenres(): array
    {
        return UserGenre::where('user_urn', user()->urn)
            ->pluck('genre')
            ->unique()
            ->values()
            ->toArray();
    }

    public function updatedFilter()
    {
        $this->handleFilterChange();
    }

    public function updatedStartDate()
    {
        if ($this->filter === 'date_range') {
            $this->handleFilterChange();
        }
    }

    public function updatedEndDate()
    {
        if ($this->filter === 'date_range') {
            $this->handleFilterChange();
        }
    }

    /**
     * Handle filter changes with full page reload effect
     */
    private function handleFilterChange()
    {
        $this->resetErrorBag();
        $this->resetPage();

        if ($this->filter === 'date_range') {
            $this->showFilters = true;
        }

        // Start loading state
        $this->isLoading = true;
        $this->dispatch('startLoading');


        $this->loadData();
        $this->loadAdditionalData();

        // End loading state and trigger full refresh
        $this->isLoading = false;
        $this->dispatch('completeRefresh');

        // Navigate to same page with updated query parameters
        $queryParams = [
            'filter' => $this->filter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'selectedGenres' => $this->selectedGenres,
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function ($value) {
            return !empty($value) || $value === '0';
        });

        $this->redirect(route('user.analytics') . '?' . http_build_query($queryParams), navigate: true);
    }

    /**
     * Optimized main data loading
     */
    public function loadData()
    {
        $dateRange = $this->getDateRange();
        try {
            if ($dateRange === false) {
                return;
            }

            // Get fresh analytics data
            $freshData = $this->analyticsService->getAnalyticsData(
                $this->filter,
                $dateRange,
                $this->selectedGenres,
                null,
                null
            );
            // Transform data for UI
            $this->data = $this->transformDataForUI($freshData);
            // Store in cache
            $cacheKey = $this->getCacheKey($dateRange);
            $this->dataCache[$cacheKey] = $this->data;

            $this->dispatch('dataUpdated');
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to load analytics data. Please try again.');
            logger()->error('Analytics data loading failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get date range for current filter
     */
    private function getDateRange()
    {
        if ($this->filter === 'date_range' && $this->startDate && $this->endDate) {
            $dateRange = [
                'start' => $this->startDate,
                'end' => $this->endDate
            ];

            if (!$this->analyticsService->validateDateRange($dateRange)) {
                $this->addError('dateRange', 'Invalid date range selected.');
                return false;
            }

            return $dateRange;
        }

        return null;
    }

    /**
     * Generate cache key
     */
    private function getCacheKey(?array $dateRange): string
    {
        $genreKey = implode(',', $this->selectedGenres);
        return $this->filter .
            ($dateRange ? '_' . $this->startDate . '_' . $this->endDate : '') .
            '_' . md5($genreKey);
    }

    /**
     * Load additional data (top tracks, genre breakdown)
     */
    private function loadAdditionalData()
    {
        try {
            $dateRange = $this->getDateRange();

            if ($dateRange === false) {
                return;
            }

            $this->topSources = $this->analyticsService->getTopSources(
                userUrn: user()->urn,
                limit: 5,
                filter: $this->filter,
                dateRange: $dateRange
            );

            $this->genreBreakdown = $this->analyticsService->getGenreBreakdown($this->filter, $dateRange, $this->selectedGenres);
        } catch (\Exception $e) {
            logger()->error('Additional data loading failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get paginated track performance data
     */
    public function getPaginatedTrackData(): LengthAwarePaginator
    {
        try {
            $dateRange = $this->getDateRange();

            if ($dateRange === false) {
                return new LengthAwarePaginator([], 0, $this->tracksPerPage, $this->getPage());
            }

            return $this->analyticsService->getPaginatedAnalytics(
                filter: $this->filter,
                dateRange: $dateRange,
                genres: $this->selectedGenres,
                perPage: $this->tracksPerPage,
                page: $this->getPage(),
                userUrn: user()->urn,
            );
        } catch (\Exception $e) {
            logger()->error('Paginated track data loading failed', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $this->tracksPerPage, $this->getPage());
        }
    }

    /**
     * Transform analytics data for UI
     */
    private function transformDataForUI(array $analyticsData): array
    {
        $streams = $analyticsData['overall_metrics']['total_plays']['current_total'];
        $likes = $analyticsData['overall_metrics']['total_likes']['current_total'];
        $reposts = $analyticsData['overall_metrics']['total_reposts']['current_total'];
        $followers = $analyticsData['overall_metrics']['total_followers']['current_total'];
        $comments = $analyticsData['overall_metrics']['total_comments']['current_total'];
        $views = $analyticsData['overall_metrics']['total_views']['current_total'] == 0 ? 1 : $analyticsData['overall_metrics']['total_views']['current_total'];

        // Calculate engagement rate
        $avgTotal = ($likes + $comments + $reposts + $streams + $followers) / 5;
        $avgEngagementRate =  $views >= $avgTotal ? round(min(100, ($avgTotal / $views) * 100), 2) : 0;

        return [
            'streams' => $this->formatNumber($streams),
            'likes' => $this->formatNumber($likes),
            'reposts' => $this->formatNumber($reposts),
            'avgEngagementRate' => $avgEngagementRate,
            'detailed' => $analyticsData,
            'streams_change' => $analyticsData['overall_metrics']['total_plays']['change_rate'] ?? 0,
            'likes_change' => $analyticsData['overall_metrics']['total_likes']['change_rate'] ?? 0,
            'reposts_change' => $analyticsData['overall_metrics']['total_reposts']['change_rate'] ?? 0,
            'engagement_change' => $this->calculateEngagementRateChange($analyticsData),
        ];
    }

    /**
     * Calculate engagement rate change between periods
     */
    private function calculateEngagementRateChange(array $analyticsData): float
    {
        $currentViews = $analyticsData['overall_metrics']['total_views']['current_total'] ?? 0;
        $previousViews = $analyticsData['overall_metrics']['total_views']['previous_total'] ?? 0;

        $currentEngagements = ($analyticsData['overall_metrics']['total_likes']['current_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_reposts']['current_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_comments']['current_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_plays']['current_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_followers']['current_total'] ?? 0);

        $previousEngagements = ($analyticsData['overall_metrics']['total_likes']['previous_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_reposts']['previous_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_comments']['previous_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_plays']['previous_total'] ?? 0) +
            ($analyticsData['overall_metrics']['total_followers']['previous_total'] ?? 0);

        $currentRate = $currentViews > 0 ? ($currentEngagements / $currentViews) * 100 : 0;
        $previousRate = $previousViews > 0 ? ($previousEngagements / $previousViews) * 100 : 0;

        if ($previousRate == 0) {
            return $currentRate > 0 ? 100.0 : 0.0;
        }

        // calculate the change rate in percentage
        $changeRate = $previousRate > 0 ? (($currentRate - $previousRate) / $previousRate * 100) : 0;
        $changeRate = max(-100, min(100, $changeRate));
        return round($changeRate, 1);
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
     * Get chart data for performance overview
     */
    public function getChartData(): array
    {
        try {
            $dateRange = $this->getDateRange();

            if ($dateRange === false) {
                return [];
            }

            return $this->analyticsService->getChartData(
                $this->filter,
                $dateRange,
                $this->selectedGenres,
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
     * Apply advanced filters with full page reload
     */
    public function applyFilters()
    {
        if (!empty($this->startDate) || !empty($this->endDate)) {
            $this->filter = 'date_range';
        }

        $this->resetPage();
        $this->showFilters = false;

        // Trigger the same reload mechanism
        $this->handleFilterChange();
    }

    /**
     * Reset all filters with full page reload
     */
    public function resetFilters()
    {
        $this->selectedGenres = ['Any Genre'];
        $this->filter = 'last_week';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();

        // Trigger the same reload mechanism
        $this->handleFilterChange();
    }

    /**
     * Get filter text for display
     */
    public function getFilterText(): string
    {
        return match ($this->filter) {
            'daily' => 'Today',
            'last_week' => 'Last 7 Days',
            'last_month' => 'Last 30 Days',
            'last_90_days' => 'Last 90 Days',
            'last_year' => 'Last Year',
            'date_range' => 'Custom Range',
            default => 'Last 7 Days',
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

    /**
     * Livewire pagination URL handling
     */
    public function updatingPage()
    {
        // This will be called before the page is updated
        // You can add any cleanup logic here if needed
    }

    public function render()
    {
        $this->getChartData();
        $paginated = $this->getPaginatedTrackData();

        // Get the collection from paginator
        $items = $paginated->getCollection();

        // Map over items to calculate engagement score and rate
        $itemsWithMetrics = $items->map(function ($track) {
            $totalViews = $track['metrics']['total_views']['current_total'];
            $totalPlays = $track['metrics']['total_plays']['current_total'];
            $totalReposts = $track['metrics']['total_reposts']['current_total'];
            $totalLikes = $track['metrics']['total_likes']['current_total'];
            $totalComments = $track['metrics']['total_comments']['current_total'];
            $totalFollowers = $track['metrics']['total_followers']['current_total'];

            $avgTotal = ($totalLikes + $totalComments + $totalReposts + $totalPlays + $totalFollowers) / 5;

            // Engagement % (capped at 100)
            $engagementRate = $totalViews > $avgTotal ? round(min(100, ($totalViews - $avgTotal) / (($totalViews + $avgTotal) / 2) * 100), 2) : 0;

            // Engagement Score (0â€“10 scale)
            $engagementScore = round(($engagementRate / 100) * 10, 1);

            // Add score and rate to track array
            $track['engagement_score'] = $engagementScore;
            $track['engagement_rate'] = round($engagementRate, 2); // optional rounding

            return $track;
        });

        // Sort by engagement score descending
        $sorted = $itemsWithMetrics->sortByDesc('engagement_score');

        // Update paginator collection
        $paginated->setCollection($sorted);
        return view('livewire.user.analytics', [
            'paginatedTracks' => $paginated
        ]);
    }
}
