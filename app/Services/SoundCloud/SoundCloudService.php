<?php

namespace App\Services\SoundCloud;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Product;
use App\Models\Repost;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\Subscription;
use App\Models\Track;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class SoundCloudService
{
    /**
     * The base URL for the SoundCloud API.
     *
     * @var string
     */
    protected string $baseUrl = 'https://api.soundcloud.com';

    /**
     * The base URL for the SoundCloud OAuth.
     *
     * IMPORTANT: This has been corrected to use the official API domain.
     * The 'secure.soundcloud.com/oauth2/token' endpoint was causing a 404 error.
     *
     * @var string
     */
    protected string $oauthUrl = 'https://api.soundcloud.com';

    /**
     * Makes an authenticated API request to SoundCloud, ensuring a valid token is used.
     * This is the central method for all API interactions.
     *
     * @param User $user The user model instance.
     * @param string $method The HTTP method (e.g., 'get', 'post', 'put').
     * @param string $endpoint The API endpoint path (e.g., '/me/tracks').
     * @param array $options Request options (e.g., 'query' for GET, 'json' for POST).
     * @param string $errorMessage A user-friendly error message.
     * @return array The JSON response from the API.
     * @throws Exception
     */
    public function makeApiRequest(User $user, string $method, string $endpoint, array $options, string $errorMessage): array
    {
        // $this->ensureSoundCloudConnection($user);

        // // Before every API call, check if the token needs to be refreshed.
        // $this->refreshUserTokenIfNeeded($user);

        // Retrieve the refreshed user instance and token to ensure we have the latest data.
        $user->refresh();

        try {
            // Use Laravel's Http facade with the token helper
            $response = Http::withToken($user->token)
                ->$method("{$this->baseUrl}{$endpoint}", $options);

            if ($response->successful()) {
                return $response->json();
            }

            // Log the error for debugging
            Log::error('SoundCloud API Error', [
                'user_urn' => $user->urn,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);

            throw new Exception("{$errorMessage} Status: " . $response->status());
        } catch (Exception $e) {
            // Re-throw the exception after logging
            Log::error("SoundCloud API Error in {$endpoint}", [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Refreshes the user's access token if it has expired.
     * This method is called automatically before every API request.
     *
     * @param User $user The user model instance.
     * @throws Exception
     */
    public function refreshUserTokenIfNeeded(User $user): void
    {
        $this->ensureSoundCloudConnection($user);

        // Check if the token needs a refresh based on the stored `last_synced_at` and `expires_in`.
        $expirationTime = is_null($user->last_synced_at) ? null : $user->last_synced_at->addSeconds($user->expires_in);

        // If the token is null, has no expiration time, or is past its expiration, we need to refresh.
        if (is_null($user->token) || is_null($expirationTime) || $expirationTime->isPast()) {

            Log::info('SoundCloud token expired or missing. Attempting to refresh for user ' . $user->urn);

            if (is_null($user->refresh_token)) {
                Log::warning('Attempted to refresh token without a refresh token available', [
                    'user_urn' => $user->urn,
                ]);
                throw new Exception('No refresh token available. User must re-authenticate.');
            }

            try {
                $response = Http::asForm()->post("{$this->oauthUrl}/oauth2/token", [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('services.soundcloud.client_id'),
                    'client_secret' => config('services.soundcloud.client_secret'),
                    'refresh_token' => $user->refresh_token,
                ]);

                if (!$response->successful()) {
                    Log::error('Failed to refresh token from SoundCloud API', [
                        'user_urn' => $user->urn,
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                    ]);
                    throw new Exception('Failed to refresh token: ' . $response->body());
                }

                $data = $response->json();

                // Update the user model with the new credentials.
                $user->update([
                    'token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'], // This is crucial for token rotation
                    'expires_in' => $data['expires_in'],
                    'last_synced_at' => now(), // Important: update the timestamp of the refresh
                ]);

                Log::info('SoundCloud access token refreshed successfully for user ' . $user->urn);
            } catch (Exception $e) {
                Log::error('Token refresh failed in refreshUserTokenIfNeeded', [
                    'user_urn' => $user->urn,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }
    }

    /**
     * Throws an exception if the user is not connected to SoundCloud.
     *
     * @param User $user
     * @throws Exception
     */
    public function ensureSoundCloudConnection(User $user): void
    {
        // This is a good place to check for a basic connection. You might need to add a `isSoundCloudConnected()` method to your User model.
        // For example: `return !is_null($this->token) && !is_null($this->refresh_token);`
        if (!$user->token || !$user->refresh_token) {
            throw new Exception('User is not connected to SoundCloud.');
        }
    }

    /* =========================================================================
     * Your public methods that need to interact with the SoundCloud API
     * All these methods will now automatically refresh the token
     * by calling `makeApiRequest`
     * =========================================================================
     */

    public function getUserTracks(User $user, int $limit = 50, int $offset = 0): array
    {
        $this->refreshUserTokenIfNeeded($user);
        return $this->makeApiRequest(
            $user,
            'get',
            "/me/tracks",
            ['query' => ['limit' => min($limit, 200), 'offset' => $offset]],
            'Failed to fetch tracks from SoundCloud API.'
        );
    }

    /**
     * Syncs a user's tracks from SoundCloud API.
     *
     * @param User $user The user to sync tracks for.
     * @param array $tracksData The tracks data to sync, or an empty array to fetch from SoundCloud API.
     * @param string|null $playlist_urn The urn of the playlist to sync tracks for, or null to sync all tracks.
     * @return int The number of tracks synced.
     * @throws Exception If there's an error syncing tracks.
     */

    public function syncUserTracks(User $user, $tracksData, $playlist_urn = null): int
    {
        try {
            $limit = 2000000000000;
            if (empty($tracksData)) {
                $tracksData = $this->getUserTracks($user, $limit); // This call now handles the refresh
            }

            $syncedCount = 0;
            $trackIdsInResponse = [];

            foreach ($tracksData as $trackData) {
                $trackIdsInResponse[] = $trackData['id'];

                $userUrn = $trackData['user']['urn'];

                $track_author = User::updateOrCreate([
                    'urn' => $userUrn,
                ], [
                    'soundcloud_id' => $trackData['user']['id'],
                    'name' => $trackData['user']['username'],
                    'nickname' => $trackData['user']['username'],
                    'avatar' => $trackData['user']['avatar_url'],
                    'soundcloud_permalink_url' => $trackData['user']['permalink_url'],
                ]);

                if (is_null($track_author->last_synced_at)) {
                    $track_author->update(['status' => User::STATUS_INACTIVE]);
                }

                // Log::info('SoundCloud sync started for playlist ' . $playlist_urn . ' for user ' . $userUrn);
                // Log::info('track Id:' . $trackData['id']);
                $commonTrackData = [
                    'user_urn' => $trackData['user']['urn'] ?? null,
                    'kind' => $trackData['kind'] ?? null,
                    'duration' => $trackData['duration'] ?? 0,
                    'commentable' => $trackData['commentable'] ?? false,
                    'comment_count' => $trackData['comment_count'] ?? 0,
                    'sharing' => $trackData['sharing'] ?? null,
                    'tag_list' => $trackData['tag_list'] ?? '',
                    'streamable' => $trackData['streamable'] ?? false,
                    'embeddable_by' => $trackData['embeddable_by'] ?? null,
                    'purchase_url' => $trackData['purchase_url'] ?? null,
                    'purchase_title' => $trackData['purchase_title'] ?? null,
                    'genre' => $trackData['genre'] ?? null,
                    'title' => $trackData['title'] ?? null,
                    'description' => $trackData['description'] ?? null,
                    'label_name' => $trackData['label_name'] ?? null,
                    'release' => $trackData['release'] ?? null,
                    'key_signature' => $trackData['key_signature'] ?? null,
                    'isrc' => $trackData['isrc'] ?? null,
                    'bpm' => $trackData['bpm'] ?? null,
                    'release_year' => $trackData['release_year'] ?? null,
                    'release_month' => $trackData['release_month'] ?? null,
                    'release_day' => $trackData['release_day'] ?? null,
                    'license' => $trackData['license'] ?? null,
                    'uri' => $trackData['uri'] ?? null,
                    'permalink_url' => $trackData['permalink_url'] ?? null,
                    'artwork_url' => $trackData['artwork_url'] ?? null,
                    'stream_url' => $trackData['stream_url'] ?? null,
                    'download_url' => $trackData['download_url'] ?? null,
                    'waveform_url' => $trackData['waveform_url'] ?? null,
                    'available_country_codes' => $trackData['available_country_codes'] ?? null,
                    'secret_uri' => $trackData['secret_uri'] ?? null,
                    'user_favorite' => $trackData['user_favorite'] ?? false,
                    'user_playback_count' => $trackData['user_playback_count'] ?? 0,
                    'playback_count' => $trackData['playback_count'] ?? 0,
                    'download_count' => $trackData['download_count'] ?? 0,
                    'favoritings_count' => $trackData['favoritings_count'] ?? 0,
                    'reposts_count' => $trackData['reposts_count'] ?? 0,
                    'downloadable' => $trackData['downloadable'] ?? false,
                    'access' => $trackData['access'] ?? null,
                    'policy' => $trackData['policy'] ?? null,
                    'monetization_model' => $trackData['monetization_model'] ?? null,
                    'metadata_artist' => $trackData['metadata_artist'] ?? null,
                    'created_at_soundcloud' => isset($trackData['created_at']) ? Carbon::parse($trackData['created_at'])->toDateTimeString() : null,
                    'type' => $trackData['type'] ?? null,
                    'last_sync_at' => now(),
                    'author_username' => $trackData['user']['username'] ?? null,
                    'author_soundcloud_id' => $trackData['user']['id'] ?? null,
                    'author_soundcloud_urn' => $trackData['user']['urn'] ?? null,
                    'author_soundcloud_kind' => $trackData['user']['kind'] ?? null,
                    'author_soundcloud_permalink_url' => $trackData['user']['permalink_url'] ?? null,
                    'author_soundcloud_permalink' => $trackData['user']['permalink'] ?? null,
                    'author_soundcloud_uri' => $trackData['user']['uri'] ?? null,
                ];

                // Log::info('Track Data:' . json_encode($commonTrackData));

                // Log::info('Searching for existing track with SoundCloud ID: ' . $trackData['id']);


                // $track = Track::where('soundcloud_track_id', $trackData['id'])->first();

                // Log::info('Track:' . json_encode($track));


                $track = Track::updateOrCreate(
                    [
                        'soundcloud_track_id' => $trackData['id'],
                        'urn' => $trackData['urn']
                    ],
                    $commonTrackData
                );

                // Log::info("Successfully synced track {$track->soundcloud_track_id} for user {$user->urn}.");

                // if ($track_author && $track_author->urn !== $user->urn && is_null($playlist_urn)) {
                //     Repost::create([
                //         'reposter_urn' => $user->urn,
                //         'track_owner_urn' => $track->user_urn,
                //         'track_id' => $track->id,
                //         'reposted_at' => $track->created_at_soundcloud
                //     ]);
                // } elseif ($playlist_urn) {
                if ($playlist_urn && $track->urn) {
                    PlaylistTrack::updateOrCreate([
                        'playlist_urn' => $playlist_urn,
                        'track_urn' => $track->urn,
                    ]);
                }
                // }

                if ($track->wasRecentlyCreated) {
                    $syncedCount++;
                }
            }

            if (is_null($playlist_urn)) {
                $tracksToDelete = Track::where('author_soundcloud_urn', $user->urn)
                    ->whereNotIn('soundcloud_track_id', $trackIdsInResponse)
                    ->pluck('id');

                if ($tracksToDelete->isNotEmpty()) {
                    Track::destroy($tracksToDelete);
                    Log::info("Successfully deleted " . count($tracksToDelete) . " tracks for user {$user->urn} that are no longer present on SoundCloud.");
                }
            } else {
                $tracksToDelete = PlaylistTrack::where('playlist_urn', $playlist_urn)
                    ->whereNotIn('track_urn', array_map(function ($t) {
                        return $t['urn'];
                    }, $tracksData))
                    ->pluck('id');
                if ($tracksToDelete->isNotEmpty()) {
                    PlaylistTrack::destroy($tracksToDelete);
                    Log::info("Successfully deleted " . count($tracksToDelete) . " tracks from playlist {$playlist_urn} that are no longer present on SoundCloud.");
                }
            }

            Log::info("Successfully synced {$syncedCount} tracks for user {$user->urn}.");
            return $syncedCount;
        } catch (Exception $e) {
            Log::error('Error syncing user tracks in syncUserTracks', [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function mapCommonTrackData(array $trackData): array
    {
        return [
            'user_urn' => $trackData['user']['urn'] ?? null,
            'kind' => $trackData['kind'] ?? null,
            'duration' => $trackData['duration'] ?? 0,
            'commentable' => $trackData['commentable'] ?? false,
            'comment_count' => $trackData['comment_count'] ?? 0,
            'sharing' => $trackData['sharing'] ?? null,
            'tag_list' => $trackData['tag_list'] ?? '',
            'streamable' => $trackData['streamable'] ?? false,
            'embeddable_by' => $trackData['embeddable_by'] ?? null,
            'purchase_url' => $trackData['purchase_url'] ?? null,
            'purchase_title' => $trackData['purchase_title'] ?? null,
            'genre' => $trackData['genre'] ?? null,
            'title' => $trackData['title'] ?? null,
            'description' => $trackData['description'] ?? null,
            'label_name' => $trackData['label_name'] ?? null,
            'release' => $trackData['release'] ?? null,
            'key_signature' => $trackData['key_signature'] ?? null,
            'isrc' => $trackData['isrc'] ?? null,
            'bpm' => $trackData['bpm'] ?? null,
            'release_year' => $trackData['release_year'] ?? null,
            'release_month' => $trackData['release_month'] ?? null,
            'release_day' => $trackData['release_day'] ?? null,
            'license' => $trackData['license'] ?? null,
            'uri' => $trackData['uri'] ?? null,
            'permalink_url' => $trackData['permalink_url'] ?? null,
            'artwork_url' => $trackData['artwork_url'] ?? null,
            'stream_url' => $trackData['stream_url'] ?? null,
            'download_url' => $trackData['download_url'] ?? null,
            'waveform_url' => $trackData['waveform_url'] ?? null,
            'available_country_codes' => $trackData['available_country_codes'] ?? null,
            'secret_uri' => $trackData['secret_uri'] ?? null,
            'user_favorite' => $trackData['user_favorite'] ?? false,
            'user_playback_count' => $trackData['user_playback_count'] ?? 0,
            'playback_count' => $trackData['playback_count'] ?? 0,
            'download_count' => $trackData['download_count'] ?? 0,
            'favoritings_count' => $trackData['favoritings_count'] ?? 0,
            'reposts_count' => $trackData['reposts_count'] ?? 0,
            'downloadable' => $trackData['downloadable'] ?? false,
            'access' => $trackData['access'] ?? null,
            'policy' => $trackData['policy'] ?? null,
            'monetization_model' => $trackData['monetization_model'] ?? null,
            'metadata_artist' => $trackData['metadata_artist'] ?? null,
            'created_at_soundcloud' => isset($trackData['created_at']) ? Carbon::parse($trackData['created_at'])->toDateTimeString() : null,
            'type' => $trackData['type'] ?? null,
            'last_sync_at' => now(),
            'author_username' => $trackData['user']['username'] ?? null,
            'author_soundcloud_id' => $trackData['user']['id'] ?? null,
            'author_soundcloud_urn' => $trackData['user']['urn'] ?? null,
            'author_soundcloud_kind' => $trackData['user']['kind'] ?? null,
            'author_soundcloud_permalink_url' => $trackData['user']['permalink_url'] ?? null,
            'author_soundcloud_permalink' => $trackData['user']['permalink'] ?? null,
            'author_soundcloud_uri' => $trackData['user']['uri'] ?? null,
        ];
    }
    public function unknownTrackAdd($trackData): int
    {
        try {
            $userUrn = $trackData['user']['urn'];
            $track_author = User::updateOrCreate([
                'urn' => $userUrn,
            ], [
                'soundcloud_id' => $trackData['user']['id'],
                'name' => $trackData['user']['username'],
                'nickname' => $trackData['user']['username'],
                'avatar' => $trackData['user']['avatar_url'],
                'soundcloud_permalink_url' => $trackData['user']['permalink_url'],
            ]);

            if (is_null($track_author->last_synced_at)) {
                $track_author->update(['status' => User::STATUS_INACTIVE]);
            }
            $commonTrackData = $this->mapCommonTrackData($trackData);

            Track::updateOrCreate(
                [
                    'soundcloud_track_id' => $trackData['id'],
                    'urn' => $trackData['urn']
                ],
                $commonTrackData
            );
            return true;
        } catch (Exception $e) {
            Log::error('Error syncing user tracks in syncUserTracks', [
                'user_urn' => user()->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function unknownPlaylistAdd($playlistData): int
    {
        try {
            dd($playlistData);
            $playlistUserUrn = $playlistData['user']['urn'];
            $track_author = User::updateOrCreate([
                'urn' => $playlistUserUrn,
            ], [
                'soundcloud_id' => $playlistData['user']['id'],
                'name' => $playlistData['user']['username'],
                'nickname' => $playlistData['user']['username'],
                'avatar' => $playlistData['user']['avatar_url'],
                'soundcloud_permalink_url' => $playlistData['user']['permalink_url'],
            ]);
            $playlist = Playlist::updateOrCreate(
                ['soundcloud_id' => $playlistData['id'] ?? null],
                [
                    'user_urn' => $playlistData['user']['urn'] ?? user()->urn,
                    'soundcloud_urn' => $playlistData['urn'] ?? null,
                    'soundcloud_kind' => $playlistData['kind'] ?? null,
                    'title' => $playlistData['title'] ?? null,
                    'duration' => $playlistData['duration'] ?? 0,
                    'description' => $playlistData['description'] ?? null,
                    'permalink' => $playlistData['permalink'] ?? null,
                    'permalink_url' => $playlistData['permalink_url'] ?? null,
                    'sharing' => $playlistData['sharing'] ?? null,
                    'tag_list' => $playlistData['tag_list'] ?? '',
                    'tags' => $playlistData['tag_list'] ?? '',
                    'genre' => $playlistData['genre'] ?? null,
                    'release' => $playlistData['release'] ?? null,
                    'release_day' => $playlistData['release_day'] ?? null,
                    'release_month' => $playlistData['release_month'] ?? null,
                    'release_year' => $playlistData['release_year'] ?? null,
                    'label_name' => $playlistData['label_name'] ?? null,
                    'label' => $playlistData['label'] ?? null,
                    'label_id' => $playlistData['label_id'] ?? null,
                    'track_count' => $playlistData['track_count'] ?? 0,
                    'likes_count' => $playlistData['likes_count'] ?? 0,
                    'streamable' => $playlistData['streamable'] ?? true,
                    'downloadable' => $playlistData['downloadable'] ?? false,
                    'purchase_title' => $playlistData['purchase_title'] ?? null,
                    'purchase_url' => $playlistData['purchase_url'] ?? null,
                    'artwork_url' => $playlistData['artwork_url'] ?? null,
                    'embeddable_by' => $playlistData['embeddable_by'] ?? null,
                    'uri' => $playlistData['uri'] ?? null,
                    'secret_uri' => $playlistData['secret_uri'] ?? null,
                    'secret_token' => $playlistData['secret_token'] ?? null,
                    'tracks_uri' => $playlistData['tracks_uri'] ?? null,
                    'playlist_type' => $playlistData['playlist_type'] ?? null,
                    'type' => $playlistData['type'] ?? null,
                    'soundcloud_created_at' => isset($playlistData['created_at']) ? Carbon::parse($playlistData['created_at'])->toDateTimeString() : null,
                    'last_modified' => isset($playlistData['last_modified']) ? Carbon::parse($playlistData['last_modified'])->toDateTimeString() : null,
                ]
            );
            $playlist_urn = $playlist->soundcloud_urn;
            $tracksData = $playlistData['tracks'];

            foreach ($tracksData as $trackData) {
                $userUrn = $trackData['user']['urn'];
                $track_author = User::updateOrCreate([
                    'urn' => $userUrn,
                ], [
                    'soundcloud_id' => $trackData['user']['id'],
                    'name' => $trackData['user']['username'],
                    'nickname' => $trackData['user']['username'],
                    'avatar' => $trackData['user']['avatar_url'],
                    'soundcloud_permalink_url' => $trackData['user']['permalink_url'],
                ]);

                if (is_null($track_author->last_synced_at)) {
                    $track_author->update(['status' => User::STATUS_INACTIVE]);
                }
                $commonTrackData = $this->mapCommonTrackData($trackData);

                $track = Track::updateOrCreate(
                    [
                        'soundcloud_track_id' => $trackData['id'],
                        'urn' => $trackData['urn']
                    ],
                    $commonTrackData
                );
                if ($playlist_urn && $track->urn) {
                    PlaylistTrack::updateOrCreate([
                        'playlist_urn' => $playlist_urn,
                        'track_urn' => $track->urn,
                    ]);
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error syncing user tracks in syncUserTracks', [
                'user_urn' => user()->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getUserProfile(User $user): array
    {
        return $this->makeApiRequest(
            $user,
            'get',
            "/me",
            [],
            'Failed to fetch profile from SoundCloud API.'
        );
    }

    public function syncUserInformation(User $user, object $soundCloudUser): UserInformation
    {
        try {
            $userData = (array) ($soundCloudUser->user ?? []);

            return UserInformation::updateOrCreate(
                ['user_urn' => $user->urn],
                [
                    'first_name' => $userData['first_name'] ?? null,
                    'last_name' => $userData['last_name'] ?? null,
                    'full_name' => $userData['full_name'] ?? null,
                    'username' => $userData['username'] ?? null,
                    'soundcloud_id' => $soundCloudUser->getId(),
                    'soundcloud_urn' => $userData['urn'] ?? null,
                    'soundcloud_kind' => $userData['kind'] ?? null,
                    'soundcloud_permalink_url' => $userData['permalink_url'] ?? null,
                    'soundcloud_permalink' => $userData['permalink'] ?? null,
                    'soundcloud_uri' => $userData['uri'] ?? null,
                    'soundcloud_created_at' => $userData['created_at'] ?? null,
                    'soundcloud_last_modified' => $userData['last_modified'] ?? null,
                    'description' => $userData['description'] ?? null,
                    'country' => $userData['country'] ?? null,
                    'city' => $userData['city'] ?? null,
                    'track_count' => $userData['track_count'] ?? 0,
                    'public_favorites_count' => $userData['public_favorites_count'] ?? 0,
                    'reposts_count' => $userData['reposts_count'] ?? 0,
                    'followers_count' => $userData['followers_count'] ?? 0,
                    'following_count' => $userData['followings_count'] ?? 0,
                    'plan' => $userData['plan'] ?? 'Free',
                    'myspace_name' => $userData['myspace_name'] ?? null,
                    'discogs_name' => $userData['discogs_name'] ?? null,
                    'website_title' => $userData['website_title'] ?? null,
                    'website' => $userData['website'] ?? null,
                    'online' => $userData['online'] ?? false,
                    'comments_count' => $userData['comments_count'] ?? 0,
                    'like_count' => $userData['likes_count'] ?? 0,
                    'playlist_count' => $userData['playlist_count'] ?? 0,
                    'private_playlist_count' => $userData['private_playlists_count'] ?? 0,
                    'private_tracks_count' => $userData['private_tracks_count'] ?? 0,
                    'primary_email_confirmed' => $userData['primary_email_confirmed'] ?? false,
                    'local' => $userData['locale'] ?? null,
                    'upload_seconds_left' => $userData['upload_seconds_left'] ?? null,
                ]
            );
        } catch (Exception $e) {
            Log::error('Error syncing user information in syncUserInformation', [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getUserPlaylists(User $user, int $limit = 50, int $offset = 0): array
    {
        $this->refreshUserTokenIfNeeded($user);
        return $this->makeApiRequest(
            $user,
            'get',
            "/me/playlists",
            ['query' => ['limit' => min($limit, 200), 'offset' => $offset]],
            'Failed to fetch playlists from SoundCloud API.'
        );
    }

    public function syncUserPlaylists(User $user): int
    {
        try {
            $playlistsData = $this->getUserPlaylists($user); // The limit is removed here, assuming getUserPlaylists handles pagination to get all playlists.
            $syncedCount = 0;
            $playlistIdsInResponse = [];

            foreach ($playlistsData as $playlistData) {
                $playlistIdsInResponse[] = $playlistData['id'];

                $playlist = Playlist::updateOrCreate(
                    ['soundcloud_id' => $playlistData['id'] ?? null],
                    [
                        'user_urn' => $playlistData['user']['urn'] ?? $user->urn,
                        'soundcloud_urn' => $playlistData['urn'] ?? null,
                        'soundcloud_kind' => $playlistData['kind'] ?? null,
                        'title' => $playlistData['title'] ?? null,
                        'duration' => $playlistData['duration'] ?? 0,
                        'description' => $playlistData['description'] ?? null,
                        'permalink' => $playlistData['permalink'] ?? null,
                        'permalink_url' => $playlistData['permalink_url'] ?? null,
                        'sharing' => $playlistData['sharing'] ?? null,
                        'tag_list' => $playlistData['tag_list'] ?? '',
                        'tags' => $playlistData['tag_list'] ?? '',
                        'genre' => $playlistData['genre'] ?? null,
                        'release' => $playlistData['release'] ?? null,
                        'release_day' => $playlistData['release_day'] ?? null,
                        'release_month' => $playlistData['release_month'] ?? null,
                        'release_year' => $playlistData['release_year'] ?? null,
                        'label_name' => $playlistData['label_name'] ?? null,
                        'label' => $playlistData['label'] ?? null,
                        'label_id' => $playlistData['label_id'] ?? null,
                        'track_count' => $playlistData['track_count'] ?? 0,
                        'likes_count' => $playlistData['likes_count'] ?? 0,
                        'streamable' => $playlistData['streamable'] ?? true,
                        'downloadable' => $playlistData['downloadable'] ?? false,
                        'purchase_title' => $playlistData['purchase_title'] ?? null,
                        'purchase_url' => $playlistData['purchase_url'] ?? null,
                        'artwork_url' => $playlistData['artwork_url'] ?? null,
                        'embeddable_by' => $playlistData['embeddable_by'] ?? null,
                        'uri' => $playlistData['uri'] ?? null,
                        'secret_uri' => $playlistData['secret_uri'] ?? null,
                        'secret_token' => $playlistData['secret_token'] ?? null,
                        'tracks_uri' => $playlistData['tracks_uri'] ?? null,
                        'playlist_type' => $playlistData['playlist_type'] ?? null,
                        'type' => $playlistData['type'] ?? null,
                        'soundcloud_created_at' => isset($playlistData['created_at']) ? Carbon::parse($playlistData['created_at'])->toDateTimeString() : null,
                        'last_modified' => isset($playlistData['last_modified']) ? Carbon::parse($playlistData['last_modified'])->toDateTimeString() : null,
                    ]
                );

                if (!empty($playlistData['tracks'])) {
                    $this->syncUserTracks($user, $playlistData['tracks'], $playlist->soundcloud_urn);
                }

                if ($playlist->wasRecentlyCreated) {
                    $syncedCount++;
                }
            }

            // Deletion logic
            $playlistsToDelete = Playlist::where('user_urn', $user->urn)
                ->whereNotIn('soundcloud_id', $playlistIdsInResponse)
                ->pluck('id');

            if ($playlistsToDelete->isNotEmpty()) {
                Playlist::destroy($playlistsToDelete);
                Log::info("Successfully deleted " . count($playlistsToDelete) . " playlists for user {$user->urn} that are no longer present on SoundCloud.");
            }

            Log::info("Successfully synced {$syncedCount} playlists for user {$user->urn}.");
            return $syncedCount;
        } catch (Exception $e) {
            Log::error('Error syncing user playlists in syncUserPlaylists', [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function syncUserProductsAndSubscriptions(User $user, object $soundCloudUser): void
    {
        Subscription::where('user_urn', $user->urn)->delete();

        if (isset($soundCloudUser->user['subscriptions']) && is_array($soundCloudUser->user['subscriptions'])) {
            foreach ($soundCloudUser->user['subscriptions'] as $subscriptionData) {
                $productDetails = $subscriptionData['product'] ?? null;

                if ($productDetails && isset($productDetails['id']) && isset($productDetails['name'])) {
                    $product = Product::updateOrCreate(
                        ['product_id' => $productDetails['id']],
                        ['name' => $productDetails['name']]
                    );

                    Subscription::create([
                        'user_urn' => $user->urn,
                        'product_id' => $product->id,
                    ]);
                } else {
                    Log::warning('SoundCloud subscription found without complete product data. Skipping.', [
                        'soundcloud_id' => $soundCloudUser->getId(),
                        'subscription_data' => $subscriptionData,
                    ]);
                }
            }
        } else {
            Log::info('SoundCloud user has no subscriptions or the data format is unexpected.', [
                'soundcloud_id' => $soundCloudUser->getId(),
                'subscriptions_data_type' => gettype($soundCloudUser->user['subscriptions'] ?? null),
            ]);
        }
    }

    public function calculateCreditsFromFollowers(int $followers): int
    {
        if ($followers < 100) {
            return 1;
        }
        if ($followers < 1000) {
            return floor($followers / 100);
        }
        if ($followers < 10000) {
            return floor($followers / 100);
        }
        return min(floor($followers / 100), 100);
    }


    /**
     * Uploads a new track to SoundCloud.
     *
     * @param User $user The user submitting the track.
     * @param array $trackData The track data, including file uploads.
     * @return array The JSON response from the API.
     * @throws Exception
     */
    public function uploadTrack(User $user, array $trackData): array
    {
        $this->refreshUserTokenIfNeeded($user);
        $user->refresh();

        $httpClient = Http::withHeaders([
            'Authorization' => 'OAuth ' . $user->token,
        ])->attach(
            'track[asset_data]',
            file_get_contents($trackData['asset_data']->getRealPath()),
            $trackData['asset_data']->getClientOriginalName()
        );

        if ($trackData['artwork_data']) {
            $httpClient->attach(
                'track[artwork_data]',
                file_get_contents($trackData['artwork_data']->getRealPath()),
                $trackData['artwork_data']->getClientOriginalName()
            );
        }

        // Highlighted change: Replaced the old requestBody creation
        $requestBody = [];
        foreach ($trackData as $key => $value) {
            // Only include non-file fields and those with a value
            if (!in_array($key, ['asset_data', 'artwork_data']) && !empty($value)) {
                $requestBody["track[{$key}]"] = $value;
            }
        }

        $response = $httpClient->post($this->baseUrl . '/tracks', $requestBody);

        $responseData = $response->json();
        if (!$response->successful() && isset($responseData['id'])) {
            logger()->warning('SoundCloud API returned a non-2xx status code but successfully uploaded the track.', [
                'status_code' => $response->status(),
                'user_urn' => $user->urn,
                'response_body' => $response->body()
            ]);
            return $responseData;
        }

        $response->throw();
        return $responseData;
    }


    public function syncSelfTracks($tracksData, $playlist_urn = null): void
    {
        $user = User::where('urn', user()->urn)->first();
        $this->syncUserTracks($user, $tracksData, $playlist_urn);
    }


    public function syncSelfPlaylists()
    {
        $user = User::where('urn', user()->urn)->first();
        $this->syncUserPlaylists($user);
    }


    public function getAuthUserFollowers(?User $user = null)
    {
        if (!$user) {
            $user = user();
        }
        $response = $this->makeApiRequest(
            $user,
            'get',
            "/me/followers",
            [],
            'Failed to fetch followers from SoundCloud API.'
        );

        return collect($response['collection']);
    }



    // public function getMusicSrc($trackUri)
    // {
    //     if (!isset($trackUri)) {
    //         return null;
    //     }

    //     $params = [
    //         'url'          => urlencode($trackUri),
    //         'color'        => str_replace('#', '%23', '#ff5500'),
    //         'auto_play'    => 'false',
    //         'show_comments' => 'false',
    //         'show_user'    => 'false',
    //         'show_reposts' => 'false',
    //         'show_teaser'  => 'false',
    //         'buying'       => 'false',
    //         'sharing'      => 'false',
    //         'download'     => 'false',
    //         'hide_related' => 'true',
    //         'visual'       => 'false',
    //     ];

    //     return "https://w.soundcloud.com/player/?" . http_build_query($params);
    // }

    // public function getMusicSrc(string $trackUri): ?string
    // {
    //     if (!$trackUri) {
    //         return null;
    //     }

    //     $clientId = config('services.soundcloud.client_id');

    //     // Check if client_id is configured
    //     if (!$clientId) {
    //         Log::error("SoundCloud client_id not configured");
    //         return null;
    //     }

    //     try {
    //         // 1. Resolve the track details
    //         $resolveUrl = "https://api-v2.soundcloud.com/resolve";
    //         $resolveResponse = Http::timeout(30)->get($resolveUrl, [
    //             'url' => $trackUri,
    //             'client_id' => $clientId,
    //         ]);


    //         // Debug: Log the response to see what's happening
    //         Log::info("SoundCloud resolve response", [
    //             'status' => $resolveResponse->status(),
    //             'body' => $resolveResponse->body()
    //         ]);

    //         if ($resolveResponse->failed()) {
    //             Log::error("SoundCloud resolve API failed", [
    //                 'trackUri' => $trackUri,
    //                 'status' => $resolveResponse->status(),
    //                 'response' => $resolveResponse->body()
    //             ]);
    //             return null;
    //         }

    //         $trackData = $resolveResponse->json();

    //         // Check if track data is valid
    //         if (!$trackData || !isset($trackData['id'])) {
    //             Log::error("Invalid track data received", ['trackData' => $trackData]);
    //             return null;
    //         }

    //         // Check if track is streamable
    //         if (!($trackData['streamable'] ?? false)) {
    //             Log::error("Track is not streamable", ['trackId' => $trackData['id']]);
    //             return null;
    //         }

    //         // 2. Get media/transcodings - check different possible structures
    //         $transcodings = null;
    //         if (isset($trackData['media']['transcodings'])) {
    //             $transcodings = $trackData['media']['transcodings'];
    //         } elseif (isset($trackData['transcodings'])) {
    //             $transcodings = $trackData['transcodings'];
    //         }

    //         if (!$transcodings) {
    //             Log::error("No transcodings found", ['trackData' => $trackData]);
    //             return null;
    //         }

    //         // 3. Find progressive stream (prefer mp3, fallback to opus)
    //         $progressive = collect($transcodings)->first(function ($transcoding) {
    //             return ($transcoding['format']['protocol'] ?? '') === 'progressive' &&
    //                 str_contains($transcoding['format']['mime_type'] ?? '', 'mp3');
    //         });

    //         // Fallback to any progressive stream
    //         if (!$progressive) {
    //             $progressive = collect($transcodings)->first(function ($transcoding) {
    //                 return ($transcoding['format']['protocol'] ?? '') === 'progressive';
    //             });
    //         }

    //         if (!$progressive) {
    //             Log::error("No progressive stream found", ['transcodings' => $transcodings]);
    //             return null;
    //         }

    //         // 4. Get the stream URL
    //         $streamUrl = $progressive['url'];
    //         if (!$streamUrl) {
    //             Log::error("No stream URL in progressive transcoding", ['progressive' => $progressive]);
    //             return null;
    //         }

    //         // 5. Get final stream URL with client_id
    //         $streamResponse = Http::timeout(30)->get($streamUrl, [
    //             'client_id' => $clientId,
    //         ]);

    //         if ($streamResponse->failed()) {
    //             Log::error("SoundCloud stream API failed", [
    //                 'url' => $streamUrl,
    //                 'status' => $streamResponse->status(),
    //                 'response' => $streamResponse->body()
    //             ]);
    //             return null;
    //         }

    //         $streamData = $streamResponse->json();
    //         $finalUrl = $streamData['url'] ?? null;

    //         if (!$finalUrl) {
    //             Log::error("No final URL in stream response", ['streamData' => $streamData]);
    //             return null;
    //         }

    //         return $finalUrl;
    //     } catch (\Exception $e) {
    //         Log::error("SoundCloud API exception", [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return null;
    //     }
    // }

    // // Helper method to validate SoundCloud URLs
    // private function isValidSoundCloudUrl(string $url): bool
    // {
    //     return str_starts_with($url, 'https://soundcloud.com/') ||
    //         str_starts_with($url, 'https://m.soundcloud.com/');
    // }

    // // Alternative method using track ID directly (if you have it)
    // public function getMusicSrcById(int $trackId): ?string
    // {
    //     $clientId = config('services.soundcloud.client_id');

    //     if (!$clientId) {
    //         Log::error("SoundCloud client_id not configured");
    //         return null;
    //     }

    //     try {
    //         $trackUrl = "https://api-v2.soundcloud.com/tracks/{$trackId}";
    //         $response = Http::timeout(30)->get($trackUrl, [
    //             'client_id' => $clientId,
    //         ]);

    //         if ($response->failed()) {
    //             return null;
    //         }

    //         $trackData = $response->json();

    //         // Use the same logic as above to get stream URL
    //         // ... (same transcoding logic)

    //         return $this->extractStreamUrl($trackData, $clientId);
    //     } catch (\Exception $e) {
    //         Log::error("SoundCloud API exception: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // private function extractStreamUrl(array $trackData, string $clientId): ?string
    // {
    //     if (!($trackData['streamable'] ?? false)) {
    //         return null;
    //     }

    //     $transcodings = $trackData['media']['transcodings'] ?? $trackData['transcodings'] ?? null;
    //     if (!$transcodings) {
    //         return null;
    //     }

    //     $progressive = collect($transcodings)->first(function ($transcoding) {
    //         return ($transcoding['format']['protocol'] ?? '') === 'progressive';
    //     });

    //     if (!$progressive || !($progressive['url'] ?? null)) {
    //         return null;
    //     }

    //     $streamResponse = Http::timeout(30)->get($progressive['url'], [
    //         'client_id' => $clientId,
    //     ]);

    //     if ($streamResponse->failed()) {
    //         return null;
    //     }

    //     return $streamResponse->json('url');
    // }




    public function getMusicSrc(string $trackUri): ?string
    {
        // $trackUri = "soundcloud:tracks:2166785337";
        // $trackId = str_replace("soundcloud:tracks:", "", $trackUri);
        // $trackUrl = "https://api.soundcloud.com/tracks/" . $trackId;
        // dd($trackUrl);

        if (!$trackUri) {
            return null;
        }

        $clientId = config('services.soundcloud.client_id'); // from config/services.php

        try {
            // 🔹 Handle if input is a track URI like "soundcloud:tracks:2166785337"
            if (str_starts_with($trackUri, 'soundcloud:tracks:')) {
                $trackId = str_replace("soundcloud:tracks:", "", $trackUri);
                $trackUrl = "https://api.soundcloud.com/tracks/" . $trackId;
            } else {
                // Otherwise assume it is a normal SoundCloud track URL
                $trackUrl = $trackUri;
            }

            // 1. Resolve the track
            $resolve = Http::get("https://soundcloud.com/resolve", [
                'url' => $trackUrl,
                'client_id' => $clientId,
            ]);

            if ($resolve->failed()) {
                Log::error("SoundCloud resolve failed", ['track' => $trackUri]);
                return null;
            }

            $trackData = $resolve->json();

            if (!isset($trackData['media']['transcodings'])) {
                return null;
            }

            // 2. Find a progressive mp3 stream
            $progressive = collect($trackData['media']['transcodings'])
                ->firstWhere('format.protocol', 'progressive');

            if (!$progressive) {
                return null;
            }

            // 3. Request the stream endpoint
            $stream = Http::get($progressive['url'], [
                'client_id' => $clientId,
            ]);

            if ($stream->failed()) {
                Log::error("SoundCloud stream fetch failed", ['url' => $progressive['url']]);
                return null;
            }

            return $stream->json('url'); // ✅ direct playable mp3/opus link

        } catch (\Exception $e) {
            Log::error("SoundCloud API exception: " . $e->getMessage());
            return null;
        }
    }
}
