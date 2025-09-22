<?php

namespace Database\Seeders;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // // Clear existing data if needed
        // DB::table('playlist_tracks')->truncate();

        // Get some playlists and tracks
        $playlists = Playlist::all()->pluck('soundcloud_urn');
        $tracks = Track::all()->pluck('urn');

        // For demo: assign first 2 tracks to each playlist
        foreach ($playlists as $playlistUrn) {
            foreach ($tracks->take(2) as $trackUrn) {
                DB::table('playlist_tracks')->insert([
                    'playlist_urn' => $playlistUrn,
                    'track_urn' => $trackUrn,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Rotate tracks so next playlist gets next 2 tracks
            $tracks->push($tracks->shift());
        }
    }
}
