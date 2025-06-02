<?php

namespace App\Http\Controllers\SouncCloud\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoundCloud\SoundCloudAuthRequest;
use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SoundCloudController extends Controller
{
    public function __construct(protected SoundCloudService $soundCloudService)
    {
    }

    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('soundcloud')->redirect();
        } catch (\Exception $e) {
            Log::error('SoundCloud redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Unable to connect to SoundCloud. Please try again.');
        }
    }

    public function callback(SoundCloudAuthRequest $request): RedirectResponse
    {
        // Check for error from SoundCloud
        if ($request->has('error')) {
            return redirect()->route('login')
                ->with('error', 'SoundCloud authentication was cancelled or failed.');
        }

        try {
            $soundCloudUser = Socialite::driver('soundcloud')->user();

            // Find or create user
            $user = $this->findOrCreateUser($soundCloudUser);

            // Sync user tracks
            $this->soundCloudService->syncUserTracks($user);

            // Update user profile data
            $this->soundCloudService->updateUserProfile($user);

            // Login user
            Auth::guard('web')->login($user, true);

            return redirect()->route('dashboard')
                ->with('success', 'Successfully connected to SoundCloud!');

        } catch (\Exception $e) {
            Log::error('SoundCloud callback error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::guard('web')->id(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with SoundCloud. Please try again.');
        }
    }

    public function disconnect(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        try {
            // Clear SoundCloud data
            $user->update([
                'soundcloud_id' => null,
                'soundcloud_username' => null,
                'soundcloud_avatar' => null,
                'soundcloud_followings_count' => 0,
                'soundcloud_followers_count' => 0,
                'soundcloud_access_token' => null,
                'soundcloud_refresh_token' => null,
                'soundcloud_token_expires_at' => null,
            ]);

            // Optionally, deactivate all tracks
            $user->soundcloudTracks()->update(['is_active' => false]);

            return redirect()->route('profile')
                ->with('success', 'Successfully disconnected from SoundCloud.');

        } catch (\Exception $e) {
            Log::error('SoundCloud disconnect error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'Failed to disconnect from SoundCloud.');
        }
    }

    public function sync(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user || !$user->isSoundCloudConnected()) {
            return redirect()->route('profile')
                ->with('error', 'Please connect to SoundCloud first.');
        }
    
        try {
            $syncedCount = $this->soundCloudService->syncUserTracks($user);
            $this->soundCloudService->updateUserProfile($user);

            return redirect()->route('user.dashboard')
                ->with('success', "Successfully synced {$syncedCount} new tracks from SoundCloud.");

        } catch (\Exception $e) {
            Log::error('SoundCloud sync error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Failed to sync tracks from SoundCloud.');
        }
    }

    protected function findOrCreateUser($soundCloudUser): User
    {
        // First, try to find user by SoundCloud ID
        $user = User::where('soundcloud_id', $soundCloudUser->getId())->first();

        if ($user) {
            // Update existing user's SoundCloud data
            $user->update([
                'soundcloud_access_token' => $soundCloudUser->token,
                'soundcloud_refresh_token' => $soundCloudUser->refreshToken,
                'soundcloud_token_expires_at' => now()->addHour(), // SoundCloud tokens typically expire in 1 hour
                'soundcloud_username' => $soundCloudUser->getNickname(),
                'soundcloud_avatar' => $soundCloudUser->getAvatar(),
                'soundcloud_followers_count' => $soundCloudUser->user['followers_count'] ?? 0,
                'soundcloud_followings_count' => $soundCloudUser->user['followings_count'] ?? 0,
                'soundcloud_tracks_count' => $soundCloudUser->user['tracks_count'] ?? 0,
                'last_sync_at' => now(),

            ]);

            return $user;
        }

        // If authenticated user exists, link SoundCloud account
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $user->update([       
                'soundcloud_id' => $soundCloudUser->getId(),
                'soundcloud_username' => $soundCloudUser->getNickname(),  
                'soundcloud_avatar' => $soundCloudUser->getAvatar(),
                'soundcloud_followers_count' => $soundCloudUser->user['followers_count'] ?? 0,
                'soundcloud_followings_count' => $soundCloudUser->user['followings_count'] ?? 0,
                'soundcloud_tracks_count' => $soundCloudUser->user['tracks_count'] ?? 0,
                'soundcloud_access_token' => $soundCloudUser->token,
                'soundcloud_refresh_token' => $soundCloudUser->refreshToken,
                'soundcloud_token_expires_at' => now()->addHour(),
                'last_sync_at' => now(),
            ]);

            return $user;
        }

        // Create new user
        return User::create([
            'name' => $soundCloudUser->getName() ?: $soundCloudUser->getNickname(),
            'email' => $soundCloudUser->getEmail(),
            'soundcloud_id' => $soundCloudUser->getId(),
            'soundcloud_username' => $soundCloudUser->getNickname(),
            'soundcloud_avatar' => $soundCloudUser->getAvatar(),
            'soundcloud_followers_count' => $soundCloudUser->user['followers_count'] ?? 0,
            'soundcloud_followings_count' => $soundCloudUser->user['followings_count'] ?? 0,
            'soundcloud_tracks_count' => $soundCloudUser->user['tracks_count'] ?? 0,
            'soundcloud_access_token' => $soundCloudUser->token,
            'soundcloud_refresh_token' => $soundCloudUser->refreshToken,
            'soundcloud_token_expires_at' => now()->addHour(),
            'last_sync_at' => now(),
            'credits' => 0,
        ]);
    }
}
