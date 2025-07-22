<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'urn' => 1,
            'name' => 'Test User 1',
            'email' => 'user@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000001,
            'status' => User::STATUS_ACTIVE,
            'email' => 'user@dev.com',
            'password' => bcrypt('user@dev.com'),
            'last_synced_at' => now(),
        ]);

        User::create([
            'urn' => 2,
            'name' => 'Test User 2',
            'email' => 'user2@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000002,
            'status' => User::STATUS_ACTIVE,
            'last_synced_at' => now(),
        ]); 
        User::create([
            'urn' => 3,
            'name' => 'Test User 3',
            'email' => 'user3@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000003,
            'status' => User::STATUS_INACTIVE, // Example of different status
            'last_synced_at' => now()->subDays(5), // Example of different sync time
        ]);

        User::create([
            'urn' => 4,
            'name' => 'Test User 4',
            'soundcloud_id' => 1000000004,
            'status' => User::STATUS_ACTIVE,
            'last_synced_at' => now(),
        ]);
        
    }
}
