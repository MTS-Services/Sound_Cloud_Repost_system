<?php

namespace Database\Seeders;

use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Track::create([
            'user_urn' => 'urn:sc:users:1001',

            'kind' => 'track',
            'soundcloud_track_id' => (string)Str::uuid(),
            'urn' => 'urn:soundcloud:tracks:' . rand(1000000, 9999999),
            'duration' => rand(100000, 500000),
            'commentable' => true,
            'comment_count' => rand(0, 500),
            'sharing' => 'public',
            'tag_list' => 'electronic,house',
            'streamable' => true,
            'embeddable_by' => 'all',
            'purchase_url' => 'https://example.com/buy/' . rand(1, 10),
            'purchase_title' => 'Buy Track #' . rand(1, 10),
            'genre' => 'Electronic',
            'title' => 'Sample Track ' . rand(1, 10),
            'description' => 'This is a sample track description for track #' . rand(1, 10),
            'label_name' => 'Label ' . rand(1, 10),
            'release' => '2025-0' . rand(1, 9) . '-01',
            'key_signature' => 'C#m',
            'isrc' => 'US-R1L-25' . rand(10000, 99999),
            'bpm' => (string)rand(90, 140),
            'release_year' => '2025',
            'release_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
            'license' => 'all-rights-reserved',
            'uri' => 'soundcloud://sounds/' . rand(1000000, 9999999),

            'permalink_url' => 'https://soundcloud.com/user/sample-track-' . rand(1, 10),
            'artwork_url' => 'https://via.placeholder.com/200x200.png?text=Track+' . rand(1, 10),
            'stream_url' => 'https://api.soundcloud.com/stream/' . rand(1, 10),
            'download_url' => 'https://api.soundcloud.com/download/' . rand(1, 10),
            'waveform_url' => 'https://waveform.example.com/' . rand(1, 10),
            'available_country_codes' => 'US,GB,CA',
            'secret_uri' => 'https://soundcloud.com/secret/' . Str::random(10),
            'user_favorite' => (bool)rand(0, 1),
            'user_playback_count' => rand(0, 10000),
            'playback_count' => rand(1000, 100000),
            'download_count' => rand(0, 5000),
            'favoritings_count' => rand(0, 3000),
            'reposts_count' => rand(0, 2000),
            'downloadable' => (bool)rand(0, 1),
            'access' => 'public',
            'policy' => 'ALLOW',
            'monetization_model' => 'AD_SUPPORTED',
            'metadata_artist' => 'Artist ',
            'created_at_soundcloud' => Carbon::now()->subDays(rand(1, 30)),

            'type' => 'original',

            // Author details
            'author_username' => 'author_' . rand(1, 10),
            'author_soundcloud_id' => rand(100000, 999999),
            'author_soundcloud_urn' => 'urn:soundcloud:users:' . rand(100000, 999999),
            'author_soundcloud_kind' => 'user',
            'author_soundcloud_permalink_url' => 'https://soundcloud.com/author_' . rand(1, 10),
            'author_soundcloud_permalink' => 'author_' . rand(1, 10),
            'author_soundcloud_uri' => 'soundcloud://users/' . rand(100000, 999999),

        ]);
        Track::create([
            'user_urn' => 'urn:sc:users:1001',

            'kind' => 'track',
            'soundcloud_track_id' => (string)Str::uuid(),
            'urn' => 'urn:soundcloud:tracks:' . rand(1000000, 9999999),
            'duration' => rand(100000, 500000),
            'commentable' => true,
            'comment_count' => rand(0, 500),
            'sharing' => 'public',
            'tag_list' => 'electronic,house',
            'streamable' => true,
            'embeddable_by' => 'all',
            'purchase_url' => 'https://example.com/buy/' . rand(1, 10),
            'purchase_title' => 'Buy Track #' . rand(1, 10),
            'genre' => 'Electronic',
            'title' => 'Sample Track ' . rand(1, 10),
            'description' => 'This is a sample track description for track #' . rand(1, 10),
            'label_name' => 'Label ' . rand(1, 10),
            'release' => '2025-0' . rand(1, 9) . '-01',
            'key_signature' => 'C#m',
            'isrc' => 'US-R1L-25' . rand(10000, 99999),
            'bpm' => (string)rand(90, 140),
            'release_year' => '2025',
            'release_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
            'license' => 'all-rights-reserved',
            'uri' => 'soundcloud://sounds/' . rand(1000000, 9999999),

            'permalink_url' => 'https://soundcloud.com/user/sample-track-' . rand(1, 10),
            'artwork_url' => 'https://via.placeholder.com/200x200.png?text=Track+' . rand(1, 10),
            'stream_url' => 'https://api.soundcloud.com/stream/' . rand(1, 10),
            'download_url' => 'https://api.soundcloud.com/download/' . rand(1, 10),
            'waveform_url' => 'https://waveform.example.com/' . rand(1, 10),
            'available_country_codes' => 'US,GB,CA',
            'secret_uri' => 'https://soundcloud.com/secret/' . Str::random(10),
            'user_favorite' => (bool)rand(0, 1),
            'user_playback_count' => rand(0, 10000),
            'playback_count' => rand(1000, 100000),
            'download_count' => rand(0, 5000),
            'favoritings_count' => rand(0, 3000),
            'reposts_count' => rand(0, 2000),
            'downloadable' => (bool)rand(0, 1),
            'access' => 'public',
            'policy' => 'ALLOW',
            'monetization_model' => 'AD_SUPPORTED',
            'metadata_artist' => 'Artist ',
            'created_at_soundcloud' => Carbon::now()->subDays(rand(1, 30)),

            'type' => 'original',

            // Author details
            'author_username' => 'author_' . rand(1, 10),
            'author_soundcloud_id' => rand(100000, 999999),
            'author_soundcloud_urn' => 'urn:soundcloud:users:' . rand(100000, 999999),
            'author_soundcloud_kind' => 'user',
            'author_soundcloud_permalink_url' => 'https://soundcloud.com/author_' . rand(1, 10),
            'author_soundcloud_permalink' => 'author_' . rand(1, 10),
            'author_soundcloud_uri' => 'soundcloud://users/' . rand(100000, 999999),

        ]);
        Track::create([
            'user_urn' => 'urn:sc:users:1001',

            'kind' => 'track',
            'soundcloud_track_id' => (string)Str::uuid(),
            'urn' => 'urn:soundcloud:tracks:' . rand(1000000, 9999999),
            'duration' => rand(100000, 500000),
            'commentable' => true,
            'comment_count' => rand(0, 500),
            'sharing' => 'public',
            'tag_list' => 'electronic,house',
            'streamable' => true,
            'embeddable_by' => 'all',
            'purchase_url' => 'https://example.com/buy/' . rand(1, 10),
            'purchase_title' => 'Buy Track #' . rand(1, 10),
            'genre' => 'Electronic',
            'title' => 'Sample Track ' . rand(1, 10),
            'description' => 'This is a sample track description for track #' . rand(1, 10),
            'label_name' => 'Label ' . rand(1, 10),
            'release' => '2025-0' . rand(1, 9) . '-01',
            'key_signature' => 'C#m',
            'isrc' => 'US-R1L-25' . rand(10000, 99999),
            'bpm' => (string)rand(90, 140),
            'release_year' => '2025',
            'release_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
            'license' => 'all-rights-reserved',
            'uri' => 'soundcloud://sounds/' . rand(1000000, 9999999),

            'permalink_url' => 'https://soundcloud.com/user/sample-track-' . rand(1, 10),
            'artwork_url' => 'https://via.placeholder.com/200x200.png?text=Track+' . rand(1, 10),
            'stream_url' => 'https://api.soundcloud.com/stream/' . rand(1, 10),
            'download_url' => 'https://api.soundcloud.com/download/' . rand(1, 10),
            'waveform_url' => 'https://waveform.example.com/' . rand(1, 10),
            'available_country_codes' => 'US,GB,CA',
            'secret_uri' => 'https://soundcloud.com/secret/' . Str::random(10),
            'user_favorite' => (bool)rand(0, 1),
            'user_playback_count' => rand(0, 10000),
            'playback_count' => rand(1000, 100000),
            'download_count' => rand(0, 5000),
            'favoritings_count' => rand(0, 3000),
            'reposts_count' => rand(0, 2000),
            'downloadable' => (bool)rand(0, 1),
            'access' => 'public',
            'policy' => 'ALLOW',
            'monetization_model' => 'AD_SUPPORTED',
            'metadata_artist' => 'Artist ',
            'created_at_soundcloud' => Carbon::now()->subDays(rand(1, 30)),

            'type' => 'original',

            // Author details
            'author_username' => 'author_' . rand(1, 10),
            'author_soundcloud_id' => rand(100000, 999999),
            'author_soundcloud_urn' => 'urn:soundcloud:users:' . rand(100000, 999999),
            'author_soundcloud_kind' => 'user',
            'author_soundcloud_permalink_url' => 'https://soundcloud.com/author_' . rand(1, 10),
            'author_soundcloud_permalink' => 'author_' . rand(1, 10),
            'author_soundcloud_uri' => 'soundcloud://users/' . rand(100000, 999999),

        ]);
    }
}
