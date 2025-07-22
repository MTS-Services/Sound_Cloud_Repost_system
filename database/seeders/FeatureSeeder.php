<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('features')->insert([
            // Core Features (feature_category_id = 1)
            ['name' => 'Autoboost', 'key' => Feature::FEATURE_KEY_AUTOBOOST, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Campaign Targeting', 'key' => Feature::FEATURE_KEY_CAMPAIGN_TARGETING, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Free Boosts per Campaign', 'key' => Feature::FEATURE_KEY_FREE_BOOSTS_PER_CAMPAIGN, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Open Direct Requests', 'key' => Feature::FEATURE_KEY_OPEN_DIRECT_REQUESTS, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Power Hour Multiplier', 'key' => Feature::FEATURE_KEY_POWER_HOUR_MULTIPLIER, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Priority Campaign Visibility', 'key' => Feature::FEATURE_KEY_PRIORITY_CAMPAIGN_VISIBILITY, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Priority Direct Repost Requests', 'key' => Feature::FEATURE_KEY_PRIORITY_DIRECT_REPOST_REQUESTS, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Promote Multiple SC Accounts', 'key' => Feature::FEATURE_KEY_PROMOTE_MULTIPLE_SC_ACCOUNTS, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Simultaneous Campaigns', 'key' => Feature::FEATURE_KEY_SIMULTANEOUS_CAMPAIGNS, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'Sitewide Discount', 'key' => Feature::FEATURE_KEY_SITEWIDE_DISCOUNT, 'type' => 0, 'feature_category_id' => 1],
            ['name' => 'SoundCloud Chart Notifier', 'key' => Feature::FEATURE_KEY_SOUNDCLOUD_CHART_NOTIFIER, 'type' => 0, 'feature_category_id' => 1],

            // Campaign Features (feature_category_id = 2)
            ['name' => 'Featured Campaigns', 'key' => Feature::FEATURE_KEY_FEATURED_CAMPAIGNS, 'type' => 0, 'feature_category_id' => 2],
            ['name' => 'Managed Campaigns', 'key' => Feature::FEATURE_KEY_MANAGED_CAMPAIGNS, 'type' => 0, 'feature_category_id' => 2],
            ['name' => 'Max Campaign Budget', 'key' => Feature::FEATURE_KEY_MAX_CAMPAIGN_BUDGET, 'type' => 0, 'feature_category_id' => 2],
            ['name' => 'Sort Campaigns by Rating', 'key' => Feature::FEATURE_KEY_SORT_CAMPAIGNS_BY_RATING, 'type' => 0, 'feature_category_id' => 2],
            ['name' => 'Sponsored Follow Campaigns', 'key' => Feature::FEATURE_KEY_SPONSORED_FOLLOW_CAMPAIGNS, 'type' => 0, 'feature_category_id' => 2],

            // Other Features (feature_category_id = 3)
            ['name' => 'Exempt from Inactivity Deduction', 'key' => Feature::FEATURE_KEY_EXEMPT_FROM_INACTIVITY_DEDUCTION, 'type' => 0, 'feature_category_id' => 3],
            ['name' => 'Monitor & Remove Reposts', 'key' => Feature::FEATURE_KEY_MONITOR_AND_REMOVE_REPOSTS, 'type' => 0, 'feature_category_id' => 3],
            ['name' => 'Promote Other Socials', 'key' => Feature::FEATURE_KEY_PROMOTE_OTHER_SOCIALS, 'type' => 0, 'feature_category_id' => 3],
            ['name' => 'Waveplayer Artwork', 'key' => Feature::FEATURE_KEY_WAVEPLAYER_ARTWORK, 'type' => 0, 'feature_category_id' => 3],
        ]);
    }
}
