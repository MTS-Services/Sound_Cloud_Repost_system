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
                'source_type' => $source->getMorphClass(),
                'type' => $type,
                'ip_address' => $ipAddress
            ]);
            return null;
        }

        // first check on datbase with created at time to check is updated on current or not

        $today = Carbon::today();
        Log::info("date: {$today} and start date: {$today->startOfDay()}");

        $response = UserAnalytics::where('act_user_urn', $actUserUrn)
            ->where('source_id', $source->id)
            ->where('source_type', $source->getMorphClass())
            ->where('owner_user_urn', $ownerUserUrn)
            ->where('type', $type)
            ->where('ip_address', $ipAddress)
            ->whereDate('created_at', '>=', $today->startOfDay())
            ->first();

        if ($response) {
            Log::info("User action update skipped for user: {$actUserUrn} on type: {$type} for source id:{$source->id} and type:{$source->getMorphClass()} for ip address: {$ipAddress}. Already updated today.");
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
            Log::info("User action update skipped for {$ownerUserUrn} on {$type} for source id:{$source->id} and type:{$source->getMorphClass()}. No user URN found.");
            return null;
        }
        // Use the new reusable method to check if the update is allowed.
        if (!$this->syncUserAction($source, $actUserUrn, $type)) {
            return false;
        }
        Log::info("Start User action update for {$ownerUserUrn} on {$type} for source id:{$source->id} and type:{$source->getMorphClass()} and actuser urn: {$actUserUrn}.");

        // Find or create the UserAnalytics record based on the unique combination.
        $analytics = UserAnalytics::updateOrCreate(
            [
                'owner_user_urn' => $ownerUserUrn,
                'act_user_urn' => $actUserUrn,
                'source_id' => $source->id,
                'source_type' => $source->getMorphClass(),
                'actionable_id' => $actionable ? $actionable->id : null,
                'actionable_type' => $actionable ? $actionable->getMorphClass() : null,
                'ip_address' => request()->ip(),
                'type' => $type,

            ],
            [
                'genre' => $genre == '' ? 'anyGenre' : $genre,
            ]

        );
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
        ?string $actionableType = null
    ): array {
        $userUrn = user()->urn;

        // Get date ranges for current and previous periods
        $periods = $this->calculatePeriods($filter, $dateRange);

        // Fetch analytics data for both periods
        $allData = $this->fetchAnalyticsData(
            $userUrn,
            $periods['previous']['start'],
            $periods['current']['end'],
            $genres,
            $source,
            $actionableType
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
        ?string $userUrn = null,
        Carbon $startDate,
        Carbon $endDate,
        ?array $genres = null,
        ?object $source = null,
        ?string $actionableType = null
    ): Collection {
        $query = UserAnalytics::query();

        if ($userUrn !== null) {
            $query->where('owner_user_urn', $userUrn);
        }

        $query->whereDate('created_at', '>=', $startDate->format('Y-m-d'))
            ->whereDate('created_at', '<=', $endDate->format('Y-m-d'));

        if ($source !== null) {
            $query->where('source_id', $source->id)
                ->where('source_type', $source->getMorphClass());
        }

        if ($actionableType !== null) {
            $query->where('actionable_type', $actionableType);
        }

        if ($genres && !in_array('Any Genre', $genres)) {
            $query->forGenres($genres);
        }

        return $query->get();
    }

    /**
     * Get paginated track analytics data
     */
    // public function getPaginatedAnalytics(
    //     string $filter = 'last_week',
    //     ?array $dateRange = null,
    //     ?array $genres = null,
    //     int $perPage = 10,
    //     int $page = 1,
    //     ?string $pageName = 'page',
    //     ?string $userUrn = null,
    //     ?string $actionableType = null
    // ): LengthAwarePaginator {
    //     $periods = $this->calculatePeriods($filter, $dateRange);

    //     // Build the base query for aggregated track data
    //     $query = UserAnalytics::select([
    //         'source_id',
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 END) as total_views'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_plays'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_likes'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 END) as total_reposts'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 END) as total_comments'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REQUEST . ' THEN 1 END) as total_requests'),
    //         DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 END) as total_followers'),
    //     ]);

    //     if ($userUrn !== null) {
    //         $query->where('owner_user_urn', $userUrn);
    //     }

    //     if ($actionableType !== null) {
    //         $query->where('actionable_type', $actionableType);
    //     }

    //     $query->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
    //         ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'));

    //     if ($genres) {
    //         $query->forGenres($genres);
    //     }

    //     // Apply groupBy and orderBy, then paginate directly from the query builder
    //     $paginatedSourceData = $query->groupBy('source_id')
    //         ->orderByDesc('total_views')
    //         ->orderByDesc('total_reposts')
    //         ->paginate($perPage, ['*'], $pageName, $page);

    //     // Get detailed analytics for paginated sources
    //     $analytics = [];
    //     if ($paginatedSourceData->isNotEmpty()) {
    //         $sourceIdsList = $paginatedSourceData->pluck('source_id')->toArray();

    //         // Fetch current and previous period data for only the paginated IDs
    //         $currentData = $this->fetchAnalyticsData(
    //             $userUrn,
    //             $periods['current']['start'],
    //             $periods['current']['end'],
    //             $genres
    //         )->whereIn('source_id', $sourceIdsList);

    //         $previousData = $this->fetchAnalyticsData(
    //             $userUrn,
    //             $periods['previous']['start'],
    //             $periods['previous']['end'],
    //             $genres
    //         )->whereIn('source_id', $sourceIdsList);

    //         $currentMetricsBySource = $this->calculateMetricsBySource($currentData);
    //         $previousMetricsBySource = $this->calculateMetricsBySource($previousData);
    //         $analytics = $this->buildComparisonResultBySource($currentMetricsBySource, $previousMetricsBySource);
    //     }

    //     // Return the paginated data with the correct total and items per page
    //     return new LengthAwarePaginator(
    //         $analytics,
    //         $paginatedSourceData->total(),
    //         $paginatedSourceData->perPage(),
    //         $paginatedSourceData->currentPage(),
    //         [
    //             'path' => request()->url(),
    //             'pageName' => $pageName,
    //         ]
    //     );
    // }


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

        // Build the base query for aggregated track data
        $query = UserAnalytics::query();

        if ($userUrn !== null) {
            $query->where('owner_user_urn', $userUrn);
        }

        if ($actionableType !== null) {
            $query->where('actionable_type', $actionableType);
        }

        $query->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'));

        if ($genres && !in_array('Any Genre', $genres)) {
            $query->forGenres($genres);
        }


        $query->select([
            'source_id',
            'source_type',
            'actionable_id',
            'actionable_type',
            'act_user_urn',
            'owner_user_urn',
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 END) as total_views'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_plays'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_likes'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 END) as total_reposts'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 END) as total_comments'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REQUEST . ' THEN 1 END) as total_requests'),
            DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 END) as total_followers'),
        ]);

        // Apply groupBy and orderBy, then paginate directly from the query builder
        $paginatedSourceData = $query->groupBy('source_id', 'source_type', 'actionable_id', 'actionable_type', 'act_user_urn', 'owner_user_urn')
            ->orderByDesc('total_views')
            ->orderByDesc('total_reposts')
            ->paginate($perPage, ['*'], $pageName, $page);
        // Get the list of source IDs for the current page
        $sourceIdsList = $paginatedSourceData->pluck('source_id')->toArray();

        // Fetch current and previous period data for only the paginated IDs
        $currentData = $this->fetchAnalyticsData(
            $userUrn,
            $periods['current']['start'],
            $periods['current']['end'],
            $genres
        )->whereIn('source_id', $sourceIdsList);

        $previousData = $this->fetchAnalyticsData(
            $userUrn,
            $periods['previous']['start'],
            $periods['previous']['end'],
            $genres
        )->whereIn('source_id', $sourceIdsList);

        $currentMetricsBySource = $this->calculateMetricsBySource($currentData);
        $previousMetricsBySource = $this->calculateMetricsBySource($previousData);

        // Re-key the arrays with the correct source_id to avoid "Undefined array key" errors
        $currentMetricsBySource = collect($currentMetricsBySource)->mapWithKeys(function ($value, $key) {
            $decodedKey = json_decode($key, true);
            return [$decodedKey['id'] => $value];
        })->toArray();

        $previousMetricsBySource = collect($previousMetricsBySource)->mapWithKeys(function ($value, $key) {
            $decodedKey = json_decode($key, true);
            return [$decodedKey['id'] => $value];
        })->toArray();

        $analytics = $this->buildComparisonResultBySource($currentMetricsBySource, $previousMetricsBySource);

        return new LengthAwarePaginator(
            $analytics,
            $paginatedSourceData->total(),
            $paginatedSourceData->perPage(),
            $paginatedSourceData->currentPage(),
            [
                'path' => request()->url(),
                'pageName' => $pageName,
            ]
        );

        // Use the paginator's 'through' method to transform the data
        // This keeps the pagination intact while adding the detailed analytics
        // return $paginatedSourceData->through(function ($source) use ($currentMetricsBySource, $previousMetricsBySource) {
        //     $sourceId = $source->source_id;

        //     $currentMetrics = $currentMetricsBySource[$sourceId] ?? [
        //         'source_type' => $source->source_type,
        //         'source_details' => $source->source_details,
        //         'actionable_details' => $source->actionable_details,
        //         'metrics' => [
        //             'total_reposts' => 0,
        //             'total_likes' => 0,
        //             'total_comments' => 0,
        //             'total_requests' => 0,
        //             'total_followers' => 0,
        //             'total_views' => 0,
        //         ],
        //     ];

        //     $previousMetrics = $previousMetricsBySource[$sourceId] ?? [
        //         'source_type' => $source->source_type,
        //         'source_details' => $source->source_details,
        //         'actionable_details' => $source->actionable_details,
        //         'metrics' => [
        //             'total_reposts' => 0,
        //             'total_likes' => 0,
        //             'total_comments' => 0,
        //             'total_requests' => 0,
        //             'total_followers' => 0,
        //             'total_views' => 0,
        //         ]
        //     ];

        //     $result = [
        //         'source_id' => $sourceId,
        //         'metrics' => [
        //             'current' => $currentMetrics,
        //             'previous' => $previousMetrics,
        //             'growth' => [
        //                 'views_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_views'], $previousMetrics['metrics']['total_views']),
        //                 'reposts_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_reposts'], $previousMetrics['metrics']['total_reposts']),
        //                 'likes_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_likes'], $previousMetrics['metrics']['total_likes']),
        //                 'comments_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_comments'], $previousMetrics['metrics']['total_comments']),
        //                 'requests_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_requests'], $previousMetrics['metrics']['total_requests']),
        //                 'followers_growth' => $this->getGrowthPercentage($currentMetrics['metrics']['total_followers'], $previousMetrics['metrics']['total_followers']),
        //             ],
        //         ]
        //     ];

        //     return $result;
        // });
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

    private function buildComparisonResultBySource(array $currentMetrics, array $previousMetrics): array
    {
        $result = [];
        $allSources = array_unique(array_merge(array_keys($currentMetrics), array_keys($previousMetrics)));

        foreach ($allSources as $source) {
            $current = $currentMetrics[$source] ?? null;
            $previous = $previousMetrics[$source] ?? null;

            $sourceDetails = $current['source_details'] ?? $previous['source_details'] ?? null;
            $actionDetails = $current['actionable_details'] ?? $previous['actionable_details'] ?? null;

            $sourceResult = [
                'source_type' => $current['source_type'] ?? $previous['source_type'] ?? 'Unknown',
                'source_details' => $sourceDetails,
                'actionable_details' => $actionDetails
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
            $result[] = $sourceResult;
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
        ?string $actionableType = null
    ): array {
        $userUrn = user()->urn;
        $periods = $this->calculatePeriods($filter, $dateRange);

        $data = $this->fetchAnalyticsData(
            $userUrn,
            $periods['current']['start'],
            $periods['current']['end'],
            $genres,
            $source,
            $actionableType
        );

        // Group by date for chart
        $chartData = $data->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($group, $date) {
            $typeGroups = $group->groupBy('type');
            return [
                'date' => $date,
                'total_plays' => $typeGroups->get(UserAnalytics::TYPE_PLAY, collect())->count(),
                'total_likes' => $typeGroups->get(UserAnalytics::TYPE_LIKE, collect())->count(),
                'total_comments' => $typeGroups->get(UserAnalytics::TYPE_COMMENT, collect())->count(),
                'total_views' => $typeGroups->get(UserAnalytics::TYPE_VIEW, collect())->count(),
                'total_requests' => $typeGroups->get(UserAnalytics::TYPE_REQUEST, collect())->count(),
                'total_reposts' => $typeGroups->get(UserAnalytics::TYPE_REPOST, collect())->count(),
                'total_followers' => $typeGroups->get(UserAnalytics::TYPE_FOLLOW, collect())->count(),
            ];
        })->values()->toArray();
        return $chartData;
    }

    /**
     * Get top performing sources
     */
    public function getTopSources(int $limit = 20, ?string $userUrn = null, ?string $filter = 'last_week', ?array $dateRange = null): array
    {
        $periods = $this->calculatePeriods($filter, $dateRange);

        $query = UserAnalytics::query();

        if ($userUrn !== null) {
            $query->where('owner_user_urn', $userUrn);
        }

        // Start the query chain and store the final result directly in $topSources
        $topSources = $query->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'))
            ->select([
                'source_id',
                'source_type',
                'owner_user_urn',
                'act_user_urn',
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_VIEW . ' THEN 1 END) as total_views'),
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_PLAY . ' THEN 1 END) as total_streams'),
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_LIKE . ' THEN 1 END) as total_likes'),
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_COMMENT . ' THEN 1 END) as total_comments'),
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_REPOST . ' THEN 1 END) as total_reposts'),
                DB::raw('COUNT(CASE WHEN type = ' . UserAnalytics::TYPE_FOLLOW . ' THEN 1 END) as total_followers'),
            ])
            ->groupBy('source_id', 'source_type', 'owner_user_urn', 'act_user_urn')
            ->orderByDesc('total_views')
            ->orderByDesc('total_streams')
            ->orderByDesc('total_likes')
            ->orderByDesc('total_reposts')
            ->orderByDesc('total_followers')
            ->with(['source.user', 'ownerUser', 'actUser'])
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                // Calculate avg_total first
                $avg_total = ($item->total_likes + $item->total_reposts + $item->total_followers + $item->total_streams + $item->total_comments) / 5;

                // Apply the new engagement rate logic
                $engagement_rate = 0;
                if ($item->total_views >= $avg_total) {
                    $engagement_rate = round(min(100, ($avg_total / ($item->total_views == 0 ? 1 : $item->total_views)) * 100), 2);
                }

                return [
                    'source' => $item->source,
                    'source_type' => $item->source_type,
                    'views' => (int) $item->total_views,
                    'streams' => (int) $item->total_streams,
                    'likes' => (int) $item->total_likes,
                    'comments' => (int) $item->total_comments,
                    'reposts' => (int) $item->total_reposts,
                    'followers' => (int) $item->total_followers,
                    'avg_total' => $avg_total,
                    'engagement_rate' => $engagement_rate,
                ];
            })
            ->filter(function ($item) {
                return $item['source'] !== null;
            })
            ->values()
            ->toArray();

        return $topSources;
    }
    /**
     * Get genre performance breakdown
     */
    public function   getGenreBreakdown(?string $filter = 'last_week', ?array $dateRange = null, ?array $genres = null): array
    {
        $userUrn = user()->urn;

        $periods = $this->calculatePeriods($filter, $dateRange);

        $query = UserAnalytics::where('owner_user_urn', $userUrn)
            ->whereDate('created_at', '>=', $periods['current']['start']->format('Y-m-d'))
            ->whereDate('created_at', '<=', $periods['current']['end']->format('Y-m-d'))
            ->where('type', UserAnalytics::TYPE_VIEW);

        if ($genres && !in_array('Any Genre', $genres)) {
            $query->forGenres($genres);
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
            ->orderByDesc('total_views')
            ->orderByDesc('total_streams')
            ->orderByDesc('total_likes')
            ->orderByDesc('total_reposts')
            ->orderByDesc('total_followers')
            ->get();

        $totalViews = $genreData->sum('total_views');
        $totalStreams = $genreData->sum('total_streams');
        $totalLikes = $genreData->sum('total_likes');
        $totalReposts = $genreData->sum('total_reposts');
        $totalFollowers = $genreData->sum('total_followers');
        $totalComments = $genreData->sum('total_comments');

        $avgTotal = ($totalLikes + $totalReposts + $totalFollowers + $totalStreams + $totalComments) / 5;

        return $genreData->map(function ($item) use ($totalViews, $avgTotal) {
            return [
                'genre' => $item->genre,
                'streams' => $item->total_streams,
                'percentage' => $totalViews >= $avgTotal ? round(($avgTotal / ($totalViews == 0 ? 1 : $totalViews)) * 100, 2) : 0,
            ];
        })->toArray();
    }
}
