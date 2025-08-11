<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            RoleHasPermissionSeeder::class,
            FeatureCategorySeeder::class,
            FeatureSeeder::class,
            PlanSeeder::class,
            CampaignSeeder::class,
            RepostRequestSeeder::class,
            PlaylistSeeder::class,
            TrackSeeder::class,
            CreditSeeder::class,
            OrderSeeder::class,
            UserInformationSeeder::class,
            RepostSeeder::class,
            FaqSeeder::class,
            PlaylistTrackSeeder::class,

            CustomNotificationSeeder::class,
            // CustomNotificationStatusSeeder::class,
        ]);
    }
}
