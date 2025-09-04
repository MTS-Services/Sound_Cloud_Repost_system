<?php

namespace App\Services\User;

use App\Models\UserAnalytics;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    /**
     * Checks if an action update is allowed for a given action, user, and source today.
     * If allowed, it sets a session flag to prevent subsequent updates for the day.
     */
    public function syncUserAction(object $source, string $column, $userUrn = null): bool
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
            $source->id
        );

        // Check if this action has already been logged for today.
        if (in_array($actionIdentifier, $updatedToday)) {
            Log::info("User action update skipped for {$userUrn} on {$column} for source {$source->id}. Already updated today.");
            return false;
        }

        // If not in the session, add the action and save.
        $updatedToday[] = $actionIdentifier;
        session()->put($todayKey, $updatedToday);

        return true;
    }

    /**
     * Updates an analytics column for a given polymorphic source.
     *
     * @param object $source The source model instance (e.g., Track, Repost, Campaign).
     * @param string $column The analytics column to update (e.g., 'total_plays', 'total_likes').
     * @param string $genre The genre of the source.
     * @param int $increment The value to increment the column by.
     * @return UserAnalytics|null
     */
    public function updateAnalytics(object $source, string $column, string $genre, int $increment = 1): UserAnalytics|bool|null
    {
        // Get the owner's URN from the source model.
        $userUrn = $source->user?->urn;
        if (!$userUrn) {
            Log::info("User action update skipped for {$userUrn} on {$column} for source {$source->id} and source type {$source->getMorphClass()}. No user URN found.");
            return null;
        }

        // Use the new reusable method to check if the update is allowed.
        if (!$this->syncUserAction($source, $column)) {
            return false;
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
