<?php

namespace App\Services\SoundCloud;

use App\Models\User;
use App\Models\UserInformation;
use App\Models\SoundcloudTrack;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SoundCloudServiceCopy2
{
    protected string $baseUrl = 'https://api.soundcloud.com';

    public function getUserTracks(User $user, int $limit = 50, int $offset = 0): array
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
            ])->get("{$this->baseUrl}/me/tracks", [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch tracks: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('SoundCloud API Error', [
                'user_urn' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function syncUserTracks(User $user, int $limit = 200): int
    {
        $tracks = $this->getUserTracks($user, $limit);
        $syncedCount = 0;

        foreach ($tracks as $trackData) {
            SoundcloudTrack::updateOrCreate(
                [
                    'user_urn' => $user->id,
                    'soundcloud_track_id' => $trackData['id'],
                ],
                [
                    'title' => $trackData['title'],
                    'description' => $trackData['description'],
                    'permalink' => $trackData['permalink'] ?? basename($trackData['permalink_url'] ?? ''),
                    'permalink_url' => $trackData['permalink_url'] ?? null,
                    'artwork_url' => $trackData['artwork_url'] ?? null,
                    'duration' => $trackData['duration'],
                    'genre' => $trackData['genre'],
                    'tag_list' => $trackData['tag_list'],
                    'playback_count' => $trackData['playback_count'] ?? 0,
                    'likes_count' => $trackData['likes_count'] ?? 0,
                    'comment_count' => $trackData['comment_count'] ?? 0,
                    'reposts_count' => $trackData['reposts_count'] ?? 0,
                    'track_data' => json_encode($trackData),
                ]
            );
            $syncedCount++;
        }

        return $syncedCount;
    }

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

            throw new \Exception('Failed to fetch profile: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('SoundCloud Profile API Error', [
                'user_urn' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateUserProfile(User $user): User
    {
        $profile = $this->getUserProfile($user);

        $user->update([
            'nickname' => $profile['username'] ?? null,
            'avatar' => $profile['avatar_url'] ?? null,
            'last_synced_at' => now(),
        ]);

        UserInformation::updateOrCreate(
            ['user_urn' => $user->id],
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

        return $user->fresh();
    }

    protected function refreshAccessToken(User $user): void
    {
        if (!$user->refresh_token) {
            throw new \Exception('No refresh token available');
        }

        try {
            $response = Http::asForm()->post("{$this->baseUrl}/oauth2/token", [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.soundcloud.client_id'),
                'client_secret' => config('services.soundcloud.client_secret'),
                'refresh_token' => $user->refresh_token,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to refresh token: ' . $response->body());
            }

            $data = $response->json();

            $user->update([
                'token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? $user->refresh_token,
                'expires_in' => $data['expires_in'],
                'last_synced_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Token refresh failed', [
                'user_urn' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function calculateCreditsFromFollowers(int $followers): int
    {
        if ($followers < 100) return 1;
        if ($followers < 1000) return floor($followers / 100);
        if ($followers < 10000) return floor($followers / 100);
        return min(floor($followers / 100), 100);
    }
}
