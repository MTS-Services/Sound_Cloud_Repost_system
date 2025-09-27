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
use Illuminate\Support\Facades\DB;

class SoundCloudService
{


    /* ============================================================================ *
                *** SOUNDCLOUD API REQUESTS AND CONFIGARATIONS ***
     * ============================================================================ */

    /**
     * The base URL for the SoundCloud API.
     *
     * @var string
     */
    protected string $baseUrl = 'https://api.soundcloud.com';
    protected string $oauthUrl = 'https://api.soundcloud.com';
    protected string $resolverUrl = 'https://soundcloud.com/resolve';


    public function makeGetApiRequest(string $endpoint, string $errorMessage, ?array $options = null): array
    {
        $options['linked_partitioning'] = true;
        return $this->makeApiRequestWithPagination(user: user(), method: 'get', endpoint: $endpoint, errorMessage: $errorMessage, options: $options);
    }

    public function makeOtherApiRequest(string $method, string $endpoint, array $options, string $errorMessage): array
    {
        return $this->makeApiRequestWithPagination(user: user(), method: $method, endpoint: $endpoint,  errorMessage: $errorMessage, options: $options);
    }

    protected function makeApiRequestWithPagination(User $user, string $method, string $endpoint, string $errorMessage, array $options,  ?int $maxPages = null): array
    {
        $user->refresh();

        $this->ensureSoundCloudConnection($user);

        $allData = [];
        $nextUrl = "{$this->baseUrl}{$endpoint}";
        $pageCount = 0;

        // Add query parameters to the initial URL if options are provided
        if (!empty($options) && $method === 'get') {
            $queryString = http_build_query($options);
            $nextUrl .= (strpos($nextUrl, '?') === false ? '?' : '&') . $queryString;
        }

        do {
            $pageCount++;

            // Stop if we've reached the maximum pages limit
            if ($maxPages && $pageCount > $maxPages) {
                break;
            }

            try {
                // Parse the URL to separate base URL from query parameters
                $urlParts = parse_url($nextUrl);
                $requestUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];
                $queryParams = [];

                if (isset($urlParts['query'])) {
                    parse_str($urlParts['query'], $queryParams);
                }

                // Make the API request
                $makeUrl = Http::withToken($user->token);
                $response = null;
                if ($method === 'get') {
                    $response = $makeUrl->get($requestUrl, $queryParams);
                } else {
                    $response = $makeUrl->$method($requestUrl, $options);
                }

                if ($response->successful()) {
                    $data = $response->json();

                    // Handle different response structures
                    if (isset($data['collection'])) {
                        // Merge the collection data
                        $allData = array_merge($allData, $data['collection']);

                        // Check for next page
                        $nextUrl = $data['next_href'] ?? null;
                    } elseif (is_array($data)) {
                        // If it's a direct array, merge it
                        $allData = array_merge($allData, $data);
                        $nextUrl = null;
                    } else {
                        // Single item or different structure
                        $allData[] = $data;
                        $nextUrl = null;
                    }

                    // Add a small delay to avoid rate limiting
                    if ($nextUrl) {
                        usleep(100000); // 100ms delay
                    }
                } else {
                    Log::error('SoundCloud API Error', [
                        'user_urn' => $user->urn,
                        'method' => $method,
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                        'next_url' => $nextUrl ?? 'N/A',
                        'page_count' => $pageCount,
                    ]);

                    throw new Exception("{$errorMessage} Status: " . $response->status());
                }
            } catch (Exception $e) {
                Log::error("SoundCloud API Error in {$endpoint}", [
                    'user_urn' => $user->urn,
                    'method' => $method,
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                    'next_url' => $nextUrl ?? 'N/A',
                    'page_count' => $pageCount,
                ]);
                throw $e;
            }
        } while ($nextUrl);

        return [
            'collection' => $allData,
            'total_count' => count($allData),
            'pages_fetched' => $pageCount
        ];
    }

    public function makeResolveApiRequest(string $endpoint, string $errorMessage)
    {
        $this->ensureSoundCloudConnection(user: user());

        try {
            $response = Http::get($this->resolverUrl, [
                'url' => $endpoint,
                'client_id' => config('services.soundcloud.client_id'),
            ]);
            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SoundCloud Resolve API Error', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);

            throw new Exception("{$errorMessage} Status: " . $response->status());
        } catch (Exception $e) {
            Log::error("SoundCloud Resolve API Error in {$endpoint}", [
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
                Log::error('Token refresh failed in SoundCloud Service for user ' . $user->urn, [
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

    /* ============================================================================ *
            *** END OF THE SOUNDCLOUD API REQUESTS AND CONFIGARATIONS ***
     * ============================================================================ */

    /* ++++++++++++++++++++++ ++++++++++++++++++++++ ++++++++++++++++++++++ *
            *** START OF COMMON HELPER METHODS TO FETCH DATA ***
     * ++++++++++++++++++++++ ++++++++++++++++++++++ ++++++++++++++++++++++ */

    public function fetchUserTracks(User $user): array
    {
        $this->refreshUserTokenIfNeeded(user());

        return $this->makeGetApiRequest(
            endpoint: '/users/' . $user->urn . '/tracks',
            errorMessage: 'Failed to fetch user tracks',
            options: [
                'access' => 'playable,preview,blocked'
            ]
        );
    }

    public function fetchUserPlaylists(User $user): array
    {
        $this->refreshUserTokenIfNeeded(user());

        return $this->makeGetApiRequest(
            endpoint: '/users/' . $user->urn . '/playlists',
            errorMessage: 'Failed to fetch user playlists',
            options: [
                'access' => 'playable,preview,blocked',
                'show_tracks' => false,
            ]
        );
    }

    public function fetchUserPlaylistTracks(User $user, string $playlistUrn): array
    {
        $this->refreshUserTokenIfNeeded(user());
        return $this->makeGetApiRequest(
            endpoint: '/playlists/' . $playlistUrn . '/tracks',
            errorMessage: 'Failed to fetch playlist tracks',
            options: [
                'access' => 'playable,preview,blocked',
            ]
        );
    }
    /* ++++++++++++++++++++++ ++++++++++++++++++++++ ++++++++++++++++++++++ *
            *** START OF COMMON HELPER METHODS TO FETCH DATA ***
     * ++++++++++++++++++++++ ++++++++++++++++++++++ ++++++++++++++++++++++ */
    /* ---------------------- ---------------------- ----------------------
     *** START OF THE SOUNDCLOUD SYNC METHODS ***
     ---------------------- ---------------------- ----------------------  */

    //   USER INFORMATION SYNC METHOD
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

    public function syncUserTracks(User $user, array $tracksData, ?string $playlist_urn = null): int
    {
        try {
            if (empty($tracksData)) {
                $response = $this->fetchUserTracks($user);
                $tracksData = $response['collection'];
            }

            $syncedCount = 0;
            $trackIdsInResponse = [];

            foreach ($tracksData as $trackData) {
                // Skip private tracks
                if (($trackData['sharing'] ?? '') === 'private') {
                    continue;
                }
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

    public function syncUserPlaylists(User $user): int
    {
        try {
            $response = $this->fetchUserPlaylists($user);
            $playlistsData = $response['collection'];
            $syncedCount = 0;
            $playlistIdsInResponse = [];

            foreach ($playlistsData as $playlistData) {
                // Skip private playlists
                if (($playlistData['sharing'] ?? '') === 'private') {
                    continue;
                }

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

                $trackResponse = $this->fetchUserPlaylistTracks($user, $playlist->soundcloud_urn);
                $playlistTrackData = $trackResponse['collection'];
                if (!empty($playlistTrackData)) {
                    $this->syncUserTracks($user, $playlistTrackData, $playlist->soundcloud_urn);
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

    public function getMusicSrc(string $trackUri): ?string
    {
        if (!$trackUri) {
            return null;
        }

        $clientId = config('services.soundcloud.client_id');

        try {
            if (str_starts_with($trackUri, 'soundcloud:tracks:')) {
                $trackId = str_replace("soundcloud:tracks:", "", $trackUri);
                $trackUrl = "https://api.soundcloud.com/tracks/" . $trackId;
            } else {
                // Otherwise assume it is a normal SoundCloud track URL
                $trackUrl = $trackUri;
            }

            // 1. Resolve the track
            $response = $this->makeResolveApiRequest(endpoint: $trackUrl, errorMessage: 'Failed to resolve track');

            if (!$response) {
                return null;
            }

            if (!isset($response['media']['transcodings'])) {
                return null;
            }

            // 2. Find a progressive mp3 stream
            $progressive = collect($response['media']['transcodings'])
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
