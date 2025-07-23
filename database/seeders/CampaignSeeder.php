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

        if (!$user) {
            $user = User::factory()->create();
        }
        
        Campaign::create([
            'user_urn' => $user->urn,
            'music_id' => rand(1, 10),
            'music_type' => 'track',
            'title' => 'Boost Track Campaign',
            'description' => 'A campaign to promote this track.',
            'target_reposts' => 1000,
            'completed_reposts' => 100,
            'credits_per_repost' => 0.50,
            'total_credits_budget' => 500.00,
            'credits_spent' => 50.00,
            'min_followers_required' => 100,
            'max_followers_limit' => 5000,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'auto_approve' => true,
        ]);
    }
}
