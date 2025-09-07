<?php

namespace App\Services\User;

use App\Models\Campaign;
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
    public function syncUserAction(object $source, string $column, $campaignId = null, $userUrn = null): bool
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
            '%s.%s.%s.%s',
            $column,
            $userUrn,
            get_class($source),
            $campaignId ? $campaignId : '',
            $source->urn ? $source->urn : ''
        );

        // Log::info("session key {$todayKey}  value {$updatedToday} actionIdentifier  {$actionIdentifier} updatedToday  {$updatedToday}");
        // Check if this action has already been logged for today.
        if (in_array($actionIdentifier, $updatedToday)) {
            Log::info("User action update skipped for {$userUrn} on {$column} for source {$source->urn}. Already updated today.");
            return false;
        }


        // If not in the session, add the action and save.
        $updatedToday[] = $actionIdentifier;
        session()->put($todayKey, $updatedToday);

        return true;
    }

    public function updateAnalytics(object $track, string $column, string $genre, $campaignId = null, int $increment = 1): UserAnalytics|bool|null
    {
        // Get the owner's URN from the track model.
        $userUrn = $track->user?->urn;
        if (!$userUrn) {
            Log::info("User action update skipped for {$userUrn} on {$column} for track {$track->id} and track type {$track->getMorphClass()}. No user URN found.");
            return null;
        }

        // Use the new reusable method to check if the update is allowed.
        if (!$this->syncUserAction($track, $column)) {
            return false;
        }

        // Find or create the UserAnalytics record based on the unique combination.
        $analytics = UserAnalytics::firstOrNew(
            [
                'user_urn' => $userUrn,
                'track_urn' => $track->urn,
                'campaign_id' => $campaignId,
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
    public function getAnalyticsData(string $userUrn, string $filter = 'last_week'): array
    {
        $startDate = $this->getStartDateForFilter($filter);
        $endDate = Carbon::now();

        $data = DB::table('user_analytics')
            ->select(
                DB::raw('SUM(total_plays) as streams'),
                DB::raw('SUM(total_likes) as likes'),
                DB::raw('SUM(total_comments) as comments'),
                DB::raw('SUM(total_views) as views'),
                DB::raw('SUM(total_reposts) as reposts'),
                DB::raw('SUM(total_followers) as followers')
            )
            ->where('user_urn', $userUrn)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->first();

        // Check if data is null, and return default values if so
        if (!$data) {
            return [
                'streams' => 0,
                'likes' => 0,
                'comments' => 0,
                'views' => 0,
                'reposts' => 0,
                'followers' => 0,
                'engagementRate' => 0,
            ];
        }

        // Calculate engagement rate
        $engagementRate = 0;
        if ($data->streams > 0) {
            $engagementRate = (($data->likes + $data->comments + $data->reposts) / $data->streams) * 100;
        }

        return [
            'streams' => $data->streams ?? 0,
            'likes' => $data->likes ?? 0,
            'comments' => $data->comments ?? 0,
            'views' => $data->views ?? 0,
            'reposts' => $data->reposts ?? 0,
            'followers' => $data->followers ?? 0,
            'engagementRate' => number_format($engagementRate, 1),
        ];
    }

    // public function getEngeagementRate(UserAnalytics $analytics)
    // {
    //     if ($analytics->source_type === Track::class) {
    //         $likeEngRate = ($analytics->total_likes / $analytics->total_views) * 100;
    //         $commentEngRate = ($analytics->total_comments / $analytics->total_views) * 100;
    //         $repostEngRate = ($analytics->total_reposts / $analytics->total_views) * 100;
    //         $playEngRate = ($analytics->total_plays / $analytics->total_views) * 100;
    //         $followEngRate = ($analytics->total_followers / $analytics->total_views) * 100;
    //         $totalEngRate = ($analytics->total_likes + $analytics->total_comments + $analytics->total_reposts + $analytics->total_plays + $analytics->total_followers) / $analytics->total_views * 100;
    //         return [
    //             'engagementType' => Track::class,
    //             'likeEngRate' => $likeEngRate,
    //             'commentEngRate' => $commentEngRate,
    //             'repostEngRate' => $repostEngRate,
    //             'playEngRate' => $playEngRate,
    //             'followEngRate' => $followEngRate,
    //             'totalEngRate' => $totalEngRate,
    //         ];
    //     } else if ($analytics->source_type === Campaign::class) {
    //         $likeEngRate = ($analytics->total_likes / $analytics->total_views) * 100;
    //         $commentEngRate = ($analytics->total_comments / $analytics->total_views) * 100;
    //         $repostEngRate = ($analytics->total_reposts / $analytics->total_views) * 100;
    //         $playEngRate = ($analytics->total_plays / $analytics->total_views) * 100;
    //         $followEngRate = ($analytics->total_followers / $analytics->total_views) * 100;
    //         $totalEngRate = ($analytics->total_likes + $analytics->total_comments + $analytics->total_reposts + $analytics->total_plays + $analytics->total_followers) / $analytics->total_views * 100;
    //         return [
    //             'engagementType' => Campaign::class,
    //             'likeEngRate' => $likeEngRate,
    //             'commentEngRate' => $commentEngRate,
    //             'repostEngRate' => $repostEngRate,
    //             'playEngRate' => $playEngRate,
    //             'followEngRate' => $followEngRate,
    //             'totalEngRate' => $totalEngRate,
    //         ];
    //     } else {
    //         return [
    //             'engagementType' => null,
    //             'likeEngRate' => null,
    //             'commentEngRate' => null,
    //             'repostEngRate' => null,
    //             'playEngRate' => null,
    //             'followEngRate' => null,
    //             'totalEngRate' => null,
    //         ];
    //     }
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
}
