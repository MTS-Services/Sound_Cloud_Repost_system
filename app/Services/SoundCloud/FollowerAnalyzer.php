<?php

namespace App\Services\SoundCloud;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FollowerAnalyzer
{
    private array $weights = [
        'hasAvatar' => 15,
        'hasDescription' => 10,
        'hasWebsite' => 8,
        'trackCount' => 20,
        'followersToFollowing' => 15,
        'accountAge' => 12,
        'activity' => 20,
    ];

    /**
     * Score below this threshold is considered fake
     */
    private int $fakeThreshold = 40;

    /**
     * Available date filter periods
     */
    private array $availablePeriods = [
        'today',
        'yesterday',
        'this_week',
        'last_week',
        'this_month',
        'last_month',
        'last_7_days',
        'last_30_days',
        'last_90_days',
        'this_year',
        'last_year',
        'all_time'
    ];

    /**
     * Analyzes a user and returns credibility data
     *
     * @param  $user
     * @return array
     */
    public function analyzeUser($user): array
    {
        $score = 0;
        $factors = [];

        // 1. Avatar check (default avatar indicates potential fake)
        $hasCustomAvatar = !str_contains($user['avatar_url'] ?? '', 'default_avatar');
        $factors['hasAvatar'] = $hasCustomAvatar;
        if ($hasCustomAvatar) {
            $score += $this->weights['hasAvatar'];
        }

        // 2. Profile completeness
        $hasDescription = !empty(trim($user['description'] ?? ''));
        $factors['hasDescription'] = $hasDescription;
        if ($hasDescription) {
            $score += $this->weights['hasDescription'];
        }

        $hasWebsite = !empty(trim($user['website'] ?? ''));
        $factors['hasWebsite'] = $hasWebsite;
        if ($hasWebsite) {
            $score += $this->weights['hasWebsite'];
        }

        // 3. Content creation (tracks uploaded)
        $trackCount = $user['track_count'] ?? 0;
        $factors['trackCount'] = $trackCount;
        if ($trackCount > 0) {
            $score += min($this->weights['trackCount'], $trackCount * 3);
        }

        // 4. Followers to following ratio analysis
        $followersCount = $user['followers_count'] ?? 0;
        $followingCount = $user['followings_count'] ?? 0;

        $ratioScore = $this->calculateRatioScore($followersCount, $followingCount);
        $factors['followersToFollowing'] = [
            'ratio' => $followingCount > 0 ? round($followersCount / $followingCount, 2) : 'N/A',
            'score' => $ratioScore
        ];
        $score += $ratioScore;

        // 5. Account age (newer accounts more suspicious)
        $accountAgeData = $this->calculateAccountAge($user['created_at'] ?? null);
        $factors['accountAge'] = $accountAgeData;
        $score += $accountAgeData['score'];

        // 6. Activity level (likes, reposts, favorites)
        $activityData = $this->calculateActivityScore($user);
        $factors['activity'] = $activityData;
        $score += $activityData['score'];

        return [
            'user' => $user,
            'credibilityScore' => round($score),
            'factors' => $factors,
            'classification' => $score >= $this->fakeThreshold ? 'real' : 'fake'
        ];
    }

    /**
     * Separates followers into fake and real categories with date filtering
     *
     * @param $followers
     * @param string|null $period
     * @return array
     */
    public function separateFollowers($followers, ?string $period = null): array
    {
        // Filter followers by date if period is specified
        if ($period && $period !== 'all_time') {
            $followers = $this->filterFollowersByPeriod($followers, $period);
        }

        $analysis = $followers->map(fn($follower) => $this->analyzeUser($follower));

        $realFollowers = $analysis->filter(fn($a) => $a['classification'] === 'real');
        $fakeFollowers = $analysis->filter(fn($a) => $a['classification'] === 'fake');

        $totalCount = count($followers);
        $realCount = $realFollowers->count();
        $fakeCount = $fakeFollowers->count();

        return [
            'period' => $period ?? 'all_time',
            'periodLabel' => $this->getPeriodLabel($period),
            'real' => $realFollowers->values()->toArray(),
            'fake' => $fakeFollowers->values()->toArray(),
            'counts' => [
                'total' => $totalCount,
                'real' => $realCount,
                'fake' => $fakeCount,
                'realPercentage' => $totalCount > 0 ? round(($realCount / $totalCount) * 100) : 0,
                'fakePercentage' => $totalCount > 0 ? round(($fakeCount / $totalCount) * 100) : 0
            ],
            'summary' => $this->generateSummary($realFollowers, $fakeFollowers),
            'dateRange' => $this->getDateRange($period)
        ];
    }

    /**
     * Get detailed analysis for a specific user with date context
     *
     * @param  $user
     * @param string|null $period
     * @return array
     */
    public function getDetailedAnalysis($user, ?string $period = null): array
    {
        $analysis = $this->analyzeUser($user);

        // Check if user falls within the specified period
        $withinPeriod = $period ? $this->isUserInPeriod($user, $period) : true;

        $details = [
            'username' => $user['username'] ?? 'Unknown',
            'overallScore' => $analysis['credibilityScore'],
            'classification' => strtoupper($analysis['classification']),
            'withinPeriod' => $withinPeriod,
            'period' => $period ?? 'all_time',
            'periodLabel' => $this->getPeriodLabel($period),
            'breakdown' => [
                'customAvatar' => $analysis['factors']['hasAvatar'] ? '✅ Yes' : '❌ No',
                'hasDescription' => $analysis['factors']['hasDescription'] ? '✅ Yes' : '❌ No',
                'hasWebsite' => $analysis['factors']['hasWebsite'] ? '✅ Yes' : '❌ No',
                'trackCount' => $analysis['factors']['trackCount'],
                'followersFollowingRatio' => $analysis['factors']['followersToFollowing']['ratio'],
                'accountAgeMonths' => $analysis['factors']['accountAge']['months'],
                'totalActivity' => $analysis['factors']['activity']['total']
            ],
            'riskFactors' => $this->identifyRiskFactors($analysis),
            'dateInfo' => [
                'createdAt' => $user['created_at'] ?? null,
                'followedAt' => $user['followed_at'] ?? null, // If available
                'accountAge' => $analysis['factors']['accountAge']['months'] . ' months'
            ]
        ];

        return $details;
    }

    /**
     * Get quick stats for dashboard with date filtering
     *
     * @param $followers
     * @param string|null $period
     * @return array
     */
    public function getQuickStats($followers, ?string $period = null): array
    {
        $result = $this->separateFollowers($followers, $period);

        return [
            'period' => $period ?? 'all_time',
            'periodLabel' => $this->getPeriodLabel($period),
            'dateRange' => $this->getDateRange($period),
            'totalFollowers' => $result['counts']['total'],
            'realFollowers' => $result['counts']['real'],
            'fakeFollowers' => $result['counts']['fake'],
            'realPercentage' => $result['counts']['realPercentage'],
            'fakePercentage' => $result['counts']['fakePercentage'],
            'qualityScore' => $result['counts']['realPercentage'], // Overall follower quality
            'averageCredibilityScore' => $result['summary']['averageRealScore']
        ];
    }

    /**
     * Get comparative analysis across multiple periods
     *
     * @param $followers
     * @param array $periods
     * @return array
     */
    public function getComparativeAnalysis($followers, array $periods = ['last_week', 'last_month', 'last_90_days']): array
    {
        $comparison = [];

        foreach ($periods as $period) {
            $stats = $this->getQuickStats($followers, $period);
            $comparison[$period] = $stats;
        }

        return [
            'comparison' => $comparison,
            'trends' => $this->calculateTrends($comparison),
            'insights' => $this->generateInsights($comparison)
        ];
    }

    /**
     * Get follower growth analytics by period
     *
     * @param $followers
     * @return array
     */
    public function getGrowthAnalytics($followers): array
    {
        $periods = ['today', 'yesterday', 'last_7_days', 'last_30_days'];
        $growth = [];

        foreach ($periods as $period) {
            $stats = $this->getQuickStats($followers, $period);
            $growth[$period] = [
                'total' => $stats['totalFollowers'],
                'real' => $stats['realFollowers'],
                'fake' => $stats['fakeFollowers'],
                'quality' => $stats['qualityScore'],
                'period_label' => $stats['periodLabel']
            ];
        }

        return [
            'growth_data' => $growth,
            'growth_rate' => $this->calculateGrowthRate($growth),
            'quality_trend' => $this->calculateQualityTrend($growth)
        ];
    }

    /**
     * Filter followers by time period
     *
     * @param $followers
     * @param string $period
     * @return mixed
     */
    private function filterFollowersByPeriod($followers, string $period)
    {
        $dateRange = $this->getDateRange($period);

        return $followers->filter(function ($follower) use ($dateRange) {
            // Use followed_at if available, otherwise created_at
            $date = $follower['followed_at'] ?? $follower['created_at'] ?? null;

            if (!$date) {
                return false;
            }

            $followerDate = Carbon::parse($date);

            return $followerDate->between(
                Carbon::parse($dateRange['start']),
                Carbon::parse($dateRange['end'])
            );
        });
    }

    /**
     * Check if user falls within specified period
     *
     * @param $user
     * @param string $period
     * @return bool
     */
    private function isUserInPeriod($user, string $period): bool
    {
        $dateRange = $this->getDateRange($period);
        $date = $user['followed_at'] ?? $user['created_at'] ?? null;

        if (!$date) {
            return false;
        }

        $userDate = Carbon::parse($date);

        return $userDate->between(
            Carbon::parse($dateRange['start']),
            Carbon::parse($dateRange['end'])
        );
    }

    /**
     * Get date range for period
     *
     * @param string|null $period
     * @return array
     */
    private function getDateRange(?string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay()
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->startOfDay(),
                'end' => $now->copy()->subDay()->endOfDay()
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ],
            'last_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ],
            'last_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth()
            ],
            'last_7_days' => [
                'start' => $now->copy()->subDays(7),
                'end' => $now
            ],
            'last_30_days' => [
                'start' => $now->copy()->subDays(30),
                'end' => $now
            ],
            'last_90_days' => [
                'start' => $now->copy()->subDays(90),
                'end' => $now
            ],
            'this_year' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear()
            ],
            'last_year' => [
                'start' => $now->copy()->subYear()->startOfYear(),
                'end' => $now->copy()->subYear()->endOfYear()
            ],
            default => [
                'start' => Carbon::create(2000, 1, 1),
                'end' => $now
            ]
        };
    }

    /**
     * Get human readable period label
     *
     * @param string|null $period
     * @return string
     */
    private function getPeriodLabel(?string $period): string
    {
        return match ($period) {
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'last_week' => 'Last Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'last_7_days' => 'Last 7 Days',
            'last_30_days' => 'Last 30 Days',
            'last_90_days' => 'Last 90 Days',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
            default => 'All Time'
        };
    }

    /**
     * Calculate trends from comparison data
     *
     * @param array $comparison
     * @return array
     */
    private function calculateTrends(array $comparison): array
    {
        $trends = [];
        $periods = array_keys($comparison);

        if (count($periods) < 2) {
            return $trends;
        }

        for ($i = 1; $i < count($periods); $i++) {
            $current = $comparison[$periods[$i]];
            $previous = $comparison[$periods[$i - 1]];

            $trends[$periods[$i]] = [
                'follower_change' => $current['totalFollowers'] - $previous['totalFollowers'],
                'quality_change' => $current['qualityScore'] - $previous['qualityScore'],
                'real_change' => $current['realFollowers'] - $previous['realFollowers'],
                'fake_change' => $current['fakeFollowers'] - $previous['fakeFollowers']
            ];
        }

        return $trends;
    }

    /**
     * Generate insights from comparison data
     *
     * @param array $comparison
     * @return array
     */
    private function generateInsights(array $comparison): array
    {
        $insights = [];

        // Find period with highest quality
        $highestQuality = collect($comparison)->sortByDesc('qualityScore')->first();
        $lowestQuality = collect($comparison)->sortBy('qualityScore')->first();

        $insights[] = "Highest follower quality: {$highestQuality['qualityScore']}% in {$highestQuality['periodLabel']}";
        $insights[] = "Lowest follower quality: {$lowestQuality['qualityScore']}% in {$lowestQuality['periodLabel']}";

        // Check for concerning patterns
        $avgFakePercentage = collect($comparison)->avg('fakePercentage');
        if ($avgFakePercentage > 50) {
            $insights[] = "⚠️ High fake follower percentage detected across periods";
        }

        return $insights;
    }

    /**
     * Calculate growth rate between periods
     *
     * @param array $growth
     * @return array
     */
    private function calculateGrowthRate(array $growth): array
    {
        $rates = [];

        if (isset($growth['yesterday']) && isset($growth['today'])) {
            $todayTotal = $growth['today']['total'];
            $yesterdayTotal = $growth['yesterday']['total'];
            $rates['daily'] = $yesterdayTotal > 0 ?
                round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100, 2) : 0;
        }

        if (isset($growth['last_7_days']) && isset($growth['last_30_days'])) {
            $weeklyAvg = $growth['last_7_days']['total'] / 7;
            $monthlyAvg = $growth['last_30_days']['total'] / 30;
            $rates['weekly_vs_monthly'] = $monthlyAvg > 0 ?
                round((($weeklyAvg - $monthlyAvg) / $monthlyAvg) * 100, 2) : 0;
        }

        return $rates;
    }

    /**
     * Calculate quality trend
     *
     * @param array $growth
     * @return string
     */
    private function calculateQualityTrend(array $growth): string
    {
        $recent = $growth['last_7_days']['quality'] ?? 0;
        $older = $growth['last_30_days']['quality'] ?? 0;

        if ($recent > $older + 5) {
            return 'improving';
        } elseif ($recent < $older - 5) {
            return 'declining';
        }

        return 'stable';
    }

    /**
     * Get available periods for filtering
     *
     * @return array
     */
    public function getAvailablePeriods(): array
    {
        return collect($this->availablePeriods)->mapWithKeys(function ($period) {
            return [$period => $this->getPeriodLabel($period)];
        })->toArray();
    }

    // ... Rest of the existing methods remain unchanged ...

    /**
     * Calculate followers to following ratio score
     */
    private function calculateRatioScore(int $followersCount, int $followingCount): int
    {
        if ($followingCount === 0) {
            return $followersCount > 0 ? $this->weights['followersToFollowing'] : 0;
        }

        $ratio = $followersCount / $followingCount;

        if ($ratio < 0.1 && $followingCount > 100) {
            return 0;
        }

        if ($ratio < 0.5) {
            return 5;
        }

        if ($ratio >= 0.5 && $ratio <= 5) {
            return $this->weights['followersToFollowing'];
        }

        return 10;
    }

    /**
     * Calculate account age score
     */
    private function calculateAccountAge(?string $createdAt): array
    {
        if (!$createdAt) {
            return ['months' => 0, 'score' => 0];
        }

        $createdDate = Carbon::parse($createdAt);
        $accountAgeMonths = $createdDate->diffInMonths(now());

        $ageScore = 0;
        if ($accountAgeMonths > 12) {
            $ageScore = $this->weights['accountAge'];
        } elseif ($accountAgeMonths > 6) {
            $ageScore = $this->weights['accountAge'] * 0.7;
        } elseif ($accountAgeMonths > 3) {
            $ageScore = $this->weights['accountAge'] * 0.4;
        }

        return [
            'months' => round($accountAgeMonths),
            'score' => round($ageScore)
        ];
    }

    /**
     * Calculate activity score
     */
    private function calculateActivityScore($user): array
    {
        $totalActivity = ($user['public_favorites_count'] ?? 0) +
            ($user['reposts_count'] ?? 0) +
            ($user['likes_count'] ?? 0);

        $activityScore = 0;
        if ($totalActivity > 100) {
            $activityScore = $this->weights['activity'];
        } elseif ($totalActivity > 50) {
            $activityScore = $this->weights['activity'] * 0.8;
        } elseif ($totalActivity > 10) {
            $activityScore = $this->weights['activity'] * 0.5;
        } elseif ($totalActivity > 0) {
            $activityScore = $this->weights['activity'] * 0.3;
        }

        return [
            'total' => $totalActivity,
            'score' => round($activityScore)
        ];
    }

    /**
     * Generate summary of analysis
     */
    private function generateSummary($realFollowers, $fakeFollowers): array
    {
        $avgRealScore = $realFollowers->count() > 0
            ? round($realFollowers->avg('credibilityScore'))
            : 0;

        $avgFakeScore = $fakeFollowers->count() > 0
            ? round($fakeFollowers->avg('credibilityScore'))
            : 0;

        return [
            'averageRealScore' => $avgRealScore,
            'averageFakeScore' => $avgFakeScore,
            'highestScoringReal' => $realFollowers->count() > 0
                ? $realFollowers->sortByDesc('credibilityScore')->first()
                : null,
            'lowestScoringFake' => $fakeFollowers->count() > 0
                ? $fakeFollowers->sortBy('credibilityScore')->first()
                : null
        ];
    }

    /**
     * Identify specific risk factors for a user
     */
    private function identifyRiskFactors(array $analysis): array
    {
        $riskFactors = [];
        $factors = $analysis['factors'];

        if (!$factors['hasAvatar']) {
            $riskFactors[] = 'Using default avatar';
        }

        if (!$factors['hasDescription']) {
            $riskFactors[] = 'No profile description';
        }

        if ($factors['trackCount'] === 0) {
            $riskFactors[] = 'No tracks uploaded';
        }

        if ($factors['accountAge']['months'] < 3) {
            $riskFactors[] = 'Very new account (less than 3 months)';
        }

        if ($factors['activity']['total'] < 10) {
            $riskFactors[] = 'Very low platform activity';
        }

        $ratio = $factors['followersToFollowing']['ratio'];
        if (is_numeric($ratio) && $ratio < 0.1) {
            $riskFactors[] = 'Following significantly more users than followers';
        }

        return $riskFactors ?: ['No major risk factors identified'];
    }

    /**
     * Set custom threshold for fake detection
     */
    public function setFakeThreshold(int $threshold): self
    {
        $this->fakeThreshold = $threshold;
        return $this;
    }

    /**
     * Set custom weights for analysis factors
     */
    public function setWeights(array $weights): self
    {
        $this->weights = array_merge($this->weights, $weights);
        return $this;
    }


    public function syncUserRealFollowers($followers, $user)
    {
        // Fetch followers from SoundCloud API


        if (empty($followers)) {
            $user->real_followers = 0;
            $user->real_followers_percentage = 100;
            $user->save();
            return;
        }

        // Analyze and separate followers
        $followers = $this->separateFollowers($followers);

        // Update user with real follower count
        $user->real_followers = $followers['counts']['real'];
        $user->real_followers_percentage = $followers['counts']['realPercentage'];
        $user->save();

        // Optionally, store detailed analysis in a related table or cache
        // For simplicity, we'll just log the summary here
        Log::info('Follower analysis summary for user ' . $user->id, $followers['counts']);
    }
}
