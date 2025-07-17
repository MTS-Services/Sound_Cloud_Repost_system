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
            'name' => 'Test User 1',
            'email' => 'user@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000001,
            'status' => User::STATUS_ACTIVE,
            'last_synced_at' => now(),
        ]);

        User::create([
            'name' => 'Test User 2',
            'email' => 'user2@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000002,
            'status' => User::STATUS_ACTIVE,
            'last_synced_at' => now(),
        ]); 
        User::create([
            'name' => 'Test User 3',
            'email' => 'user3@dev.com',
            'password' => Hash::make('user@dev.com'),
            'soundcloud_id' => 1000000003,
            'status' => User::STATUS_ACTIVE,
            'last_synced_at' => now(),
        ]);
        
    }
}
