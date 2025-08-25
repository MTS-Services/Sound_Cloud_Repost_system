<?php

namespace App\Services;

use App\Models\Playlist;
use Illuminate\Database\Eloquent\Collection;

class PlaylistService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getPlaylists($orderBy = 'sort_order', $order = 'asc')
    {
        return Playlist::orderBy($orderBy, $order)->latest()->with('tracks', 'user');
    }
    public function getPlaylist(string $encryptedValue, string $field = 'soundcloud_urn'): Playlist
    {
        return Playlist::where($field, decrypt($encryptedValue))->first();
    }
    public function UpdateOrCreateSoundCloudTrack(array $playlists)
    {
        // 
    }
}
