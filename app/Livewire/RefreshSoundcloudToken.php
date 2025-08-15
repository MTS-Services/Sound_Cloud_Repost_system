<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RefreshSoundcloudToken extends Component
{
    public $message = '';

    public function refreshSoundcloudToken()
    {
        $user = Auth::user();

        if (!$user || is_null($user->refresh_token)) {
            $this->message = 'You are not connected to SoundCloud or a refresh token is missing.';
            return;
        }

        try {
            $response = Http::asForm()->post('https://secure.soundcloud.com/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.soundcloud.client_id'),
                'client_secret' => config('services.soundcloud.client_secret'),
                'refresh_token' => $user->refresh_token,
            ]);

            // Check if the refresh request was successful
            if ($response->successful()) {
                $data = $response->json();

                // Get the new token and expiration time
                $newAccessToken = $data['access_token'];
                $newRefreshToken = $data['refresh_token']; // Note: The refresh token can also be new
                $expiresIn = $data['expires_in'];

                // Update the user's tokens in the database
                $user->update([
                    'token' => $newAccessToken,
                    'refresh_token' => $newRefreshToken,
                    'expires_in' => $expiresIn, // You're storing 'expires_in' as seconds
                    'last_synced_at' => now(), // It's good to also update this timestamp
                ]);

                $this->message = 'SoundCloud token refreshed successfully!';

            } else {
                // Log the error for debugging
                \Log::error('SoundCloud token refresh failed.', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $this->message = 'Failed to refresh token. Please re-authenticate with SoundCloud.';
            }

        } catch (\Exception $e) {
            \Log::error('SoundCloud refresh exception.', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            $this->message = 'An error occurred while refreshing the token.';
        }
    }

    public function render()
    {
        return view('livewire.refresh-soundcloud-token');
    }
}