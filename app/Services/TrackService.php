<?php

namespace App\Services;

use App\Http\Traits\FileManagementTrait;
use App\Models\Track;

class TrackService
{
    use FileManagementTrait;

   public function getTracks($orderBy = 'id', $order = 'asc')
    {
        return Track::orderBy($orderBy, $order)->latest();
    }
    public function getTrack(string $encryptedValue ,  string $field = 'id'): Track
    {
        return Track::where($field, decrypt($encryptedValue))->first();
    }

    public function UpdateOrCreateSoundCloudTrack(array $tracks)
    {
        foreach ($tracks as $track) {
            Track::updateOrCreate(
                [
                    'soundcloud_track_id' => $track['id'], // unique check
                ],
                [
                    'user_urn'                 => user()->urn,
                    'kind'                     => $track['kind'] ?? null,
                    'urn'                      => $track['urn'] ?? null,
                    'duration'                 => $track['duration'] ?? 0,
                    'commentable'              => $track['commentable'] ?? false,
                    'comment_count'            => $track['comment_count'] ?? 0,
                    'sharing'                  => $track['sharing'] ?? null,
                    'tag_list'                 => $track['tag_list'] ?? null,
                    'streamable'               => $track['streamable'] ?? false,
                    'embeddable_by'            => $track['embeddable_by'] ?? null,
                    'purchase_url'             => $track['purchase_url'] ?? null,
                    'purchase_title'           => $track['purchase_title'] ?? null,
                    'genre'                    => $track['genre'] ?? null,
                    'title'                    => $track['title'] ?? null,
                    'description'              => $track['description'] ?? null,
                    'label_name'               => $track['label_name'] ?? null,
                    'release'                  => $track['release'] ?? null,
                    'key_signature'            => $track['key_signature'] ?? null,
                    'isrc'                     => $track['isrc'] ?? null,
                    'bpm'                      => $track['bpm'] ?? null,
                    'release_year'             => $track['release_year'] ?? null,
                    'release_month'            => $track['release_month'] ?? null,
                    'release_day'              => $track['release_day'] ?? null,
                    'license'                  => $track['license'] ?? null,
                    'uri'                      => $track['uri'] ?? null,
                    'permalink_url'            => $track['permalink_url'] ?? null,
                    'artwork_url'              => $track['artwork_url'] ?? null,
                    'stream_url'               => $track['stream_url'] ?? null,
                    'download_url'             => $track['download_url'] ?? null,
                    'waveform_url'             => $track['waveform_url'] ?? null,
                    'available_country_codes'  => $track['available_country_codes'] ?? null,
                    'secret_uri'               => $track['secret_uri'] ?? null,
                    'user_favorite'            => $track['user_favorite'] ?? false,
                    'user_playback_count'      => $track['user_playback_count'] ?? 0,
                    'playback_count'           => $track['playback_count'] ?? 0,
                    'download_count'           => $track['download_count'] ?? 0,
                    'favoritings_count'        => $track['favoritings_count'] ?? 0,
                    'reposts_count'            => $track['reposts_count'] ?? 0,
                    'downloadable'             => $track['downloadable'] ?? false,
                    'access'                   => $track['access'] ?? null,
                    'policy'                   => $track['policy'] ?? null,
                    'monetization_model'       => $track['monetization_model'] ?? null,
                    'metadata_artist'          => $track['metadata_artist'] ?? null,
                    'created_at_soundcloud'    => isset($track['created_at']) ? \Carbon\Carbon::parse($track['created_at']) : null,
                    'type'                     => $track['type'] ?? null,

                    // Author info
                    'author_username'          => $track['user']['username'] ?? null,
                    'author_soundcloud_id'     => $track['user']['id'] ?? null,
                    'author_soundcloud_urn'    => $track['user']['urn'] ?? null,
                    'author_soundcloud_kind'   => $track['user']['kind'] ?? null,
                    'author_soundcloud_permalink_url' => $track['user']['permalink_url'] ?? null,
                    'author_soundcloud_permalink'     => $track['user']['permalink'] ?? null,
                    'author_soundcloud_uri'    => $track['user']['uri'] ?? null,

                    'last_sync_at'             => now(),
                ]
            );
        }

    }
   
}
