<?php

namespace App\Livewire\User;

use App\Jobs\NotificationMailSent;
use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Repost;
use App\Models\Track;
use App\Models\UserAnalytics;
use App\Services\SoundCloud\SoundCloudService;
use App\Services\User\AnalyticsService;
use App\Services\User\CampaignManagement\CampaignService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Throwable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Chart extends Component
{

    public function render()
    {

        $startDate = Carbon::now()->subDays(7)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $topAnalytics = UserAnalytics::query()
            ->whereNotNull('actionable_id')
            ->where('actionable_type', Campaign::class)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select([
                'source_id',
                'source_type',
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
            ->groupBy('source_id', 'source_type')
            ->orderByDesc('engagement_rate')
            ->take(20)
            ->get();

        // Eager load relations (N+1 free)
        $topAnalytics->load(['actionable', 'source']);
        dd($topAnalytics);

        // Map analytics
        $analyticsData = $topAnalytics->map(function ($item) {
            return [
                'campaign' => $item->actionable,
                'source' => $item->source,
                'source_type' => $item->source_type,
                'views' => (int) $item->total_views,
                'streams' => (int) $item->total_streams,
                'likes' => (int) $item->total_likes,
                'comments' => (int) $item->total_comments,
                'reposts' => (int) $item->total_reposts,
                'followers' => (int) $item->total_followers,
                'engagement_rate' => round($item->engagement_rate, 2),
            ];
        });

        // Include period info
        $period = [
            'start' => $startDate,
            'end' => $endDate,
            'days' => $endDate->diffInDays($startDate) + 1,
        ];

        // ✅ Store or update cache every time
        Cache::put('user_analytics_top_20_sources_cache', [
            'period' => $period,
            'analytics' => $analyticsData,
        ], 604800); // cache for 7 days

        $results = Cache::get('user_analytics_top_20_sources_cache');
        // Now you can access both easily
        dd($results['period'], $results['analytics']);

        return view(
            'livewire.user.chart'
        );
    }
}
