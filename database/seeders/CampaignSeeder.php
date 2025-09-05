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
        // $user = User::first();
        // if (!$user) {
        //     $user = User::factory()->create();
        // }

        // $track = Track::first();
        // if (!$track) {
        //     $this->call(TrackSeeder::class); // ✅ call the seeder instead of factory
        //     $track = Track::first(); // re-fetch after seeding
        // }

        // Campaign::create([
        //     'user_urn' => $user->urn,
        //     'music_id' => $track->id,
        //     'music_type' => 'App\Models\Track',
        //     'title' => 'Boost Track Campaign',
        //     'commentable' => true,
        //     'likeable' => true,
        //     'max_followers' => 1000,
        //     'max_repost_last_24_h' => 50,
        //     'max_repost_per_day' => 10,
        //     'target_genre' => 'Pop',
        //     'description' => 'A campaign to promote this track.',
        //     'description' => 'A campaign to promote this track.',
        //     'completed_reposts' => 100,
        //     'budget_credits' => 500.00,
        //     'credits_spent' => 50.00,
        //     'min_followers' => 100,
        //     'max_followers' => 5000,
        //     'start_date' => Carbon::now(),
        //     'end_date' => Carbon::now()->addDays(30),
        //     'is_featured' => true,
        // ]);
    }
}
