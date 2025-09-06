<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repost;
use App\Models\User;
use App\Models\Campaign;
use App\Models\RepostRequest;

class RepostSeeder extends Seeder
{
    public function run(): void
    {
        // $users = User::all();
        // $campaigns = Campaign::all();
        // $repostRequests = RepostRequest::all();

        // if ($users->isEmpty()) {
        //     $this->command->info('Skipping RepostSeeder. No users found.');
        //     return;
        // }

        // for ($i = 0; $i < 50; $i++) {
        //     $reposter = $users->random();
        //     $trackOwner = $users->random();

        //     $repostData = [
        //         'sort_order' => $i,
        //         'reposter_urn' => $reposter->urn,
        //         'track_owner_urn' => $trackOwner->urn,
        //         'soundcloud_repost_id' => 'sc-repost-' . rand(1000, 9999),
        //         'credits_earned' => rand(1, 100) / 10,
        //         'reposted_at' => now()->subDays(rand(0, 30)),
        //         'campaign_id' => null,
        //         'repost_request_id' => null,
        //     ];

        //     $associateWithCampaign = rand(0, 1) === 0;

        //     if ($associateWithCampaign && $campaigns->isNotEmpty()) {
        //         $repostData['campaign_id'] = $campaigns->random()->id;
        //     } elseif ($repostRequests->isNotEmpty()) {
        //         $repostData['repost_request_id'] = $repostRequests->random()->id;
        //     } else {
        //         $this->command->warn('No campaign or repost request available. Skipping this repost.');
        //         continue;
        //     }

        //     Repost::create($repostData);
        // }
    }
}
