<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use Illuminate\Database\Eloquent\Collection;

class PlaylistService
{
    private TrackService $trackService;
    /**
     * Create a new class instance.
     */
    public function __construct(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }
    public function getPlaylists($orderBy = 'sort_order', $order = 'asc')
    {
        return Playlist::orderBy($orderBy, $order)->latest()->with('tracks', 'user');
    }
    public function getPlaylist(string $encryptedValue, string $field = 'soundcloud_urn'): Playlist
    {
        return Playlist::where($field, decrypt($encryptedValue))->first();
    }
    public function UpdateOrCreateSoundCloudPlaylistTrack(array $playlists)
    {
        foreach ($playlists as $playlist) {
            foreach ($playlist['tracks'] as $track) {
                $this->trackService->UpdateOrCreateSoundCloudTrack($track);
                PlaylistTrack::updateOrCreate(
                    [
                        'playlist_urn' => $playlist['urn'],
                        'track_urn'    => $track['urn'],
                    ],
                );
            }
        }
    }
}
