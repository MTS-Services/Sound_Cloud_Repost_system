<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\UserAnalytics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userUrn = 'urn:sc:users:1001';

        $campaigns = Campaign::where('user_urn', $userUrn)->with('music')->get();


        foreach ($campaigns as $campaign) {
            $this->createAnalyticsForCampaign($userUrn, $campaign);
        }
    }

    private function createAnalyticsForCampaign(string $userUrn, Campaign $campaign): void
    {
        if (!$campaign) {
            return;
        }
        if (!$campaign->music) {
            return;
        }

        UserAnalytics::create([
            'user_urn' => $userUrn,
            'track_urn' => $campaign->music->urn,
            'action_id' => $campaign->id,
            'action_type' => Campaign::class,
            'genre' => $campaign->target_genre,
            'date' => now()->subDays(rand(1, 30)),
            'total_requests' => rand(1, 1000),
            'total_views' => rand(1, 1000),
            'total_comments' => rand(1, 1000),
            'total_reposts' => rand(1, 1000),
            'total_likes' => rand(1, 1000),
            'total_followers' => rand(1, 1000),
            'total_plays' => rand(1, 1000),
        ]);
    }
}
