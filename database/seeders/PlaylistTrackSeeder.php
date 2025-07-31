<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('playlist_tracks')->insert([
            [
                'playlist_urn' => 'urn:soundcloud:playlists:1',
                'track_urn' => 'urn:soundcloud:tracks:4107564',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'playlist_urn' => 'urn:soundcloud:playlists:1',
                'track_urn' => 'urn:soundcloud:tracks:5749548',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
