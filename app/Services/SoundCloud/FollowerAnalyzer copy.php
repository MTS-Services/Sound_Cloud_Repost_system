<?php

namespace App\Services\SoundCloud;

use Carbon\Carbon;


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
     * Separates followers into fake and real categories
     *
     * @param $followers
     * @return array
     */
    public function separateFollowers($followers): array
    {
        // $analysis = collect($followers)->map(fn($follower) => $this->analyzeUser($follower));
        $analysis = $followers->map(fn($follower) => $this->analyzeUser($follower));

        $realFollowers = $analysis->filter(fn($a) => $a['classification'] === 'real');
        $fakeFollowers = $analysis->filter(fn($a) => $a['classification'] === 'fake');

        $totalCount = count($followers);
        $realCount = $realFollowers->count();
        $fakeCount = $fakeFollowers->count();

        return [
            'real' => $realFollowers->values()->toArray(),
            'fake' => $fakeFollowers->values()->toArray(),
            'counts' => [
                'total' => $totalCount,
                'real' => $realCount,
                'fake' => $fakeCount,
                'realPercentage' => $totalCount > 0 ? round(($realCount / $totalCount) * 100) : 0,
                'fakePercentage' => $totalCount > 0 ? round(($fakeCount / $totalCount) * 100) : 0
            ],
            'summary' => $this->generateSummary($realFollowers, $fakeFollowers)
        ];
    }

    /**
     * Get detailed analysis for a specific user
     *
     * @param  $user
     * @return array
     */
    public function getDetailedAnalysis($user): array
    {
        $analysis = $this->analyzeUser($user);

        $details = [
            'username' => $user['username'] ?? 'Unknown',
            'overallScore' => $analysis['credibilityScore'],
            'classification' => strtoupper($analysis['classification']),
            'breakdown' => [
                'customAvatar' => $analysis['factors']['hasAvatar'] ? '✅ Yes' : '❌ No',
                'hasDescription' => $analysis['factors']['hasDescription'] ? '✅ Yes' : '❌ No',
                'hasWebsite' => $analysis['factors']['hasWebsite'] ? '✅ Yes' : '❌ No',
                'trackCount' => $analysis['factors']['trackCount'],
                'followersFollowingRatio' => $analysis['factors']['followersToFollowing']['ratio'],
                'accountAgeMonths' => $analysis['factors']['accountAge']['months'],
                'totalActivity' => $analysis['factors']['activity']['total']
            ],
            'riskFactors' => $this->identifyRiskFactors($analysis)
        ];

        return $details;
    }

    /**
     * Calculate followers to following ratio score
     *
     * @param int $followersCount
     * @param int $followingCount
     * @return int
     */
    private function calculateRatioScore(int $followersCount, int $followingCount): int
    {
        if ($followingCount === 0) {
            return $followersCount > 0 ? $this->weights['followersToFollowing'] : 0;
        }

        $ratio = $followersCount / $followingCount;

        // Very suspicious if following way more than followers
        if ($ratio < 0.1 && $followingCount > 100) {
            return 0;
        }

        if ($ratio < 0.5) {
            return 5; // Somewhat suspicious
        }

        if ($ratio >= 0.5 && $ratio <= 5) {
            return $this->weights['followersToFollowing']; // Normal range
        }

        return 10; // High ratio, could be influencer
    }

    /**
     * Calculate account age score
     *
     * @param string|null $createdAt
     * @return array
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
     *
     * @param  $user
     * @return array
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
     *
     * @param $realFollowers
     * @param $fakeFollowers
     * @return array
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
     *
     * @param array $analysis
     * @return array
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
     * Get quick stats for dashboard
     *
     * @param $followers
     * @return array
     */
    public function getQuickStats($followers): array
    {
        $result = $this->separateFollowers($followers);

        return [
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
     * Set custom threshold for fake detection
     *
     * @param int $threshold
     * @return self
     */
    public function setFakeThreshold(int $threshold): self
    {
        $this->fakeThreshold = $threshold;
        return $this;
    }

    /**
     * Set custom weights for analysis factors
     *
     * @param array $weights
     * @return self
     */
    public function setWeights(array $weights): self
    {
        $this->weights = array_merge($this->weights, $weights);
        return $this;
    }

}
