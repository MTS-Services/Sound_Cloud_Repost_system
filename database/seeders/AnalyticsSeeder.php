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
        $campaignIds = Campaign::all()->pluck('id')->toArray();
        // $playlistIds = Playlist::all()->pluck('id')->toArray();
        // $trackIds = Track::all()->pluck('id')->toArray();
        // $requests = RepostRequest::all()->pluck('id')->toArray();

        // Group the sources together with their corresponding class names.
        $sources = [
            'campaigns' => [
                'type' => Campaign::class,
                'ids' => $campaignIds,
            ],
            // 'playlists' => [
            //     'type' => Playlist::class,
            //     'ids' => $playlistIds,
            // ],
            // 'tracks' => [
            //     'type' => Track::class,
            //     'ids' => $trackIds,
            // ],
            // 'requests' => [
            //     'type' => RepostRequest::class,
            //     'ids' => $requests,
            // ],
        ];

        for ($i = 0; $i < 3; $i++) {
            $randomSourceKey = array_rand($sources);

            $randomSource = $sources[$randomSourceKey];

            $sourceId = $randomSource['ids'][array_rand($randomSource['ids'])];

            $sourceType = $randomSource['type'];

            UserAnalytics::create([
                'user_urn' => $userUrn,
                'source_id' => $sourceId,
                'source_type' => $sourceType,
                'date' => now()->subDays(rand(1, 30)),
                'genre' => 'Pop',

                'total_views' => rand(1, 100),
                'total_comments' => rand(1, 100),
                'total_reposts' => rand(1, 100),
                'total_likes' => rand(1, 100),
                'total_followes' => rand(1, 100),
                'total_plays' => rand(1, 100),
            ]);
        }
    }
}
