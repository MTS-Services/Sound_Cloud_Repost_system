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
        return Playlist::orderBy($orderBy, $order)->latest();
    }
    public function getPlaylist(string $encryptedUrn): Playlist | Collection
    {
        return Playlist::where('urn', decrypt($encryptedUrn))->first();
    }
    public function getPlaylistTracks( string $soundcloudUrn)
    {
        $playlistTracks = Playlist::with('tracks')->where('soundcloud_urn', decrypt($soundcloudUrn))->latest();
        return $playlistTracks;
    }
}
