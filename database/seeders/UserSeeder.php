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
        User::create(
            [
                'name' => 'Developer',
                'soundcloud_id' => 1001,
                'status' => User::STATUS_ACTIVE,
                'email' => 'developer@dev.com',
                'password' => Hash::make('developer@dev.com'),
                'last_synced_at' => now(),
                'token' => 'test-token-1',
                'refresh_token' => 'test-refresh-token-1',
                'expires_in' => 3600,
                'urn' => 'urn:sc:users:1001',
            ]
        );
        User::create(
            [
                'name' => 'Developer 2',
                'soundcloud_id' => 1002,
                'status' => User::STATUS_ACTIVE,
                'email' => 'Developer2@dev.com',
                'password' => Hash::make('Developer2@dev.com'),
                'last_synced_at' => now(),
                'token' => 'test-token-2',
                'refresh_token' => 'test-refresh-token-2',
                'expires_in' => 3600,
                'urn' => 'urn:sc:users:1002',
            ]
        );
        // User::create(
        //     [
        //         'name' => 'Test User 3',
        //         'soundcloud_id' => 1003,
        //         'status' => User::STATUS_ACTIVE,
        //         'email' => 'user3@dev.com',
        //         'password' => Hash::make('user3@dev.com'),
        //         'last_synced_at' => now(),
        //         'token' => 'test-token-3',
        //         'refresh_token' => 'test-refresh-token-3',
        //         'expires_in' => 3600,
        //         'urn' => 'urn:sc:users:1003',
        //     ]
        // );
        // User::create(
        //     [
        //         'name' => 'Test User 4',
        //         'soundcloud_id' => 1004,
        //         'status' => User::STATUS_ACTIVE,
        //         'email' => 'user4@dev.com',
        //         'password' => Hash::make('user4@dev.com'),
        //         'last_synced_at' => now(),
        //         'token' => 'test-token-4',
        //         'refresh_token' => 'test-refresh-token-4',
        //         'expires_in' => 3600,
        //         'urn' => 'urn:sc:users:1004',
        //     ],
        // );

        // User::create(
        //     [
        //         'name' => 'Test User 5',
        //         'soundcloud_id' => 1005,
        //         'status' => User::STATUS_ACTIVE,
        //         'email' => 'user5@dev.com',
        //         'password' => Hash::make('user5@dev.com'),
        //         'last_synced_at' => now(),
        //         'token' => 'test-token-5',
        //         'refresh_token' => 'test-refresh-token-5',
        //         'expires_in' => 3600,
        //         'urn' => 'urn:sc:users:1005',
        //     ],
        // );
        // User::create(
        //     [
        //         'name' => 'Test User 6',
        //         'soundcloud_id' => 1006,
        //         'status' => User::STATUS_ACTIVE,
        //         'email' => 'user6@dev.com',
        //         'password' => Hash::make('user6@dev.com'),
        //         'last_synced_at' => now(),
        //         'token' => 'test-token-6',
        //         'refresh_token' => 'test-refresh-token-6',
        //         'expires_in' => 3600,
        //         'urn' => 'urn:sc:users:1006',
        //     ],
        // );
        // User::create(
        //     [
        //         'name' => 'Wasif Ahmed',
        //         'soundcloud_id' => 1007,
        //         'status' => User::STATUS_ACTIVE,
        //         'email' => 'dev.wasifahmed@gmail.com',
        //         'password' => Hash::make('dev.wasifahmed@gmail.com'),
        //         'last_synced_at' => now(),
        //         'token' => 'test-token-7',
        //         'refresh_token' => 'test-refresh-token-7',
        //         'expires_in' => 3600,
        //         'urn' => 'urn:sc:users:1007',
        //     ],
        // );
    }
}
