<?php

namespace Database\Seeders;

use App\Models\Campaign;
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

        for ($i = 0; $i < 50; $i++) {
            $track = $tracks->random();
            $this->createCampaign($user, $track, $genres);
        }
    }

    private function createCampaign(User $user, Track $track, array $genres): Campaign
    {
        return Campaign::create([
            'user_urn' => $user->urn,
            'music_id' => $track->id,
            'music_type' => Track::class,
            'title' => 'Boost Track Campaign' . $track->id,
            'commentable' => true,
            'likeable' => true,
            'max_followers' => 1000,
            'max_repost_last_24_h' => 50,
            'max_repost_per_day' => 10,
            'target_genre' => $genres[array_rand($genres)],
            'description' => 'A campaign to promote this track.' . $track->id,
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
