<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RepostRequest;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Track;

class RepostRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $campaigns = Campaign::all();
        $tracks = Track::all();

        if ($users->count() == 0 || $campaigns->count() == 0 || $tracks->count() == 0) {
            $this->command->info('Skipping RepostRequestSeeder. Not enough data in users, campaigns, or tracks tables.');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $requester = $users->random();
            $targetUser = $users->random();
            $campaign = $campaigns->random();
            $track = $tracks->random();

            RepostRequest::create([
                'sort_order' => $i,
                'requester_urn' => $requester->urn,
                'target_user_urn' => $targetUser->urn,
                'campaign_id' => $campaign->id,
                'track_urn' => $track->urn,
                'credits_spent' => rand(1, 100) / 10,
                'status' => array_rand(RepostRequest::getStatusList()),
                'requested_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
