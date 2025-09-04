<?php

namespace App\Services\User;

use App\Models\UserAnalytics;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    public function updateAnalytics(object $source, string $column, int $increment = 1, string $genre): UserAnalytics|null
    {
        // Get the owner's URN from the source model.
        // The source model (Track, Repost, etc.) is expected to have a 'user' relationship that returns the user.
        $userUrn = $source->user?->urn;
        if (!$userUrn) {
            return null;
        }

        // Find or create the UserAnalytics record based on the unique combination.
        $analytics = UserAnalytics::firstOrNew([
            'user_urn' => $userUrn,
            'source_id' => $source->id,
            'source_type' => get_class($source),
            'date' => now()->toDateString(),
            'genre' => $genre,
        ]);

        // Increment the specified analytics column.
        $analytics->increment($column, $increment);

        return $analytics;
    }
}
