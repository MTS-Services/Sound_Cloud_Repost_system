<?php

namespace App\Services\User;

use App\Models\Campaign;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\UserAnalytics;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AnalyticsService
{
    /**
     * Checks if an action update is allowed for a given action, user, and source today.
     * If allowed, it sets a session flag to prevent subsequent updates for the day.
     */
    public function syncUserAction(object $track, object $action, string $column, $userUrn = null): bool
    {
        if ($userUrn == null) {
            $userUrn = user()->urn;
        }

        // First, check for and delete any old user action session data.
        // This prevents the session from bloating with old, unused keys.
        $today = now()->toDateString();
        foreach (session()->all() as $key => $value) {
            if (str_starts_with($key, 'user.action.updates.') && !str_ends_with($key, $today)) {
                session()->forget($key);
            }
        }

        // Generate a unique session key for today's updates.
        $todayKey = 'user.action.updates.' . $today;

        // Retrieve the current day's updates or an empty array.
        $updatedToday = session()->get($todayKey, []);

        // Define the unique identifier for the current action.
        $actionIdentifier = sprintf(
            '%s.%s.%s.%s.%s',
            $column,
            $track->urn,
            $action->id,
            $action->getMorphClass(),
            $userUrn,
            $today
        );

        // Check if this action has already been logged for today.
        if (in_array($actionIdentifier, $updatedToday)) {
            Log::info("User action update skipped for {$userUrn} on {$actionIdentifier} for source {$track->urn}. Already updated today.");
            return false;
        }

        // If not in the session, add the action and save.
        $updatedToday[] = $actionIdentifier;
        session()->put($todayKey, $updatedToday);

        return true;
    }

    public function updateAnalytics(object $track, object $action, string $column, string $genre, int $increment = 1): UserAnalytics|bool|null
    {
        // Get the owner's URN from the track model.
        $userUrn = $action->user?->urn ?? $track->user?->urn ?? null;

        // If no user URN is found, log and exit early.
        if (!$userUrn) {
            Log::info("User action update skipped for {$userUrn} on {$column} for track {$track->id} and track type {$track->getMorphClass()}. No user URN found.");
            return null;
        }

        // Use the new reusable method to check if the update is allowed.
        if (!$this->syncUserAction($track, $action, $column)) {
            return false;
        }

        // Find or create the UserAnalytics record based on the unique combination.
        $analytics = UserAnalytics::firstOrNew(
            [
                'user_urn' => $userUrn,
                'track_urn' => $track->urn,
                'action_id' => $action->id,
                'action_type' => $action->getMorphClass(),
                'date' => now()->toDateString(),
            ]
        );
        $analytics->genre = $genre;
        $analytics->save();
        $analytics->increment($column, $increment);

        return $analytics;
    }

    /**
     * Available filter options
     */
    const FILTER_OPTIONS = [
        'daily' => 'Last 24 hours',
        'last_week' => 'Last 7 days',
        'last_month' => 'Last 30 days',
        'last_90_days' => 'Last 90 days',
        'last_year' => 'Last 365 days',
        'date_range' => 'Custom Date Range'
    ];

    /**
     * Metrics to calculate
     */
    const METRICS = [
        'total_plays',
        'total_likes',
        'total_comments',
        'total_views',
        'total_requests',
        'total_reposts',
        'total_followers'
    ];

    /**
     * Get analytics data with comparison
     */
    public function getAnalyticsData(
        string $filter = 'last_week',
        ?array $dateRange = null,
        ?array $genres = null,
        ?string $trackUrn = null,
        ?string $actionType = null
    ): array {
        $userUrn = user()->urn;

        // Get date ranges for current and previous periods
        $periods = $this->calculatePeriods($filter, $dateRange);

        // Fetch analytics data for both periods in a single optimized query
        $allData = $this->fetchOptimizedAnalyticsData(
            $userUrn,
            $periods['previous']['start'],
            $periods['current']['end'],
            $genres,
            $trackUrn,
            $actionType
        );

        // Separate current and previous period data
        $currentData = $allData->filter(function ($item) use ($periods) {
            $itemDate = Carbon::parse($item->date);
            return $itemDate->between($periods['current']['start'], $periods['current']['end']);
        });

        $previousData = $allData->filter(function ($item) use ($periods) {
            $itemDate = Carbon::parse($item->date);
            return $itemDate->between($periods['previous']['start'], $periods['previous']['end']);
        });

        // Calculate and compare overall metrics
        $overallCurrentMetrics = $this->calculateMetrics($currentData);
        $overallPreviousMetrics = $this->calculateMetrics($previousData);
        $overallAnalytics = $this->buildComparisonResult($overallCurrentMetrics, $overallPreviousMetrics);

        // Calculate and compare metrics for each track
        $currentMetricsByTrack = $this->calculateMetricsByTrack($currentData);
        $previousMetricsByTrack = $this->calculateMetricsByTrack($previousData);
        $trackAnalytics = $this->buildComparisonResultByTrack($currentMetricsByTrack, $previousMetricsByTrack);

        return [
            'overall_metrics' => $overallAnalytics,
            'track_metrics' => $trackAnalytics,
            'period_info' => [
                'filter' => $filter,
                'current_period' => [
                    'start' => $periods['current']['start']->format('Y-m-d'),
                    'end' => $periods['current']['end']->format('Y-m-d'),
                    'days' => $periods['current']['days']
                ],
                'previous_period' => [
                    'start' => $periods['previous']['start']->format('Y-m-d'),
                    'end' => $periods['previous']['end']->format('Y-m-d'),
                    'days' => $periods['previous']['days']
                ]
            ]
        ];
    }

    /**
     * Optimized data fetching with eager loading
     */
    private function fetchOptimizedAnalyticsData(
        ?string $userUrn = null,
        Carbon $startDate,
        Carbon $endDate,
        ?array $genres = null,
        ?string $trackUrn = null,
        ?string $actionType = null,
        ?int $actionId = null
    ): Collection {
        $query = UserAnalytics::query();

        if ($userUrn !== null) {
            $query->where('user_urn', $userUrn);
        }

        $query->whereDate('date', '>=', $startDate->format('Y-m-d'))
            ->whereDate('date', '<=', $endDate->format('Y-m-d'));


        if ($trackUrn) {
            $query->where('track_urn', $trackUrn);
        }

        if ($actionType && $actionId) {
            $query->where('action_type', $actionType)
                ->where('action_id', $actionId);
        }

        if ($genres) {
            $filteredGenres = array_filter($genres, function ($genre) {
                return $genre !== 'Any Genre';
            });

            if (!empty($filteredGenres)) {
                $query->whereIn('genre', $filteredGenres);
            }
        }
        return $query->get();
    }

    /**
     * Get paginated track analytics data
     */
    public function getPaginatedTrackAnalytics(
        string $filter = 'last_week',
        ?array $dateRange = null,
        ?array $genres = null,
        int $perPage = 10,
        int $page = 1,
        ?string $userUrn = null,
        ?string $actionType = null
    ): LengthAwarePaginator {

        $periods = $this->calculatePeriods($filter, $dateRange);


        // Get track URNs with aggregated data
        $trackUrnsQuery = UserAnalytics::query();

        if ($userUrn !== null) {
            $trackUrnsQuery->where('user_urn', $userUrn);
        }

        if ($actionType !== null) {
            $trackUrnsQuery->where('action_type', $actionType);
        }

        $trackUrnsQuery->whereDate('date', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('date', '<=', $periods['current']['end']->format('Y-m-d'));

        if ($genres) {
            $filteredGenres = array_filter($genres, function ($genre) {
                return $genre !== 'Any Genre';
            });

            if (!empty($filteredGenres)) {
                $trackUrnsQuery->whereIn('genre', $filteredGenres);
            }
        }
        // dd($trackUrnsQuery->get());

        $trackUrns = $trackUrnsQuery
            ->selectRaw('
                track_urn,
                MIN(action_type) as action_type,
                MIN(action_id) as action_id,
                SUM(total_views) as total_views,
                SUM(total_plays) as total_streams,
                SUM(total_likes) as total_likes,
                SUM(total_reposts) as total_reposts,
                SUM(total_comments) as total_comments
            ')
            // ->groupBy('track_urn')
            // ->orderByDesc('total_views')
            ->orderBy('total_streams')
            ->groupBy('total_reposts')
            ->get();

        // Create paginator
        $total = $trackUrns->count();
        $offset = ($page - 1) * $perPage;
        $paginatedTrackUrns = $trackUrns->slice($offset, $perPage);

        // Get detailed analytics for paginated tracks
        $trackAnalytics = [];
        if ($paginatedTrackUrns->isNotEmpty()) {
            $trackUrnsList = $paginatedTrackUrns->pluck('track_urn')->toArray();

            // Fetch current period data
            $currentData = $this->fetchOptimizedAnalyticsData(
                $userUrn,
                $periods['current']['start'],
                $periods['current']['end'],
                $genres
            )->whereIn('track_urn', $trackUrnsList);

            // Fetch previous period data
            $previousData = $this->fetchOptimizedAnalyticsData(
                $userUrn,
                $periods['previous']['start'],
                $periods['previous']['end'],
                $genres
            )->whereIn('track_urn', $trackUrnsList);


            $currentMetricsByTrack = $this->calculateMetricsByTrack($currentData);
            $previousMetricsByTrack = $this->calculateMetricsByTrack($previousData);
            $trackAnalytics = $this->buildComparisonResultByTrack($currentMetricsByTrack, $previousMetricsByTrack);
        }

        return new LengthAwarePaginator(
            $trackAnalytics,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    /**
     * Calculate current and previous periods based on filter
     */
    private function calculatePeriods(string $filter, ?array $dateRange = null): array
    {
        $now = Carbon::now();

        if ($filter === 'date_range' && $dateRange) {
            return $this->calculateCustomDateRangePeriods($dateRange);
        }

        // Get current period dates
        $currentEnd = $now->copy()->endOfDay();
        $currentStart = $this->getStartDateForFilter($filter, $now);

        // Calculate the duration in days (inclusive)
        $durationInDays = $currentStart->diffInDays($currentEnd) + 1;

        // Calculate previous period (same duration, shifted back)
        $previousEnd = $currentStart->copy()->subDay()->endOfDay();
        $previousStart = $previousEnd->copy()->subDays($durationInDays - 1)->startOfDay();

        return [
            'current' => [
                'start' => $currentStart,
                'end' => $currentEnd,
                'days' => $durationInDays
            ],
            'previous' => [
                'start' => $previousStart,
                'end' => $previousEnd,
                'days' => $durationInDays
            ]
        ];
    }

    /**
     * Calculate periods for custom date range
     */
    private function calculateCustomDateRangePeriods(array $dateRange): array
    {
        $currentStart = Carbon::parse($dateRange['start'])->startOfDay();
        $currentEnd = Carbon::parse($dateRange['end'])->endOfDay();

        // Calculate duration in days (inclusive)
        $durationInDays = $currentStart->diffInDays($currentEnd) + 1;

        // Calculate previous period
        $previousEnd = $currentStart->copy()->subDay()->endOfDay();
        $previousStart = $previousEnd->copy()->subDays($durationInDays - 1)->startOfDay();

        return [
            'current' => [
                'start' => $currentStart,
                'end' => $currentEnd,
                'days' => $durationInDays
            ],
            'previous' => [
                'start' => $previousStart,
                'end' => $previousEnd,
                'days' => $durationInDays
            ]
        ];
    }

    /**
     * Get start date for predefined filters
     */
    private function getStartDateForFilter(string $filter, Carbon $now): Carbon
    {
        return match ($filter) {
            'daily' => $now->copy()->startOfDay(),
            'last_week' => $now->copy()->subDays(6)->startOfDay(),
            'last_month' => $now->copy()->subDays(29)->startOfDay(),
            'last_90_days' => $now->copy()->subDays(89)->startOfDay(),
            'last_year' => $now->copy()->subDays(364)->startOfDay(),
            default => $now->copy()->subDays(6)->startOfDay(),
        };
    }

    /**
     * Calculate metrics from raw analytics data, grouped by track.
     */
    private function calculateMetricsByTrack(Collection $data): array
    {
        $metricsByTrack = [];


        $data->groupBy('track_urn')->each(function ($trackGroup, $trackUrn) use (&$metricsByTrack) {
            $track = $trackGroup->first()->track;
            $trackName = $track ? $track->title : 'Unknown Track';
            $trackDetails = $track ? $track : null;
            $actionDetails = $trackGroup->first()->action ?? null;

            if ($trackDetails) {
                $trackDetails['created_at_formatted'] = $track->created_at?->format('M d, Y') ?? 'Unknown';
            }

            if ($actionDetails) {
                $actionDetails['created_at_formatted'] = $actionDetails['created_at']->format('M d, Y');
            }

            $totalMetrics = [];
            foreach (self::METRICS as $metric) {
                $totalMetrics[$metric] = $trackGroup->sum($metric);
            }

            $metricsByTrack[$trackUrn] = [
                'track_urn' => $trackUrn,
                'track_name' => $trackName,
                'track_details' => $trackDetails,
                'action_details' => $actionDetails,
                'metrics' => $totalMetrics
            ];
        });
        return $metricsByTrack;
    }

    /**
     * Compare and build final result, grouped by track.
     */
    private function buildComparisonResultByTrack(array $currentMetrics, array $previousMetrics): array
    {
        $result = [];
        $allTrackUrns = array_unique(array_merge(array_keys($currentMetrics), array_keys($previousMetrics)));

        foreach ($allTrackUrns as $trackUrn) {
            $current = $currentMetrics[$trackUrn] ?? null;
            $previous = $previousMetrics[$trackUrn] ?? null;

            $trackName = $current['track_name'] ?? $previous['track_name'] ?? 'Unknown Track';
            $trackDetails = $current['track_details'] ?? $previous['track_details'] ?? null;
            $actionDetails = $current['action_details'] ?? $previous['action_details'] ?? null;

            $trackResult = [
                'track_urn' => $trackUrn,
                'track_name' => $trackName,
                'track_details' => $trackDetails,
                'action_details' => $actionDetails
            ];

            foreach (self::METRICS as $metric) {
                $currentTotal = $current['metrics'][$metric] ?? 0;
                $previousTotal = $previous['metrics'][$metric] ?? 0;

                $trackResult['metrics'][$metric] = [
                    'current_total' => $currentTotal,
                    'previous_total' => $previousTotal,
                    'change_rate' => $this->calculatePercentageChange($currentTotal, $previousTotal)
                ];
            }
            $result[] = $trackResult;
        }

        return $result;
    }

    /**
     * Calculate metrics from raw analytics data
     */
    private function calculateMetrics(Collection $data): array
    {
        if ($data->isEmpty()) {
            return $this->getEmptyMetrics();
        }

        // Group by date and sum daily totals
        $dailyData = $data->groupBy('date')->map(function ($group) {
            $dailyMetrics = [];
            foreach (self::METRICS as $metric) {
                $dailyMetrics[$metric] = $group->sum($metric);
            }
            return $dailyMetrics;
        });

        // Calculate totals and averages
        $metrics = [];
        $totalDays = $dailyData->count();

        foreach (self::METRICS as $metric) {
            $total = $dailyData->sum($metric);
            $average = $totalDays > 0 ? $total / $totalDays : 0;

            $metrics[$metric] = [
                'total' => $total,
                'average' => $average
            ];
        }

        // Calculate rates (percentages based on views)
        $totalViews = $metrics['total_views']['total'];
        $avgViews = $metrics['total_views']['average'];

        foreach (self::METRICS as $metric) {
            if ($metric === 'total_views')
                continue;

            $metrics[$metric]['total_percent'] = $totalViews > 0
                ? ($metrics[$metric]['total'] / $totalViews) * 100
                : 0;

            $metrics[$metric]['avg_percent'] = $avgViews > 0
                ? ($metrics[$metric]['average'] / $avgViews) * 100
                : 0;
        }

        // Views percent is always 100% of itself
        $metrics['total_views']['total_percent'] = 100;
        $metrics['total_views']['avg_percent'] = 100;
        return $metrics;
    }

    /**
     * Get empty metrics structure
     */
    private function getEmptyMetrics(): array
    {
        $metrics = [];
        foreach (self::METRICS as $metric) {
            $metrics[$metric] = [
                'total' => 0,
                'average' => 0,
                'total_percent' => 0,
                'avg_percent' => 0
            ];
        }
        return $metrics;
    }

    /**
     * Build comparison result between current and previous periods
     */
    private function buildComparisonResult(array $currentMetrics, array $previousMetrics): array
    {
        $result = [];

        foreach (self::METRICS as $metric) {
            $current = $currentMetrics[$metric];
            $previous = $previousMetrics[$metric];

            $result[$metric] = [
                // Current period values
                'current_total' => $current['total'],
                'current_avg' => round($current['average'], 2),
                'current_total_percent' => round($current['total_percent'], 2),
                'current_avg_percent' => round($current['avg_percent'], 2),

                // Previous period values
                'previous_total' => $previous['total'],
                'previous_avg' => round($previous['average'], 2),
                'previous_total_percent' => round($previous['total_percent'], 2),
                'previous_avg_percent' => round($previous['avg_percent'], 2),

                // Change calculations
                'change_value' => $current['total'] - $previous['total'],
                'change_avg' => round($current['average'] - $previous['average'], 2),
                'change_rate' => $this->calculatePercentageChange($current['total'], $previous['total']),
                'change_avg_rate' => $this->calculatePercentageChange($current['average'], $previous['average']),
                'change_total_percent' => round($current['total_percent'] - $previous['total_percent'], 2),
                'change_avg_percent' => round($current['avg_percent'] - $previous['avg_percent'], 2),
            ];
        }

        return $result;
    }

    /**
     * Calculate percentage change with safe division and capped at ±100%
     */
    private function calculatePercentageChange(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.00 : 0.00;
        }

        $changeRate = (($current - $previous) / $previous) * 100;

        // Cap the change rate at ±100%
        return round(max(-100, min(100, $changeRate)), 2);
    }

    /**
     * Get available filter options
     */
    public function getFilterOptions(): array
    {
        return self::FILTER_OPTIONS;
    }

    /**
     * Validate date range input
     */
    public function validateDateRange(?array $dateRange): bool
    {
        if (!$dateRange || !isset($dateRange['start']) || !isset($dateRange['end'])) {
            return false;
        }

        try {
            $start = Carbon::parse($dateRange['start']);
            $end = Carbon::parse($dateRange['end']);

            return $start->lte($end) && $end->lte(Carbon::now());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get chart data for visualization
     */
    public function getChartData(
        string $filter = 'last_week',
        ?array $dateRange = null,
        ?array $genres = null,
        ?string $trackUrn = null,
        ?string $actionType = null
    ): array {
        $userUrn = user()->urn;
        $periods = $this->calculatePeriods($filter, $dateRange);

        $data = $this->fetchOptimizedAnalyticsData(
            $userUrn,
            $periods['current']['start'],
            $periods['current']['end'],
            $genres,
            $trackUrn,
            $actionType
        );

        // Group by date for chart
        $chartData = $data->groupBy('date')->map(function ($group, $date) {
            $dailyMetrics = ['date' => $date];
            foreach (self::METRICS as $metric) {
                $dailyMetrics[$metric] = $group->sum($metric);
            }
            return $dailyMetrics;
        })->values()->toArray();

        return $chartData;
    }

    /**
     * Get top performing tracks (optimized)
     */
    public function getTopTracks(int $limit = 20, ?string $userUrn = null, ?string $filter = 'last_week', ?array $dateRange = null): array
    {
        $userUrn = $userUrn ?? user()->urn;
        $periods = $this->calculatePeriods($filter, $dateRange);

        $topTracks = UserAnalytics::where('user_urn', $userUrn)
            ->whereDate('date', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('date', '<=', $periods['current']['end']->format('Y-m-d'))
            ->selectRaw('track_urn, SUM(total_views) as total_views, SUM(total_plays) as total_streams, SUM(total_likes) as total_likes, SUM(total_reposts) as total_reposts')
            ->groupBy('track_urn')
            ->orderByDesc('total_views')
            ->orderByDesc('total_streams')
            ->orderByDesc('total_likes')
            ->orderByDesc('total_reposts')
            ->with(['track.user'])
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'track' => $item->track ? $item->track->toArray() : null,
                    'streams' => (int) $item->total_streams,
                    'likes' => (int) $item->total_likes,
                    'reposts' => (int) $item->total_reposts,
                    'engagement_rate' => $item->total_streams > 0 ?
                        round((($item->total_likes + $item->total_reposts) / $item->total_streams) * 100, 1) : 0
                ];
            })
            ->filter(function ($item) {
                return $item['track'] !== null;
            })
            ->values()
            ->toArray();

        return $topTracks;
    }

    /**
     * Get genre performance breakdown (optimized)
     */
    public function getGenreBreakdown(): array
    {
        $userUrn = user()->urn;

        $genreData = UserAnalytics::where('user_urn', $userUrn)
            ->selectRaw('genre, SUM(total_views) as total_streams')
            ->whereNotNull('genre')
            ->groupBy('genre')
            ->orderByDesc('total_streams')
            ->get();

        $totalStreams = $genreData->sum('total_streams');

        return $genreData->map(function ($item) use ($totalStreams) {
            return [
                'genre' => $item->genre,
                'streams' => $item->total_streams,
                'percentage' => $totalStreams > 0 ? round(($item->total_streams / $totalStreams) * 100, 1) : 0
            ];
        })->toArray();
    }
}
