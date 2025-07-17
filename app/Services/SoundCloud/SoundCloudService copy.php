<?php

namespace App\Services\SoundCloud;

use App\Models\SoundCloudProfile;
use App\Models\User;
use App\Models\SoundcloudTrack;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SoundCloudServiceCopy
{
    protected string $baseUrl = 'https://api.soundcloud.com';

    public function getUserTracks(User $user, int $limit = 50, int $offset = 0): array
    {
        if (!$user->isSoundCloudConnected()) {
            throw new \Exception('User is not connected to SoundCloud');
        }

        // Check if token needs refresh
        if ($user->needsTokenRefresh()) {
            $this->refreshAccessToken($user);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . $user->soundcloud_access_token,
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
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    public function syncUserTracks(User $user): int
    {
        $tracks = $this->getUserTracks($user, 200); // Get up to 200 tracks
        $syncedCount = 0;

        foreach ($tracks as $trackData) {
            $track = SoundcloudTrack::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'soundcloud_track_id' => $trackData['id'],
                ],
                [
                    'title' => $trackData['title'],
                    'description' => $trackData['description'],
                    'permalink' => $trackData['permalink'],
                    'permalink_url' => $trackData['permalink_url'],
                    'artwork_url' => $trackData['artwork_url'],
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

            if ($track->wasRecentlyCreated) {
                $syncedCount++;
            }
        }

        return $syncedCount;
    }

    public function getUserProfile(User $user): array
    {
        
        if (!$user->isSoundCloudConnected()) {
            throw new \Exception('User is not connected to SoundCloud');
        }

        // Check if token needs refresh
        if ($user->needsTokenRefresh()) {
            $this->refreshAccessToken($user);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'OAuth ' . $user->soundcloud_access_token,
            ])->get("{$this->baseUrl}/me");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch profile: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('SoundCloud Profile API Error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function updateUserProfile(User $user): User
    {
        $profile = $this->getUserProfile($user);

        $user->update([
            'soundcloud_username' => $profile['username'],
            'soundcloud_avatar' => $profile['avatar_url'],
            'soundcloud_followers_count' => $profile['followers_count'] ?? 0,
            'soundcloud_followings_count' => $profile['followings_count'] ?? 0,
            'soundcloud_tracks_count' => $profile['tracks_count'] ?? 0,
            'last_sync_at' => now(),
        ]);
        SoundCloudProfile::where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
            'soundcloud_url' => $profile['permalink_url'],
            'description' => $profile['description'],
            'country' => $profile['country'],
            'city' => $profile['city'],
            'genres' => $profile['genres'],
            'is_pro' => $profile['is_pro'],
            'is_verified' => $profile['is_verified'],
        ]);

        return $user->fresh();
    }

    protected function refreshAccessToken(User $user): void
    {
        if (!$user->soundcloud_refresh_token) {
            throw new \Exception('No refresh token available');
        }

        try {
            $response = Http::post("{$this->baseUrl}/oauth2/token", [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.soundcloud.client_id'),
                'client_secret' => config('services.soundcloud.client_secret'),
                'refresh_token' => $user->soundcloud_refresh_token,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $user->update([
                    'soundcloud_access_token' => $data['access_token'],
                    'soundcloud_refresh_token' => $data['refresh_token'] ?? $user->soundcloud_refresh_token,
                    'soundcloud_token_expires_at' => Carbon::now()->addSeconds($data['expires_in']),
                ]);
            } else {
                throw new \Exception('Failed to refresh token: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Token refresh failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function calculateCreditsFromFollowers(int $followers): int
    {
        // Credit calculation based on follower count
        if ($followers < 100) {
            return 1;
        } elseif ($followers < 1000) {
            return floor($followers / 100);
        } elseif ($followers < 10000) {
            return floor($followers / 100);
        } else {
            return min(floor($followers / 100), 100); // Cap at 100 credits per repost
        }
    }
}
