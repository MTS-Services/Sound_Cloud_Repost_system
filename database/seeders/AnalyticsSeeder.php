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

        for ($i = 0; $i < 5; $i++) {
            
            UserAnalytics::create([
                'user_urn' => $userUrn,
                'track_urn' => Track::first()->urn,
                'campaign_id' => null,
                'date' => now()->subDays(rand(1, 30)),
                'genre' => 'Pop',
                'total_views' => rand(1, 100),
                'total_comments' => rand(1, 100),
                'total_reposts' => rand(1, 100),
                'total_likes' => rand(1, 100),
                'total_followers' => rand(1, 100),
                'total_plays' => rand(1, 100),
            ]);
        }
    }
}
