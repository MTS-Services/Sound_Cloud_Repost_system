<?php

namespace Database\Seeders;

use App\Models\CustomNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomNotification::factory()->count(100)->create();
    }
}
