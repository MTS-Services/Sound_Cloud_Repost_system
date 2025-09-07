<?php

namespace Database\Seeders;

use App\Models\CustomNotificationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomNotificationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomNotificationStatus::factory()->count(10)->create();
    }
}
