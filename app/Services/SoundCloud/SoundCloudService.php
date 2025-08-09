<?php

namespace App\Services\SoundCloud;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Product;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\Subscription;
use App\Models\Track;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception; // Import Exception class

class SoundCloudService
{
    /**
     * The base URL for the SoundCloud API.
     *
     * @var string
     */
    protected string $baseUrl = 'https://api.soundcloud.com';

    private function makeSoundCloudApiRequest(User $user, string $endpoint, array $queryParameters, string $errorMessage): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . $user->token,
            ])->get("{$this->baseUrl}{$endpoint}", $queryParameters);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SoundCloud API Error', [
                'user_urn' => $user->urn,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            throw new Exception("{$errorMessage} Status: " . $response->status());
        } catch (Exception $e) {
            Log::error("SoundCloud API Error in {$endpoint}", [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function refreshAccessToken(User $user): void
    {
        if (!$user->refresh_token) {
            Log::warning('Attempted to refresh token without a refresh token available', [
                'user_urn' => $user->urn,
            ]);
            throw new Exception('No refresh token available for user ' . $user->urn);
        }

        try {
            $response = Http::asForm()->post("{$this->baseUrl}/oauth2/token", [
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

            $user->update([
                'token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? $user->refresh_token, // Refresh token might not change
                'expires_in' => $data['expires_in'],
                'last_synced_at' => now(), // Mark a successful token refresh
            ]);

            Log::info('SoundCloud access token refreshed successfully for user ' . $user->urn);
        } catch (Exception $e) {
            Log::error('Token refresh failed in refreshAccessToken', [
                'user_urn' => $user->urn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function ensureSoundCloudConnection(User $user): void
    {
        if (!$user->isSoundCloudConnected()) {
            throw new Exception('User is not connected to SoundCloud');
        }
    }

    private function refreshUserTokenIfNeeded(User $user): void
    {
        if ($user->needsTokenRefresh()) {
            $this->refreshAccessToken($user);
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

    /* =================================== ===================================
                Start of Sync User SoundCloud Services 
     =================================== =================================== */

    public function getUserTracks(User $user, int $limit = 50, int $offset = 0): array
    {
        $this->ensureSoundCloudConnection($user);
        $this->refreshUserTokenIfNeeded($user);


        $tracksData = $this->makeSoundCloudApiRequest(
            $user,
            "/me/tracks",
            ['limit' => min($limit, 200), 'offset' => $offset],
            'Failed to fetch tracks from SoundCloud API.'
        );

        Log::info('Fetched tracks from SoundCloud API for user ' . $user->urn . 'tracks' . json_encode($tracksData));
        return $tracksData;
    }

    public function syncUserTracks(User $user, int $limit = 200): int
    {
        try {
            $tracksData = $this->getUserTracks($user, $limit);
            $syncedCount = 0;

            foreach ($tracksData as $trackData) {
                // Prepare common track data, setting defaults for potentially missing keys
                $commonTrackData = [
                    'kind' => $trackData['kind'] ?? null,
                    'urn' => $trackData['urn'] ?? null,
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
                    'soundcloud_permalink_url' => $trackData['soundcloud_permalink_url'] ?? null,
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
                ];

                // Add author details if available
                if (isset($trackData['user'])) {
                    $commonTrackData = array_merge($commonTrackData, [
                        'author_username' => $trackData['user']['username'] ?? null,
                        'author_soundcloud_id' => $trackData['user']['id'] ?? null,
                        'author_soundcloud_urn' => $trackData['user']['urn'] ?? null,
                        'author_soundcloud_kind' => $trackData['user']['kind'] ?? null,
                        'author_soundcloud_permalink_url' => $trackData['user']['permalink_url'] ?? null,
                        'author_soundcloud_permalink' => $trackData['user']['permalink'] ?? null,
                        'author_soundcloud_uri' => $trackData['user']['uri'] ?? null,
                    ]);
                }

                $track = Track::updateOrCreate(
                    [
                        'user_urn' => $user->urn,
                        'soundcloud_track_id' => $trackData['id'],
                    ],
                    $commonTrackData
                );

                if ($track->wasRecentlyCreated) {
                    $syncedCount++;
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

    public function getUserProfile(User $user): array
    {
        $this->ensureSoundCloudConnection($user);
        $this->refreshUserTokenIfNeeded($user);

        return $this->makeSoundCloudApiRequest(
            $user,
            "/me",
            [],
            'Failed to fetch profile from SoundCloud API.'
        );
    }

    public function syncUserInformation(User $user, object $soundCloudUser): UserInformation
    {
        try {
            // Ensure $soundCloudUser->user is accessible and is an array or object
            $userData = (array) ($soundCloudUser->user ?? []);

            return UserInformation::updateOrCreate(
                ['user_urn' => $user->urn],
                [
                    'first_name' => $userData['first_name'] ?? null,
                    'last_name' => $userData['last_name'] ?? null,
                    'full_name' => $userData['full_name'] ?? null,
                    'username' => $userData['username'] ?? null,

                    'soundcloud_id' => $soundCloudUser->getId(), // Assuming getId() is available on $soundCloudUser
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
                    'following_count' => $userData['followings_count'] ?? 0, // Typo corrected: followings_count

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
        $this->ensureSoundCloudConnection($user);
        $this->refreshUserTokenIfNeeded($user);

        return $this->makeSoundCloudApiRequest(
            $user,
            "/me/playlists",
            ['limit' => min($limit, 200), 'offset' => $offset],
            'Failed to fetch playlists from SoundCloud API.'
        );
    }

    public function syncUserPlaylists(User $user, int $limit = 200): int
    {
        $playlistsData = $this->getUserPlaylists($user, $limit);
        try {
            $syncedCount = 0;

            foreach ($playlistsData as $playlistData) {
                $playlist = Playlist::updateOrCreate(
                    [
                        'user_urn' => $user->urn,
                        'soundcloud_id' => $playlistData['id'] ?? null, // Use soundcloud_id for unique identification
                    ],
                    [
                        'soundcloud_urn' => $playlistData['urn'] ?? null,
                        'soundcloud_kind' => $playlistData['kind'] ?? null,
                        'title' => $playlistData['title'] ?? null,
                        'duration' => $playlistData['duration'] ?? 0,
                        'description' => $playlistData['description'] ?? null,
                        'permalink' => $playlistData['permalink'] ?? null,
                        'permalink_url' => $playlistData['permalink_url'] ?? null,
                        'sharing' => $playlistData['sharing'] ?? null,
                        'tag_list' => $playlistData['tag_list'] ?? '',
                        'tags' => $playlistData['tag_list'] ?? '', // Redundant if tag_list is the source
                        'genre' => $playlistData['genre'] ?? null,
                        'release' => $playlistData['release'] ?? null,
                        'release_day' => $playlistData['release_day'] ?? null,
                        'release_month' => $playlistData['release_month'] ?? null,
                        'release_year' => $playlistData['release_year'] ?? null,
                        'label_name' => $playlistData['label_name'] ?? null,
                        'label' => $playlistData['label'] ?? null,
                        'label_id' => $playlistData['label_id'] ?? null,
                        'track_count' => $playlistData['track_count'] ?? 0, // Default to 0 instead of null
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

                // --- Uncommented and refined playlist track syncing ---
                if (!empty($playlistData['tracks'])) {
                    foreach ($playlistData['tracks'] as $trackData) {
                        // Prepare common track data (similar to syncUserTracks, but for a playlist context)
                        $commonTrackData = [
                            'kind' => $trackData['kind'] ?? null,
                            'urn' => $trackData['urn'] ?? null,
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
                        ];

                        // Add author details if available for the track
                        if (isset($trackData['user'])) {
                            $commonTrackData = array_merge($commonTrackData, [
                                'author_username' => $trackData['user']['username'] ?? null,
                                'author_soundcloud_id' => $trackData['user']['id'] ?? null,
                                'author_soundcloud_urn' => $trackData['user']['urn'] ?? null,
                                'author_soundcloud_kind' => $trackData['user']['kind'] ?? null,
                                'author_soundcloud_permalink_url' => $trackData['user']['permalink_url'] ?? null,
                                'author_soundcloud_permalink' => $trackData['user']['permalink'] ?? null,
                                'author_soundcloud_uri' => $trackData['uri'] ?? null,
                            ]);
                        }

                        $track = Track::updateOrCreate(
                            [
                                // Use the user_urn from the main user object and the soundcloud_track_id
                                'user_urn' => $user->urn,
                                'soundcloud_track_id' => $trackData['id'],
                            ],
                            $commonTrackData
                        );
                        // Link the track to the playlist
                        // Ensure playlist and track have valid URNs before linking
                        if ($playlist->soundcloud_urn && $track->urn) {
                            PlaylistTrack::updateOrCreate([
                                'playlist_urn' => $playlist->soundcloud_urn,
                                'track_urn' => $track->urn,
                            ]);
                        } else {
                            Log::warning('Skipping PlaylistTrack creation due to missing URNs', [
                                'playlist_id' => $playlist->id,
                                'playlist_urn' => $playlist->soundcloud_urn,
                                'track_id' => $track->id,
                                'track_urn' => $track->urn,
                            ]);
                        }
                    }
                }
                // --- End of uncommented and refined playlist track syncing ---

                if ($playlist->wasRecentlyCreated) {
                    $syncedCount++;
                }
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
        // Delete all existing subscriptions for the user to ensure a fresh sync
        Subscription::where('user_urn', $user->urn)->delete();

        // Check if subscriptions data exists and is iterable
        if (isset($soundCloudUser->user['subscriptions']) && is_array($soundCloudUser->user['subscriptions'])) {
            foreach ($soundCloudUser->user['subscriptions'] as $subscriptionData) {
                $productDetails = $subscriptionData['product'] ?? null;

                if ($productDetails && isset($productDetails['id']) && isset($productDetails['name'])) {
                    // Create or update the product
                    $product = Product::updateOrCreate(
                        ['product_id' => $productDetails['id']],
                        ['name' => $productDetails['name']]
                    );

                    // Create the user's subscription record
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
}