<?php

namespace App\Services\SoundCloud;

use App\Models\User;
use App\Models\UserInformation;
use App\Models\SoundcloudTrack; // Assuming you have this model for tracks
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SoundCloudService
{
    protected string $baseUrl = 'https://api.soundcloud.com';

    /**
     * Fetches tracks uploaded by the authenticated user from SoundCloud.
     *
     * @param User $user The authenticated user model.
     * @param int $limit The number of tracks to retrieve per request (max 200).
     * @param int $offset The offset for pagination.
     * @return array An array of track data from SoundCloud.
     * @throws \Exception If the user is not connected or API call fails.
     */
    public function getUserTracks(User $user, int $limit = 50, int $offset = 0): array
    {
        if (!$user->isSoundCloudConnected()) {
            throw new \Exception('User is not connected to SoundCloud');
        }

        // Check if token needs refreshing before making API call
        if ($user->needsTokenRefresh()) {
            $this->refreshAccessToken($user);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . $user->token,
            ])->get("{$this->baseUrl}/tracks", [
                'limit' => min($limit, 200), // SoundCloud API limit for /me/tracks is typically 200
                'offset' => $offset,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Log detailed error response body for debugging
            Log::error('SoundCloud API Error - Failed to fetch tracks', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            throw new \Exception('Failed to fetch tracks from SoundCloud API. Status: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('SoundCloud API Error in getUserTracks', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Re-throw the exception after logging
        }
    }

    /**
     * Syncs (creates or updates) a user's tracks from SoundCloud into the database.
     *
     * @param User $user The authenticated user model.
     * @param int $limit The maximum number of tracks to fetch for this sync operation.
     * @return int The number of tracks that were newly created or updated.
     */
    public function syncUserTracks(User $user, int $limit = 200): int
    {
        try {
            $tracks = $this->getUserTracks($user, $limit);
            dd($tracks);
            $syncedCount = 0;

            foreach ($tracks as $trackData) {
                // Determine if a new record was created or an existing one updated
                $track = SoundcloudTrack::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'soundcloud_track_id' => $trackData['id'], // Unique identifier from SoundCloud
                    ],
                    [
                        // Map all relevant fields from the SoundCloud API response to your model's fillable fields
                        'kind' => $trackData['kind'] ?? null,
                        'urn' => $trackData['urn'] ?? null,
                        'duration' => $trackData['duration'] ?? 0,
                        'commentable' => $trackData['commentable'] ?? false,
                        'comment_count' => $trackData['comment_count'] ?? 0,
                        'sharing' => $trackData['sharing'] ?? null,
                        'tag_list' => $trackData['tag_list'] ?? '', // Ensure empty string if null
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
                        'release_day' => $trackData['release_day'] ?? null, // Added based on array output
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
                        'type' => $trackData['type'] ?? null, // Not directly in the provided array, but good to include if it might be
                        'author_username' => $trackData['user']['username'] ?? null,
                        'author_soundcloud_id' => $trackData['user']['id'] ?? null,
                        'author_soundcloud_urn' => $trackData['user']['urn'] ?? null,
                        'author_soundcloud_kind' => $trackData['user']['kind'] ?? null,
                        'author_soundcloud_permalink_url' => $trackData['user']['permalink_url'] ?? null,
                        'author_soundcloud_permalink' => $trackData['user']['permalink'] ?? null,
                        'author_soundcloud_uri' => $trackData['user']['uri'] ?? null,
                        'last_sync_at' => now(), // Record when this track was last synced
                        // Ensure 'is_active' is managed based on your application's logic.
                        // You might set it to true here, or handle it separately.
                        // 'is_active' => true,
                    ]
                );

                if ($track->wasRecentlyCreated) {
                    $syncedCount++;
                }
            }

            Log::info("Successfully synced {$syncedCount} tracks for user {$user->id}.");
            return $syncedCount;
        } catch (\Exception $e) {
            Log::error('Error syncing user tracks in syncUserTracks', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Re-throw the exception after logging
        }
    }

    /**
     * Fetches the user's profile data from SoundCloud.
     *
     * @param User $user The authenticated user model.
     * @return array An array of user profile data from SoundCloud.
     * @throws \Exception If the user is not connected or API call fails.
     */
    public function getUserProfile(User $user): array
    {
        if (!$user->isSoundCloudConnected()) {
            throw new \Exception('User is not connected to SoundCloud');
        }

        if ($user->needsTokenRefresh()) {
            $this->refreshAccessToken($user);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . $user->token,
            ])->get("{$this->baseUrl}/me");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SoundCloud Profile API Error - Failed to fetch profile', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            throw new \Exception('Failed to fetch profile from SoundCloud API. Status: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('SoundCloud Profile API Error in getUserProfile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Updates the user's main profile and UserInformation based on SoundCloud data.
     *
     * @param User $user The authenticated user model.
     * @return User The updated user model.
     */
    public function updateUserProfile(User $user): User
    {
        try {
            $profile = $this->getUserProfile($user);

            // Update User model fields
            $user->update([
                'nickname' => $profile['username'] ?? null,
                'avatar' => $profile['avatar_url'] ?? null,
                'last_synced_at' => now(), // Update general sync timestamp for the user
                'soundcloud_followings_count' => $profile['followings_count'] ?? 0, // Add these here for User model
                'soundcloud_followers_count' => $profile['followers_count'] ?? 0, // Add these here for User model
                // Note: token, refresh_token, expires_in are updated in refreshAccessToken
            ]);

            UserInformation::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $profile['first_name'] ?? null,
                    'last_name' => $profile['last_name'] ?? null,
                    'full_name' => $profile['full_name'] ?? null,
                    'username' => $profile['username'] ?? null,
                    'soundcloud_id' => $profile['id'] ?? null,
                    'soundcloud_urn' => $profile['urn'] ?? null,
                    'soundcloud_kind' => $profile['kind'] ?? null,
                    'soundcloud_permalink_url' => $profile['permalink_url'] ?? null,
                    'soundcloud_permalink' => $profile['permalink'] ?? null,
                    'soundcloud_uri' => $profile['uri'] ?? null,
                    'soundcloud_created_at' => $profile['created_at'] ?? null,
                    'soundcloud_last_modified' => $profile['last_modified'] ?? null,
                    'description' => $profile['description'] ?? null,
                    'country' => $profile['country'] ?? null,
                    'city' => $profile['city'] ?? null,
                    'track_count' => $profile['track_count'] ?? 0,
                    'followers_count' => $profile['followers_count'] ?? 0,
                    'following_count' => $profile['followings_count'] ?? 0,
                    'plan' => $profile['plan'] ?? 'Free',
                    'online' => $profile['online'] ?? false,
                    'comments_count' => $profile['comments_count'] ?? 0,
                    'like_count' => $profile['likes_count'] ?? 0,
                    'playlist_count' => $profile['playlist_count'] ?? 0,
                    'private_playlist_count' => $profile['private_playlists_count'] ?? 0,
                    'private_tracks_count' => $profile['private_tracks_count'] ?? 0,
                    'primary_email_confirmed' => $profile['primary_email_confirmed'] ?? false,
                    'local' => $profile['locale'] ?? null,
                    'upload_seconds_left' => $profile['upload_seconds_left'] ?? null,
                ]
            );

            return $user->fresh(); // Return the updated user instance
        } catch (\Exception $e) {
            Log::error('Error updating user profile in updateUserProfile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Refreshes the user's SoundCloud access token using their refresh token.
     *
     * @param User $user The user model whose token needs refreshing.
     * @throws \Exception If no refresh token is available or token refresh fails.
     */
    protected function refreshAccessToken(User $user): void
    {
        if (!$user->refresh_token) {
            Log::warning('Attempted to refresh token without a refresh token available', [
                'user_id' => $user->id,
            ]);
            throw new \Exception('No refresh token available for user ' . $user->id);
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
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                throw new \Exception('Failed to refresh token: ' . $response->body());
            }

            $data = $response->json();

            // Update the user's token information
            $user->update([
                'token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? $user->refresh_token, // Refresh token might not change
                'expires_in' => $data['expires_in'],
                'last_synced_at' => now(), // Also update this to mark a successful token refresh
            ]);

            Log::info('SoundCloud access token refreshed successfully for user ' . $user->id);
        } catch (\Exception $e) {
            Log::error('Token refresh failed in refreshAccessToken', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculates credits based on followers count.
     * This logic seems independent of SoundCloud API calls.
     *
     * @param int $followers The number of followers.
     * @return int The calculated credits.
     */
    public function calculateCreditsFromFollowers(int $followers): int
    {
        if ($followers < 100) return 1;
        if ($followers < 1000) return floor($followers / 100);
        if ($followers < 10000) return floor($followers / 100);
        return min(floor($followers / 100), 100);
    }
}
