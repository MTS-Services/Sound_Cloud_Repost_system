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
            'email' => 'user@dev.com',
            'password' => Hash::make('user@dev.com'),
            'last_synced_at' => now(),
            'token' => 'test-token-1',
            'refresh_token' => 'test-refresh-token-1',
            'expires_in' => 3600,
            'urn' => 'urn:sc:users:1000000001',
        ]);
        
    }
}
