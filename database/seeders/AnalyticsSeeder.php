<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserAnalytics;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerUserUrn = 'urn:sc:users:1001';

        // The user URN for both owner and actor will be the same
        $actUserUrn = $ownerUserUrn;

        // Get all tracks and playlists to be used as sources
        $tracks = Track::all();
        $playlists = Playlist::all();
        $sources = $tracks->concat($playlists);

        // Seed analytics for campaigns (actionable_id and actionable_type are present)
        $campaigns = Campaign::where('user_urn', $ownerUserUrn)->with('music')->get();
        foreach ($campaigns as $campaign) {
            $this->createAnalyticsForActionable($ownerUserUrn, $campaign, $sources, $actUserUrn);
        }
        // Seed general analytics (actionable_id and actionable_type are null)
        $this->createGeneralAnalytics($ownerUserUrn, $sources, $actUserUrn);
    }

    /**
     * Create analytics records for a specific actionable model (e.g., Campaign, RepostRequest).
     */
    private function createAnalyticsForActionable(string $ownerUserUrn, $actionable, $sources, string $actUserUrn): void
    {
        if (!$actionable) {
            return;
        }

        $source = $actionable->music ?? $actionable->track ?? $actionable->playlist;

        if (!$source) {
            return;
        }

        for ($i = 0; $i <= 6; $i++) {
            UserAnalytics::create([
                'owner_user_urn' => $ownerUserUrn,
                'act_user_urn' => $actUserUrn, // Set to the same as owner user
                'source_id' => $source->id,
                'source_type' => get_class($source),
                'actionable_id' => $actionable->id,
                'actionable_type' => get_class($actionable),
                'genre' => $source->target_genre ?? null,
                'type' => $i,
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
    }

    /**
     * Create general analytics records where the actionable fields are null.
     */
    private function createGeneralAnalytics(string $ownerUserUrn, $sources, string $actUserUrn): void
    {
        for ($j = 0; $j < 20; $j++) {
            $source = $sources->random();
            $sourceType = get_class($source);
            
            $sourceType = array_search($sourceType, Relation::morphMap()) ?: $sourceType;

            UserAnalytics::create([
                'owner_user_urn' => $ownerUserUrn,
                'act_user_urn' => $actUserUrn, // Set to the same as owner user
                'source_id' => $source->id,
                'source_type' => $sourceType,
                'actionable_id' => null,
                'actionable_type' => null,
                'genre' => $source->target_genre ?? null,
                'type' => rand(0, 6),
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
    }
}