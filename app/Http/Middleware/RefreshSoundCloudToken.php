<?php

namespace App\Http\Middleware;

use App\Services\SoundCloud\SoundCloudService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RefreshSoundCloudToken
{
    protected $soundCloudService;

    public function __construct(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only process if user is authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Only process if user has SoundCloud connection
        if (!$user->isSoundCloudConnected()) {
            return $next($request);
        }

        try {
            // Attempt to refresh token if needed
            $this->soundCloudService->refreshUserTokenIfNeeded($user);
            
            // Token is valid, continue with request
            return $next($request);
            
        } catch (Exception $e) {
            // Token refresh failed - disconnect and logout
            Log::warning('SoundCloud token refresh failed in middleware. Logging out user.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Disconnect SoundCloud
            $this->disconnectSoundCloud($user);

            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out due to SoundCloud authentication failure: ' . $user->id);

            // Handle different request types
            return $this->handleFailedAuthentication($request, $e->getMessage());
        }
    }

    /**
     * Disconnect SoundCloud credentials
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    private function disconnectSoundCloud($user): void
    {
        try {
            $user->update([
                'token' => null,
                'refresh_token' => null,
                'expires_in' => null,
                'last_synced_at' => null,
                'urn' => null,
                'soundcloud_username' => null,
                'soundcloud_avatar' => null,
            ]);

            Log::info('SoundCloud disconnected for user ' . $user->id);
        } catch (Exception $e) {
            Log::error('Error during SoundCloud disconnection in middleware', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle failed authentication response
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    private function handleFailedAuthentication(Request $request, string $message)
    {
        // For Livewire requests
        if ($request->header('X-Livewire')) {
            return response()->json([
                'message' => $message,
                'redirect' => route('login')
            ], 401);
        }

        // For AJAX/JSON requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'redirect' => route('login')
            ], 401);
        }

        // For regular web requests
        return redirect()->route('login')
            ->with('error', $message);
    }
}