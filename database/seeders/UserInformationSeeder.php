<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a random user with a valid URN
        $user = User::first();

        if (!$user || !$user->urn) {
            $this->command->warn('No valid user found with URN. Please seed the users table first.');
            return;
        }

        $soundcloudId = fake()->unique()->numberBetween(100000, 999999);

        UserInformation::create([
            'sort_order' => 1,
            'user_urn' => $user->urn,

            'first_name' => "Developer",
            'last_name' => "Account",
            'full_name' => "Developer Account",
            'username' => "developer",
            // 'first_name' => fake()->firstName(),
            // 'last_name' => fake()->lastName(),
            // 'full_name' => fake()->name(),
            // 'username' => fake()->userName(),

            'soundcloud_id' => $soundcloudId,
            'soundcloud_urn' => 'urn:soundcloud:users:' . $soundcloudId,
            'soundcloud_kind' => 'user',
            'soundcloud_permalink_url' => 'https://soundcloud.com/' . Str::slug(fake()->userName()),
            'soundcloud_permalink' => Str::slug(fake()->userName()),
            'soundcloud_uri' => 'https://api.soundcloud.com/users/' . $soundcloudId,

            'soundcloud_created_at' => Carbon::now()->subDays(rand(10, 1000)),
            'soundcloud_last_modified' => Carbon::now()->subDays(rand(1, 9)),

            'description' => fake()->sentence(),
            'country' => fake()->country(),
            'city' => fake()->city(),

            'track_count' => rand(1, 50),
            'public_favorites_count' => rand(10, 200),
            'reposts_count' => rand(5, 100),
            'followers_count' => rand(100, 5000),
            'following_count' => rand(10, 1000),

            'plan' => fake()->randomElement(['Free', 'Pro', 'Unlimited']),
            'myspace_name' => fake()->userName(),
            'discogs_name' => fake()->userName(),
            'website_title' => fake()->sentence(2),
            'website' => fake()->url(),

            'online' => fake()->boolean(),
            'comments_count' => rand(0, 300),
            'like_count' => rand(0, 1000),
            'playlist_count' => rand(1, 50),
            'private_playlist_count' => rand(0, 10),
            'private_tracks_count' => rand(0, 10),

            'primary_email_confirmed' => fake()->boolean(),
            'local' => fake()->locale(),
            'upload_seconds_left' => rand(1000, 999999),

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

}
