<?php

namespace App\Services\User;

use App\Models\Campaign;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\UserAnalytics;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $userUrn = $track->user?->urn;
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
     * Get the analytics data for a specific user within a date range.
     *
     * @param string $userUrn The user's URN to filter by.
     * @param string $filter The time filter (daily, last_week, last_month, last_90_days, last_year).
     * @return array The aggregated analytics data.
     */
    // public function getAnalyticsData(string $userUrn, string $filter = 'last_week'): array
    // {
    //     $startDate = $this->getStartDateForFilter($filter);
    //     $endDate = Carbon::now();

    //     $data = DB::table('user_analytics')
    //         ->select(
    //             DB::raw('SUM(total_plays) as streams'),
    //             DB::raw('SUM(total_likes) as likes'),
    //             DB::raw('SUM(total_comments) as comments'),
    //             DB::raw('SUM(total_views) as views'),
    //             DB::raw('SUM(total_requests) as requests'),
    //             DB::raw('SUM(total_reposts) as reposts'),
    //             DB::raw('SUM(total_followers) as followers')
    //         )
    //         ->where('user_urn', $userUrn)
    //         ->whereDate('date', '>=', $startDate)
    //         ->whereDate('date', '<=', $endDate)
    //         ->first();

    //     // Check if data is null, and return default values if so
    //     if (!$data) {
    //         return [
    //             'streams' => 0,
    //             'likes' => 0,
    //             'comments' => 0,
    //             'views' => 0,
    //             'requests' => 0,
    //             'reposts' => 0,
    //             'followers' => 0,
    //         ];
    //     }

    //     return [
    //         'streams' => $data->streams ?? 0,
    //         'likes' => $data->likes ?? 0,
    //         'comments' => $data->comments ?? 0,
    //         'views' => $data->views ?? 0,
    //         'requests' => $data->requests ?? 0,
    //         'reposts' => $data->reposts ?? 0,
    //         'followers' => $data->followers ?? 0,
    //     ];
    // }

    /**
     * Determine the start date based on the filter.
     *
     * @param string $filter
     * @return Carbon
     */
    protected function getStartDateForFilter(string $filter): Carbon
    {
        $now = Carbon::now();
        return match ($filter) {
            'daily' => $now->subDay()->startOfDay(), // Last 24 hours
            'last_week' => $now->subWeek()->startOfDay(), // Last 7 days
            'last_month' => $now->subMonth()->startOfDay(),
            'last_90_days' => $now->subDays(90)->startOfDay(),
            'last_year' => $now->subYear()->startOfDay(),
            default => $now->subWeek()->startOfDay(),
        };
    }

    // public function getAnalyticsData(string $filter = 'last_week', $trackUrn = null, $actionType = null)
    // {
    //     $userUrn = user()->urn;
    //     $filterStartDate = $this->getStartDateForFilter($filter);
    //     $currentDate = Carbon::now()->startOfDay();

    //     $query = UserAnalytics::where('user_urn', $userUrn);

    //     if ($trackUrn) {
    //         $query->where('track_urn', $trackUrn);
    //     }

    //     if ($actionType) {
    //         $query->where('action_type', $actionType);
    //     }

    //     $analytics = $query->whereDate('date', '>=', $filterStartDate)
    //         ->whereDate('date', '<=', $currentDate)
    //         ->get();

    //     $analytics = $analytics->groupBy('date')->map(function ($group) {
    //         return [
    //             'date' => $group->first()->date,
    //             'total_plays' => $group->sum('total_plays'),
    //             'total_likes' => $group->sum('total_likes'),
    //             'total_comments' => $group->sum('total_comments'),
    //             'total_views' => $group->sum('total_views'),
    //             'total_requests' => $group->sum('total_requests'),
    //             'total_reposts' => $group->sum('total_reposts'),
    //             'total_followers' => $group->sum('total_followers'),
    //         ];
    //     });

    //     // sum of every total values separated by filter date. for range of filter dates
    //     $totalPlays = $analytics->sum('total_plays');
    //     $totalLikes = $analytics->sum('total_likes');
    //     $totalComments = $analytics->sum('total_comments');
    //     $totalViews = $analytics->sum('total_views');
    //     $totalRequests = $analytics->sum('total_requests');
    //     $totalReposts = $analytics->sum('total_reposts');
    //     $totalFollowers = $analytics->sum('total_followers');

    //     $avgTotalPlays = $analytics->avg('total_plays');
    //     $avgTotalLikes = $analytics->avg('total_likes');
    //     $avgTotalComments = $analytics->avg('total_comments');
    //     $avgTotalViews = $analytics->avg('total_views');
    //     $avgTotalRequests = $analytics->avg('total_requests');
    //     $avgTotalReposts = $analytics->avg('total_reposts');
    //     $avgTotalFollowers = $analytics->avg('total_followers');

    //     $totalPlaysRate = ($totalPlays / $totalViews) * 100;
    //     $totalLikesRate = ($totalLikes / $totalViews) * 100;
    //     $totalCommentsRate = ($totalComments / $totalViews) * 100;
    //     $totalRequestsRate = ($totalRequests / $totalViews) * 100;
    //     $totalRepostsRate = ($totalReposts / $totalViews) * 100;
    //     $totalFollowersRate = ($totalFollowers / $totalViews) * 100;

    //     $avgTotalPlaysRate = ($avgTotalPlays / $avgTotalViews) * 100;
    //     $avgTotalLikesRate = ($avgTotalLikes / $avgTotalViews) * 100;
    //     $avgTotalCommentsRate = ($avgTotalComments / $avgTotalViews) * 100;
    //     $avgTotalRequestsRate = ($avgTotalRequests / $avgTotalViews) * 100;
    //     $avgTotalRepostsRate = ($avgTotalReposts / $avgTotalViews) * 100;
    //     $avgTotalFollowersRate = ($avgTotalFollowers / $avgTotalViews) * 100;

    //     // compare all values to get profit or loss in % and total values of all based on filter date. if filter date is last week then compare to last week on todays . like if today is wednesday then compare between this week start date to wednesday and last week start date to wednesday. same condition for all last day, last month, last 90 days, last year


    //     $analyticsTotalPlays =

    //         $analytics = [
    //             'total_plays' => $totalPlays,
    //             'avg_total_plays' => $avgTotalPlays,
    //             'total_likes' => $totalLikes,
    //             'avg_total_likes' => $avgTotalLikes,
    //             'total_comments' => $totalComments,
    //             'avg_total_comments' => $avgTotalComments,
    //             'total_views' => $totalViews,
    //             'avg_total_views' => $avgTotalViews,
    //             'total_requests' => $totalRequests,
    //             'avg_total_requests' => $avgTotalRequests,
    //             'total_reposts' => $totalReposts,
    //             'avg_total_reposts' => $avgTotalReposts,
    //             'total_followers' => $totalFollowers,
    //             'avg_total_followers' => $avgTotalFollowers,
    //             'total_plays_rate' => $totalPlaysRate,
    //             'avg_total_plays_rate' => $avgTotalPlaysRate,
    //             'total_likes_rate' => $totalLikesRate,
    //             'avg_total_likes_rate' => $avgTotalLikesRate,
    //             'total_comments_rate' => $totalCommentsRate,
    //             'avg_total_comments_rate' => $avgTotalCommentsRate,
    //             'total_requests_rate' => $totalRequestsRate,
    //             'avg_total_requests_rate' => $avgTotalRequestsRate,
    //             'total_reposts_rate' => $totalRepostsRate,
    //             'avg_total_reposts_rate' => $avgTotalRepostsRate,
    //             'total_followers_rate' => $totalFollowersRate,
    //             'avg_total_followers_rate' => $avgTotalFollowersRate,
    //         ];

    //     dd($analytics);

    //     return $analytics;
    // }

    public function getAnalyticsData(string $filter = 'last_week', $trackUrn = null, $actionType = null)
    {
        $userUrn = user()->urn;

        // Determine the date range for the current period
        $currentPeriodStartDate = $this->getStartDateForFilter($filter);
        $currentPeriodEndDate = Carbon::now()->startOfDay();

        // Determine the date range for the previous, comparable period
        $interval = $currentPeriodEndDate->diff($currentPeriodStartDate);
        $previousPeriodEndDate = $currentPeriodStartDate->copy()->subDay();
        $previousPeriodStartDate = $previousPeriodEndDate->copy()->sub($interval);

        // --- Fetch data for the CURRENT period ---
        $query = UserAnalytics::where('user_urn', $userUrn);
        if ($trackUrn) {
            $query->where('track_urn', $trackUrn);
        }
        if ($actionType) {
            $query->where('action_type', $actionType);
        }
        $currentAnalytics = $query->whereDate('date', '>=', $currentPeriodStartDate)
            ->whereDate('date', '<=', $currentPeriodEndDate)
            ->get();

        // --- Fetch data for the PREVIOUS period ---
        $previousQuery = UserAnalytics::where('user_urn', $userUrn);
        if ($trackUrn) {
            $previousQuery->where('track_urn', $trackUrn);
        }
        if ($actionType) {
            $previousQuery->where('action_type', $actionType);
        }
        $previousAnalytics = $previousQuery->whereDate('date', '>=', $previousPeriodStartDate)
            ->whereDate('date', '<=', $previousPeriodEndDate)
            ->get();

        // --- Process and calculate metrics for both periods ---
        $processMetrics = function ($data) {
            // This is the collection from your original code
            $groupedData = $data->groupBy('date')->map(function ($group) {
                return [
                    'date' => $group->first()->date,
                    'total_plays' => $group->sum('total_plays'),
                    'total_likes' => $group->sum('total_likes'),
                    'total_comments' => $group->sum('total_comments'),
                    'total_views' => $group->sum('total_views'),
                    'total_requests' => $group->sum('total_requests'),
                    'total_reposts' => $group->sum('total_reposts'),
                    'total_followers' => $group->sum('total_followers'),
                ];
            });

            // The bug was here. We need to explicitly sum and average each metric.
            $metrics = [];
            $metricKeys = [
                'total_plays',
                'total_likes',
                'total_comments',
                'total_views',
                'total_requests',
                'total_reposts',
                'total_followers'
            ];

            foreach ($metricKeys as $key) {
                $metrics[$key] = $groupedData->sum($key) ?? 0;
                $metrics['avg_' . $key] = $groupedData->avg($key) ?? 0;
            }

            // Now calculate rates using the corrected totals
            $totalViews = $metrics['total_views'];
            $avgTotalViews = $metrics['avg_total_views'];

            $metrics['total_plays_rate'] = $totalViews > 0 ? ($metrics['total_plays'] / $totalViews) * 100 : 0;
            $metrics['avg_total_plays_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_plays'] / $avgTotalViews) * 100 : 0;

            $metrics['total_likes_rate'] = $totalViews > 0 ? ($metrics['total_likes'] / $totalViews) * 100 : 0;
            $metrics['avg_total_likes_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_likes'] / $avgTotalViews) * 100 : 0;

            $metrics['total_comments_rate'] = $totalViews > 0 ? ($metrics['total_comments'] / $totalViews) * 100 : 0;
            $metrics['avg_total_comments_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_comments'] / $avgTotalViews) * 100 : 0;

            $metrics['total_requests_rate'] = $totalViews > 0 ? ($metrics['total_requests'] / $totalViews) * 100 : 0;
            $metrics['avg_total_requests_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_requests'] / $avgTotalViews) * 100 : 0;

            $metrics['total_reposts_rate'] = $totalViews > 0 ? ($metrics['total_reposts'] / $totalViews) * 100 : 0;
            $metrics['avg_total_reposts_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_reposts'] / $avgTotalViews) * 100 : 0;

            $metrics['total_followers_rate'] = $totalViews > 0 ? ($metrics['total_followers'] / $totalViews) * 100 : 0;
            $metrics['avg_total_followers_rate'] = $avgTotalViews > 0 ? ($metrics['avg_total_followers'] / $avgTotalViews) * 100 : 0;

            return $metrics;
        };

        $currentMetrics = $processMetrics($currentAnalytics);
        $previousMetrics = $processMetrics($previousAnalytics);

        // --- Compare metrics and calculate profit/loss percentages ---
        $finalAnalytics = [];

        foreach ($currentMetrics as $key => $currentValue) {
            $previousValue = $previousMetrics[$key] ?? 0;
            $percentChange = 0;

            if ($previousValue != 0) {
                $percentChange = (($currentValue - $previousValue) / $previousValue) * 100;
            } elseif ($currentValue > 0) {
                $percentChange = 100.0; // Represents a huge increase from zero
            }

            $finalAnalytics[$key] = [
                'current_value' => $currentValue,
                'previous_value' => $previousValue,
                'percent_change' => number_format($percentChange, 2),
            ];
        }
        dd($finalAnalytics);

        return $finalAnalytics;
    }
}
