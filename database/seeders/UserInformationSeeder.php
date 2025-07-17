<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInformation;
use Faker\Factory as Faker;

class UserInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
        $users = User::all();

        foreach ($users as $user) {
            UserInformation::create([
                'user_id' => $user->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'username' => 'johndoe_' . $user->id,
                'soundcloud_id' => 100000 + $user->id,
                'soundcloud_urn' => 'https://soundcloud.com/users/' . $user->id,
                'soundcloud_kind' => 'user_' . $user->id,
                'soundcloud_permalink_url' => 'https://soundcloud.com/johndoe_' . $user->id,
                'soundcloud_permalink' => 'johndoe_' . $user->id,
                'soundcloud_uri' => 'https://api.soundcloud.com/users/' . $user->id,
                'soundcloud_created_at' => now()->subYears(3),
                'soundcloud_last_modified' => now(),
                'description' => 'This is a sample user description.',
                'country' => 'Bangladesh',
                'city' => 'Dhaka',
                'track_count' => 20,
                'public_favorites_count' => 100,
                'reposts_count' => 50,
                'followers_count' => 500,
                'following_count' => 300,
                'plan' => 'free',
                'myspace_name' => 'johndoe_space',
                'discogs_name' => 'johndoe_disc',
                'website_title' => 'John\'s Website',
                'website' => 'https://example.com',
                'online' => true,
                'comments_count' => 30,
                'like_count' => 200,
                'playlist_count' => 5,
                'private_playlist_count' => 2,
                'private_tracks_count' => 3,
                'primary_email_confirmed' => true,
                'local' => 'en_US',
                'upload_seconds_left' => 1800,
            ]);
        }
    }
}
