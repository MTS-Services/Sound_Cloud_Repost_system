<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Playlist;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\UserAnalytics;
use Carbon\Carbon;
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


        for ($i = 0; $i <= 6; $i++) {
            UserAnalytics::create([
                'owner_user_urn' => $userUrn,
                'act_user_urn' => $userUrn,
                'track_urn' => $campaign->music->urn,
                'actionable_id' => $campaign->id,
                'actionable_type' => Campaign::class,
                'genre' => $campaign->target_genre,
                'type' => $i,
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
    }
}
