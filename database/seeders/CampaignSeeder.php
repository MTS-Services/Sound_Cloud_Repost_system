<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $genres = ['Pop', 'Rock', 'Hip-Hop', 'Classical', 'Electronic'];

        $tracks = Track::where('user_urn', $user->urn)->get();
        $playlists = Playlist::where('user_urn', $user->urn)->get();
        $sources = $tracks->concat($playlists);

        for ($i = 0; $i < 50; $i++) {
            $source = $sources->random();
            $this->createCampaign($user, $source, $genres);
        }
    }

    private function createCampaign(User $user, $source, array $genres): Campaign
    {
        return Campaign::create([
            'user_urn' => $user->urn,
            'music_id' => $source->id,
            'music_type' => get_class($source),
            'title' => 'Boost Track Campaign' . $source->id,
            'commentable' => true,
            'likeable' => true,
            'max_followers' => 1000,
            'max_repost_last_24_h' => 50,
            'max_repost_per_day' => 10,
            'target_genre' => $genres[array_rand($genres)],
            'description' => 'A campaign to promote this track.' . $source->id,
            'completed_reposts' => rand(0, 100),
            'budget_credits' => rand(100, 500),
            'credits_spent' => rand(0, 100),
            'min_followers' => rand(100, 500),
            'max_followers' => rand(1000, 5000),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'is_featured' => true,
        ]);
    }
}
