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
    // public function syncUserAction(
    //     object $source,
    //     string $actUserUrn,
    //     int $type,
    //     ?string $ipAddress = null,

    // ): bool|null {
    //     $ownerUserUrn = $source->user?->urn ?? null;

    //     if ($ipAddress == null) {
    //         $ipAddress = request()->ip();
    //     }

    //     if (!$actUserUrn || !$ownerUserUrn) {
    //         Log::info("Analytics recording skipped - missing user URN", [
    //             'act_user_urn' => $actUserUrn,
    //             'owner_user_urn' => $ownerUserUrn,
    //             'source_id' => $source->id,
    //             'source_type' => get_class($source),
    //             'type' => $type,
    //             'ip_address' => $ipAddress
    //         ]);
    //         return null;
    //     }

    //     // first check on datbase with created at time to check is updated on current or not

    //     $today = Carbon::today();
    //     Log::info("date: {$today} and start date: {$today->startOfDay()}");

    //     $response = UserAnalytics::where('act_user_urn', $actUserUrn)
    //         ->where('source_id', $source->id)
    //         ->where('source_type', get_class($source))
    //         ->where('owner_user_urn', $ownerUserUrn)
    //         ->where('type', $type)
    //         ->where('ip_address', $ipAddress)
    //         ->whereDate('created_at', '>=', $today->startOfDay())
    //         ->first();

    //     // dd('response : ', $response, 'actUserUrn: ',  $actUserUrn, 'ownerUserUrn: ', $ownerUserUrn, 'source_id: ', $source->id, 'source_type: ', get_class($source), 'type: ', $type, 'ip_address: ', $ipAddress, 'logged user: ', user()->urn);
    //     if ($response) {
    //         Log::info("User action update skipped for user: {$actUserUrn} on type: {$type} for source id:{$source->id} and type: " .get_class($source) . " for ip address: {$ipAddress}. Already updated today.");
    //         return false;
    //     }

    //     return true;
    // }

    public function syncUserAction(
        object $source,
        string $actUserUrn,
        int $type,
        ?string $ipAddress = null,
    ): bool|null {
        $ownerUserUrn = $source->user?->urn ?? null;

        if ($ipAddress == null) {
            $ipAddress = request()->ip();
        }

        if (!$actUserUrn || !$ownerUserUrn) {
            Log::info("Analytics recording skipped - missing user URN", [
                'act_user_urn' => $actUserUrn,
                'owner_user_urn' => $ownerUserUrn,
                'source_id' => $source->id,
                'source_type' => get_class($source),
                'type' => $type,
                'ip_address' => $ipAddress
            ]);
            return null;
        }

        $today = Carbon::today()->toDateString();

        // ✅ ADD: Log search parameters
        Log::info("Searching for duplicate with parameters:", [
            'act_user_urn' => $actUserUrn,
            'source_id' => $source->id,
            'source_type' => get_class($source),
            'owner_user_urn' => $ownerUserUrn,
            'type' => $type,
            'ip_address' => $ipAddress,
            'date' => $today
        ]);

        $query = UserAnalytics::where('act_user_urn', $actUserUrn)
            ->where('source_id', $source->id)
            ->where('source_type', get_class($source))
            ->where('owner_user_urn', $ownerUserUrn)
            ->where('type', $type)
            ->where('ip_address', $ipAddress)
            ->whereDate('created_at', $today);

        // ✅ ADD: Log the actual SQL query
        Log::info("SQL Query: " . $query->toSql());
        Log::info("Query Bindings: " . json_encode($query->getBindings()));

        $response = $query->first();

        // ✅ ADD: Log what was found
        if ($response) {
            Log::info("Found existing record:", [
                'id' => $response->id,
                'created_at' => $response->created_at,
                'all_data' => $response->toArray()
            ]);
        } else {
            Log::info("No existing record found - will create new one");
        }

        if ($response) {
            Log::info("User action update skipped for user: {$actUserUrn} on type: {$type} for source id:{$source->id} and type: " . get_class($source) . " for ip address: {$ipAddress}. Already updated today.");
            return false;
        }

        return true;
    }

    public function recordAnalytics(object $source, ?object $actionable = null, int $type, string $genre, $actUserUrn = null): UserAnalytics|bool|null
    {
        // Get the owner's URN from the track model.
        $ownerUserUrn = ($actionable ? $actionable?->user?->urn : $source?->user?->urn);
        $actUserUrn = $actUserUrn ?? user()->urn;

        // If no user URN is found, log and exit early.
        if (!$ownerUserUrn) {
            Log::info("User action update skipped for {$ownerUserUrn} on {$type} for source id:{$source->id} and type:" . get_class($source) . " . No user URN found.");
            return null;
        }
        // Use the new reusable method to check if the update is allowed.
        $syncAction = $this->syncUserAction($source, $actUserUrn, $type);
        if (!$syncAction) {
            return false;
        }
        Log::info("Start User action update for {$ownerUserUrn} on {$type} for source id:{$source->id} and type: " . get_class($source) . " and actuser urn: {$actUserUrn}.");

        // Find or create the UserAnalytics record based on the unique combination if created_at is today then update else create.
        $analytics = UserAnalytics::create([
            'owner_user_urn' => $ownerUserUrn,
            'act_user_urn' => $actUserUrn,
            'source_id' => $source->id,
            'source_type' => get_class($source),
            'actionable_id' => $actionable ? $actionable->id : null,
            'actionable_type' => $actionable ? get_class($actionable) : null,
            'ip_address' => request()->ip(),
            'type' => $type,
            'genre' => $genre == '' ? 'anyGenre' : $genre,
        ]);
        Log::info("User action updated for {$ownerUserUrn} on {$type} for source id:{$source->id} and actuser urn: {$actUserUrn}. analytics:" . json_encode($analytics));

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
        'current_year' => 'Current Year',
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
        ?object $source = null,
        ?string $actionableType = null,
        ?string $ownerUserUrn = null,
        ?string $actUserUrn = null
    ): array {

        // Get date ranges for current and previous periods
        $periods = $this->calculatePeriods($filter, $dateRange);

        // Fetch analytics data for both periods
        $allData = $this->fetchAnalyticsData(
            ownerUserUrn: $ownerUserUrn,
            startDate: $periods['previous']['start'],
            endDate: $periods['current']['end'],
            genres: $genres,
            source: $source,
            actionableType: $actionableType,
            actUserUrn: $actUserUrn
        );
        // Separate current and previous period data
        $currentData = $allData->filter(function ($item) use ($periods) {
            $itemDate = Carbon::parse($item->created_at);
            return $itemDate->between($periods['current']['start'], $periods['current']['end']);
        });

        $previousData = $allData->filter(function ($item) use ($periods) {
            $itemDate = Carbon::parse($item->created_at);
            return $itemDate->between($periods['previous']['start'], $periods['previous']['end']);
        });

        // Calculate metrics for both periods
        $currentMetrics = $this->calculateMetrics($currentData);
        $previousMetrics = $this->calculateMetrics($previousData);
        $overallAnalytics = $this->buildComparisonResult($currentMetrics, $previousMetrics);
        // Calculate track-specific metrics
        // $currentSourceMetrics = $this->calculateMetricsBySource($currentData);
        // $previousSourceMetrics = $this->calculateMetricsBySource($previousData);
        // $sourceAnalytics = $this->buildComparisonResultBySource($currentSourceMetrics, $previousSourceMetrics);

        return [
            'overall_metrics' => $overallAnalytics,
            // 'metrics' => $sourceAnalytics,
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
     * Fetch analytics data from database
     */
    private function fetchAnalyticsData(
        ?string $ownerUserUrn = null,
        Carbon $startDate,
        Carbon $endDate,
        ?array $genres = null,
        ?object $source = null,
        ?string $actionableType = null,
        ?string $actUserUrn = null
    ): Collection {
        $query = UserAnalytics::query();

        if ($ownerUserUrn !== null) {
            $query->where('owner_user_urn', $ownerUserUrn);
        }

        if ($actUserUrn !== null) {
            $query->where('act_user_urn', $actUserUrn);
        }

        $query->whereDate('created_at', '>=', $startDate->format('Y-m-d'))
            ->whereDate('created_at', '<=', $endDate->format('Y-m-d'));

        if ($source !== null) {
            $query->where('source_id', $source->id)
                ->where('source_type', get_class($source));
        }

        if ($actionableType !== null) {
            $query->where('actionable_type', $actionableType);
        }

        if ($genres && !in_array('Any Genre', $genres)) {
            $query->forGenres($genres);
        }

        return $query->get();
    }



    public function getPaginatedAnalytics(
        string $filter = 'last_week',
        ?array $dateRange = null,
        ?array $genres = null,
        int $perPage = 10,
        int $page = 1,
        ?string $pageName = 'page',
        ?string $userUrn = null,
        ?string $actionableType = null
    ): LengthAwarePaginator {
        $periods = $this->calculatePeriods($filter, $dateRange);

        // Fetch ALL relevant current and previous data without paginating first
        $currentData = $this->fetchAnalyticsData(
            ownerUserUrn: $userUrn,
            startDate: $periods['current']['start'],
            endDate: $periods['current']['end'],
            genres: $genres,
            source: null,
            actionableType: $actionableType
        );

        $previousData = $this->fetchAnalyticsData(
            ownerUserUrn: $userUrn,
            startDate: $periods['previous']['start'],
            endDate: $periods['previous']['end'],
            genres: $genres,
            source: null,
            actionableType: $actionableType
        );

        // Calculate metrics for the full dataset
        $currentMetricsBySource = $this->calculateMetricsBySource($currentData);
        $previousMetricsBySource = $this->calculateMetricsBySource($previousData);

        // Build the full, unpaginated comparison result
        $analytics = $this->buildComparisonResultBySource($currentMetricsBySource, $previousMetricsBySource);

        // Manually create a paginator from the full collection
        $total = $analytics->count();
        $paginatedResults = $analytics->forPage($page, $perPage);

        // Return the new LengthAwarePaginator instance
        return new LengthAwarePaginator(
            $paginatedResults,
            $total,
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    private function getGrowthPercentage($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $previous) / $previous) * 100;
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
        // return match ($filter) {
        //     'daily' => $now->copy()->startOfDay(),
        //     'last_week' => $now->copy()->subDays(6)->startOfDay(),
        //     'last_month' => $now->copy()->subDays(29)->startOfDay(),
        //     'last_90_days' => $now->copy()->subDays(89)->startOfDay(),
        //     'last_year' => $now->copy()->subDays(364)->startOfDay(),
        //     'current_year' => $now->copy()->startOfYear(),
        //     default => $now->copy()->subDays(6)->startOfDay(),
        // };

        return match ($filter) {
            'daily' => $now->copy()->startOfDay(),
            'last_week' => $now->copy()->subWeeks(1)->startOfDay(),
            'last_month' => $now->copy()->subMonths(1)->startOfDay(),
            'last_90_days' => $now->copy()->subDays(90)->startOfDay(),
            'last_year' => $now->copy()->subYears(1)->startOfYear(),
            'current_year' => $now->startOfYear(),
            default => $now->copy()->subWeeks(1)->startOfDay(),
        };
    }

    /**
     * Calculate metrics from analytics data, grouped by source
     */
    private function calculateMetricsBySource(Collection $data): array
    {
        $metricsBySource = [];

        $data->groupBy('source')->each(function ($sourceGroup, $source) use (&$metricsBySource) {


            $sourceDetails = $sourceGroup->first()->source;
            $actionableDetails = $sourceGroup->first()->actionable ?? null;
            if ($sourceDetails && method_exists($sourceDetails, 'format')) {
                $sourceDetails['created_at_formatted'] = $sourceDetails->created_at->format('M d, Y');
            }
            if ($actionableDetails && method_exists($actionableDetails, 'format')) {
                $actionableDetails['created_at_formatted'] = $actionableDetails->created_at->format('M d, Y');
            }

            // Group by action type and count
            $typeGroups = $sourceGroup->groupBy('type');

            $metrics = [
                'total_plays' => $typeGroups->get(UserAnalytics::TYPE_PLAY, collect())->count(),
                'total_likes' => $typeGroups->get(UserAnalytics::TYPE_LIKE, collect())->count(),
                'total_comments' => $typeGroups->get(UserAnalytics::TYPE_COMMENT, collect())->count(),
                'total_views' => $typeGroups->get(UserAnalytics::TYPE_VIEW, collect())->count(),
                'total_requests' => $typeGroups->get(UserAnalytics::TYPE_REQUEST, collect())->count(),
                'total_reposts' => $typeGroups->get(UserAnalytics::TYPE_REPOST, collect())->count(),
                'total_followers' => $typeGroups->get(UserAnalytics::TYPE_FOLLOW, collect())->count(),
            ];

            $metricsBySource[$source] = [
                'source_type' => $sourceGroup->first()->source_type,
                'source_details' => $sourceDetails,
                'actionable_details' => $actionableDetails,
                'metrics' => $metrics
            ];
        });

        return $metricsBySource;
    }

    /**
     * Compare and build final result, grouped by track
     */
    // private function buildComparisonResultByTrack(array $currentMetrics, array $previousMetrics): array
    // {
    //     $result = [];
    //     $allTrackUrns = array_unique(array_merge(array_keys($currentMetrics), array_keys($previousMetrics)));

    //     foreach ($allTrackUrns as $trackUrn) {
    //         $current = $currentMetrics[$trackUrn] ?? null;
    //         $previous = $previousMetrics[$trackUrn] ?? null;

    //         $trackName = $current['track_name'] ?? $previous['track_name'] ?? 'Unknown Track';
    //         $trackDetails = $current['track_details'] ?? $previous['track_details'] ?? null;
    //         $actionDetails = $current['actionable_details'] ?? $previous['actionable_details'] ?? null;

    //         $trackResult = [
    //             'track_urn' => $trackUrn,
    //             'track_name' => $trackName,
    //             'track_details' => $trackDetails,
    //             'actionable_details' => $actionDetails
    //         ];

    //         $metrics = ['total_plays', 'total_likes', 'total_comments', 'total_views', 'total_requests', 'total_reposts', 'total_followers'];

    //         foreach ($metrics as $metric) {
    //             $currentTotal = $current['metrics'][$metric] ?? 0;
    //             $previousTotal = $previous['metrics'][$metric] ?? 0;

    //             $trackResult['metrics'][$metric] = [
    //                 'current_total' => $currentTotal,
    //                 'previous_total' => $previousTotal,
    //                 'change_rate' => $this->calculatePercentageChange($currentTotal, $previousTotal)
    //             ];
    //         }
    //         $result[] = $trackResult;
    //     }

    //     return $result;
    // }

    // private function buildComparisonResultBySource(array $currentMetrics, array $previousMetrics): array
    // {
    //     $result = [];
    //     $allSources = array_unique(array_merge(array_keys($currentMetrics), array_keys($previousMetrics)));

    //     foreach ($allSources as $source) {
    //         $current = $currentMetrics[$source] ?? null;
    //         $previous = $previousMetrics[$source] ?? null;

    //         $sourceDetails = $current['source_details'] ?? $previous['source_details'] ?? null;
    //         $actionDetails = $current['actionable_details'] ?? $previous['actionable_details'] ?? null;

    //         $sourceResult = [
    //             'source_type' => $current['source_type'] ?? $previous['source_type'] ?? 'Unknown',
    //             'source_details' => $sourceDetails,
    //             'actionable_details' => $actionDetails
    //         ];

    //         foreach (self::METRICS as $metric) {
    //             $currentTotal = $current['metrics'][$metric] ?? 0;
    //             $previousTotal = $previous['metrics'][$metric] ?? 0;

    //             $sourceResult['metrics'][$metric] = [
    //                 'current_total' => $currentTotal,
    //                 'previous_total' => $previousTotal,
    //                 'change_rate' => $this->calculatePercentageChange($currentTotal, $previousTotal)
    //             ];
    //         }
    //         $result[] = $sourceResult;
    //     }

    //     return $result;
    // }

    private function buildComparisonResultBySource(array $currentMetrics, array $previousMetrics): Collection
    {
        $result = collect(); // Use a Laravel collection
        $allSources = array_unique(array_merge(array_keys($currentMetrics), array_keys($previousMetrics)));

        foreach ($allSources as $source) {
            $current = $currentMetrics[$source] ?? null;
            $previous = $previousMetrics[$source] ?? null;

            $sourceDetails = $current['source_details'] ?? $previous['source_details'] ?? null;
            $actionDetails = $current['actionable_details'] ?? $previous['actionable_details'] ?? null;

            $sourceResult = [
                'source_type' => $current['source_type'] ?? $previous['source_type'] ?? 'Unknown',
                'source_details' => $sourceDetails,
                'actionable_details' => $actionDetails,
                'metrics' => [] // Initialize metrics as an empty array
            ];

            foreach (self::METRICS as $metric) {
                $currentTotal = $current['metrics'][$metric] ?? 0;
                $previousTotal = $previous['metrics'][$metric] ?? 0;

                $sourceResult['metrics'][$metric] = [
                    'current_total' => $currentTotal,
                    'previous_total' => $previousTotal,
                    'change_rate' => $this->calculatePercentageChange($currentTotal, $previousTotal)
                ];
            }
            $result->push($sourceResult); // Add the item to the collection
        }

        return $result;
    }

    /**
     * Calculate metrics from analytics data
     */
    private function calculateMetrics(Collection $data): array
    {
        if ($data->isEmpty()) {
            return $this->getEmptyMetrics();
        }

        // Group by date and calculate daily metrics
        $dailyData = $data->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($dayGroup) {
            $typeGroups = $dayGroup->groupBy('type');
            return [
                'total_plays' => $typeGroups->get(UserAnalytics::TYPE_PLAY, collect())->count(),
                'total_likes' => $typeGroups->get(UserAnalytics::TYPE_LIKE, collect())->count(),
                'total_comments' => $typeGroups->get(UserAnalytics::TYPE_COMMENT, collect())->count(),
                'total_views' => $typeGroups->get(UserAnalytics::TYPE_VIEW, collect())->count(),
                'total_requests' => $typeGroups->get(UserAnalytics::TYPE_REQUEST, collect())->count(),
                'total_reposts' => $typeGroups->get(UserAnalytics::TYPE_REPOST, collect())->count(),
                'total_followers' => $typeGroups->get(UserAnalytics::TYPE_FOLLOW, collect())->count(),
            ];
        });

        // return $dailyData->values()->all();

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

        // // Calculate rates (percentages based on views)
        $totalViews = $metrics['total_views']['total'];
        $avgViews = $metrics['total_views']['average'];

        foreach (self::METRICS as $metric) {
            if ($metric === 'total_views') {
                $metrics[$metric]['total_percent'] = 100;
                $metrics[$metric]['avg_percent'] = 100;
                continue;
            }

            $metrics[$metric]['total_percent'] = $totalViews > 0 ? min(100, ($metrics[$metric]['total'] / $totalViews) * 100) : 0;
            $metrics[$metric]['avg_percent'] = $avgViews > 0 ? min(100, ($metrics[$metric]['average'] / $avgViews) * 100) : 0;
        }

        return $metrics;
    }

    /**
     * Return empty metrics structure
     */
    private function getEmptyMetrics(): array
    {
        $metrics = [];
        $metricTypes = ['total_plays', 'total_likes', 'total_comments', 'total_views', 'total_requests', 'total_reposts', 'total_followers'];

        foreach ($metricTypes as $metric) {
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
        $metricTypes = ['total_plays', 'total_likes', 'total_comments', 'total_views', 'total_requests', 'total_reposts', 'total_followers'];

        foreach ($metricTypes as $metric) {
            $current = $currentMetrics[$metric];
            $previous = $previousMetrics[$metric];
            $result[$metric] = [
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
        ?object $source = null,
        ?string $actionableType = null,
        ?string $ownerUserUrn = null,
        ?string $actUserUrn = null
    ): array {
        if ($actUserUrn == null) {
            $ownerUserUrn = $ownerUserUrn ?? user()->urn;
        } else {
            $ownerUserUrn = null;
        }
        $periods = $this->calculatePeriods($filter, $dateRange);

        $data = $this->fetchAnalyticsData(
            ownerUserUrn: $ownerUserUrn,
            startDate: $periods['current']['start'],
            endDate: $periods['current']['end'],
            genres: $genres,
            source: $source,
            actionableType: $actionableType,
            actUserUrn: $actUserUrn
        );

        // Group by date for chart
        $chartData = $data->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($group, $date) {
            $typeGroups = $group->groupBy('type');
            $total_plays = $typeGroups->get(UserAnalytics::TYPE_PLAY, collect())->count();
            $total_likes = $typeGroups->get(UserAnalytics::TYPE_LIKE, collect())->count();
            $total_comments = $typeGroups->get(UserAnalytics::TYPE_COMMENT, collect())->count();
            $total_views = $typeGroups->get(UserAnalytics::TYPE_VIEW, collect())->count();
            $total_requests = $typeGroups->get(UserAnalytics::TYPE_REQUEST, collect())->count();
            $total_reposts = $typeGroups->get(UserAnalytics::TYPE_REPOST, collect())->count();
            $total_followers = $typeGroups->get(UserAnalytics::TYPE_FOLLOW, collect())->count();
            $avg_total = ($total_plays + $total_likes + $total_comments + $total_requests + $total_reposts + $total_followers) / 6;
            $avg_activities_rate = $total_views >= $avg_total ? round(min(100, ($avg_total / $total_views) * 100), 2) : 0;
            return [
                'date' => $date,
                'total_plays' => $total_plays,
                'total_likes' => $total_likes,
                'total_comments' => $total_comments,
                'total_views' => $total_views,
                'total_requests' => $total_requests,
                'total_reposts' => $total_reposts,
                'total_followers' => $total_followers,
                'avg_activities_rate' => round($avg_activities_rate, 2),

            ];
        })->values()->toArray();
        return $chartData;
    }

    /**
     * Get top performing sources
     */
    // public function getTopSources(int $limit = 20, ?string $userUrn = null, ?string $filter = 'last_week', ?array $dateRange = null): array
    // {
    //     $periods = $this->calculatePeriods($filter, $dateRange);

    //     $query = UserAnalytics::query();

    //     if ($userUrn !== null) {
    //         $query->where('owner_user_urn', $userUrn);
    //     }

    //     // Start the query chain and store the final result directly in $topSources
    //     $topSources = $query->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
    //         ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'))
    //         ->select([
    //             'source_id',
    //             'source_type',
    //             'owner_user_urn',
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 END) as total_views'),
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_PLAY . ' THEN 1 END) as total_streams'),
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_likes'),
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 END) as total_comments'),
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 END) as total_reposts'),
    //             DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 END) as total_followers'),
    //         ])
    //         ->groupBy('source_id', 'source_type', 'owner_user_urn')
    //         ->orderByDesc('total_views')
    //         ->orderByDesc('total_streams')
    //         ->orderByDesc('total_likes')
    //         ->orderByDesc('total_reposts')
    //         ->orderByDesc('total_followers')
    //         ->with(['source.user', 'ownerUser'])
    //         ->limit($limit)
    //         ->get()
    //         ->map(function ($item) {
    //             // Calculate avg_total first
    //             $avg_total = ($item->total_likes + $item->total_reposts + $item->total_followers + $item->total_streams + $item->total_comments) / 5;

    //             // Apply the new engagement rate logic
    //             $engagement_rate = 0;
    //             if ($item->total_views >= $avg_total) {
    //                 $engagement_rate = round(min(100, ($avg_total / ($item->total_views == 0 ? 1 : $item->total_views)) * 100), 2);
    //             }

    //             return [
    //                 'source' => $item->source,
    //                 'source_type' => $item->source_type,
    //                 'views' => (int) $item->total_views,
    //                 'streams' => (int) $item->total_streams,
    //                 'likes' => (int) $item->total_likes,
    //                 'comments' => (int) $item->total_comments,
    //                 'reposts' => (int) $item->total_reposts,
    //                 'followers' => (int) $item->total_followers,
    //                 'avg_total' => $avg_total,
    //                 'engagement_rate' => $engagement_rate,
    //             ];
    //         })
    //         ->filter(function ($item) {
    //             return $item['source'] !== null;
    //         })
    //         ->values()
    //         ->toArray();

    //     return $topSources;
    // }

    public function getTopSources(int $limit = 20, ?string $userUrn = null, ?string $filter = 'last_week', ?array $dateRange = null, ?string $actionableType = null): array
    {
        $periods = $this->calculatePeriods($filter, $dateRange);

        $query = UserAnalytics::query();

        // 🔹 Filter by user URN if provided
        if ($userUrn !== null) {
            $query->where('owner_user_urn', $userUrn);
        }

        // 🔹 Filter by actionable type if provided
        if ($actionableType !== null) {
            $query->whereNotNull('actionable_id')
                ->where('actionable_type', $actionableType);
        }

        // 🔹 Apply date filter
        $query->whereBetween('created_at', [
            $periods['current']['start']->format('Y-m-d'),
            $periods['current']['end']->format('Y-m-d'),
        ]);

        // 🔹 Get one actionable_id per source using subquery or MAX/MIN
        $topSources = $query->select([
            'source_id',
            'source_type',
            DB::raw('MAX(actionable_id) as actionable_id'), // Get one actionable per source
            DB::raw('MAX(actionable_type) as actionable_type'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 ELSE 0 END) as total_views'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_PLAY . ' THEN 1 ELSE 0 END) as total_streams'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 ELSE 0 END) as total_likes'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 ELSE 0 END) as total_comments'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 ELSE 0 END) as total_reposts'),
            DB::raw('SUM(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 ELSE 0 END) as total_followers'),
            DB::raw('
                CASE WHEN SUM(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 ELSE 0 END) > 0 THEN
                    LEAST(100, (
                        (
                            SUM(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN type = ' . UserAnalytics::TYPE_PLAY . ' THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 ELSE 0 END)
                        ) / 5
                    ) / SUM(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 ELSE 0 END) * 100
                    )
                ELSE 0 END as engagement_rate
            ')
        ])
            ->groupBy(['source_id', 'source_type'])
            ->orderByDesc('engagement_rate')
            ->take($limit)
            ->get();

        // 🔹 Eager load both source and actionable in one go
        $topSources->load([
            'source',
            'actionable',
            'source.user', // User related to the source model
            'actionable.user', // User related to the actionable model
            'actionable.music', // Music related to the actionable model
            'ownerUser', // Direct UserAnalytics relations
            'actUser'    // Direct UserAnalytics relations
        ]);

        // 🔹 Map results
        $topSourcesFormatted = $topSources->map(function ($item) {
            return [
                'actionable' => $item->actionable ?? null,
                'source' => $item->source ?? null,
                'source_type' => $item->source_type,
                'views' => (int) $item->total_views,
                'streams' => (int) $item->total_streams,
                'likes' => (int) $item->total_likes,
                'comments' => (int) $item->total_comments,
                'reposts' => (int) $item->total_reposts,
                'followers' => (int) $item->total_followers,
                'engagement_rate' => round($item->engagement_rate, 2),
                'engagement_score' => round(($item->engagement_rate / 100) * 10, 2),
            ];
        });

        return $topSourcesFormatted->toArray();
    }

    /**
     * Get genre performance breakdown
     */
    public function getGenreBreakdown(?string $filter = 'last_week', ?array $dateRange = null, ?array $genres = null, ?array $userGenres = null): array
    {
        $userUrn = user()->urn;

        $periods = $this->calculatePeriods($filter, $dateRange);

        $query = UserAnalytics::where('owner_user_urn', $userUrn)
            ->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'));

        // Check if a genre filter is applied and apply it
        if ($genres && !in_array('Any Genre', $genres)) {
            $query->forGenres($genres);
        }
        // If userGenres are passed, filter the query by them
        if ($userGenres && !in_array('Any Genre', $userGenres)) {
            $query->forGenres($userGenres);
        }

        $genreData = $query->select([
            'genre',
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 END) as total_views'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_PLAY . ' THEN 1 END) as total_streams'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_likes'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 END) as total_comments'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 END) as total_reposts'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 END) as total_followers'),
        ])
            ->groupBy('genre')
            ->whereNotNull('genre')
            ->get();

        // Convert the query results to a collection keyed by genre name for easy lookup
        $genreDataMap = $genreData->keyBy('genre');

        $finalGenreData = collect();

        // Iterate through the full list of genres provided by the user
        // This is the key change that ensures all genres are included
        if ($userGenres) {
            foreach ($userGenres as $userGenre) {
                // Find the analytics data for the current genre
                $dataItem = $genreDataMap->get($userGenre);
                if ($dataItem) {
                    // If data exists, use the counts from the database
                    $finalGenreData->push([
                        'genre' => $userGenre,
                        'streams' => $dataItem->total_streams,
                        'avg_total' => ($dataItem->total_likes + $dataItem->total_reposts + $dataItem->total_followers + $dataItem->total_streams + $dataItem->total_comments) / 5,
                    ]);
                } else {
                    // If no data exists for this genre, add it with all metrics as 0
                    $finalGenreData->push([
                        'genre' => $userGenre,
                        'streams' => 0,
                        'avg_total' => 0,
                    ]);
                }
            }
        } else {
            // Fallback: If no userGenres are provided, just use the data from the query
            $finalGenreData = $genreData->map(function ($item) {
                return [
                    'genre' => $item->genre,
                    'streams' => $item->total_streams,
                    'avg_total' => ($item->total_likes + $item->total_reposts + $item->total_followers + $item->total_streams + $item->total_comments) / 5,
                ];
            });
        }

        // Now, calculate the total sum from the complete dataset
        $totalAvgSum = $finalGenreData->sum('avg_total');

        // Calculate final percentages
        $genreDataWithPercentage = $finalGenreData->map(function ($item) use ($totalAvgSum) {
            $percentage = ($totalAvgSum > 0) ? round(($item['avg_total'] / $totalAvgSum) * 100, 2) : 0;
            return [
                'genre' => $item['genre'],
                'streams' => $item['streams'],
                'percentage' => $percentage,
            ];
        })->toArray();

        // Adjust for rounding errors
        $totalPercentage = collect($genreDataWithPercentage)->sum('percentage');
        if (count($genreDataWithPercentage) > 0 && abs(100 - $totalPercentage) > 0.01) {
            usort($genreDataWithPercentage, function ($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });
            $adjustment = 100 - $totalPercentage;
            $genreDataWithPercentage[0]['percentage'] = round($genreDataWithPercentage[0]['percentage'] + $adjustment, 2);
        }

        // Sort the final array by percentage to match the chart order
        usort($genreDataWithPercentage, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });
        return $genreDataWithPercentage;
    }
}
