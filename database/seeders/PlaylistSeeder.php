<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // for ($i = 1; $i <= 12; $i++) {
        //     Playlist::create([
        //         'sort_order' => $i,
        //         'user_urn' => User::class::first()->urn,
        //         'duration' => rand(1000, 5000),
        //         'label_id' => null,
        //         'genre' => 'Electronic',
        //         'release_day' => rand(1, 28),
        //         'permalink' => "sample-playlist-$i",
        //         'permalink_url' => "https://soundcloud.com/sample-playlist-$i",
        //         'release_month' => rand(1, 12),
        //         'release_year' => rand(2015, 2025),
        //         'description' => 'This is a sample playlist description.',
        //         'uri' => "soundcloud:sample:uri:$i",
        //         'label_name' => 'Sample Label',
        //         'label' => 'Sample Label Group',
        //         'tag_list' => 'electronic ambient chill',
        //         'track_count' => rand(5, 20),
        //         'last_modified' => Carbon::now(),
        //         'license' => 'all-rights-reserved',
        //         'playlist_type' => 'album',
        //         'type' => 'playlist',
        //         'soundcloud_id' => 100000 + $i,
        //         'soundcloud_urn' => "urn:soundcloud:playlists:$i",
        //         'downloadable' => rand(0, 1),
        //         'likes_count' => rand(0, 1000),
        //         'sharing' => 'public',
        //         'soundcloud_created_at' => Carbon::now()->subDays(rand(1, 100)),
        //         'release' => 'Sample Release',
        //         'tags' => 'house,techno',
        //         'soundcloud_kind' => 'playlist',
        //         'title' => "Sample Playlist Title $i",
        //         'purchase_title' => 'Buy Now',
        //         'ean' => '1234567890123',
        //         'streamable' => true,
        //         'embeddable_by' => 'all',
        //         'purchase_url' => 'https://example.com/buy-now',
        //         'tracks_uri' => "https://api.soundcloud.com/playlists/$i/tracks",
        //         'secret_token' => Str::random(16),
        //         'secret_uri' => "https://api.soundcloud.com/playlists/$i?secret_token=" . Str::random(8),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
