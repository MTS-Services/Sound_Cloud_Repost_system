<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\SoundCloud\SoundCloudService;
use Symfony\Component\HttpFoundation\Response;

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
    public function handle(Request $request, Closure $next): Response
    {
        // Log middleware execution for debugging
        Log::info('RefreshSoundCloudToken middleware executed', [
            'url' => $request->url(),
            'route_name' => $request->route() ? $request->route()->getName() : 'unknown',
            'is_authenticated' => Auth::check(),
        ]);

        // Only process if user is authenticated
        if (!Auth::check()) {
            Log::info('User not authenticated, skipping SoundCloud token check');
            return $next($request);
        }

        $user = Auth::user();

        // Log user connection status
        Log::info('Checking SoundCloud connection', [
            'user_id' => $user->id,
            'is_connected' => $user->isSoundCloudConnected(),
            'has_token' => !is_null($user->token),
            'has_refresh_token' => !is_null($user->refresh_token),
        ]);

        // Skip if user is not connected to SoundCloud
        if (!$user->isSoundCloudConnected()) {
            Log::info('User not connected to SoundCloud, skipping token refresh');
            return $next($request);
        }

        try {
            // Attempt to refresh token if needed
            $this->soundCloudService->refreshUserTokenIfNeeded($user);

            Log::info('SoundCloud token check passed, continuing request');

            // Token is valid, continue with request
            return $next($request);
        } catch (Exception $e) {
            // Token refresh failed - disconnect and logout
            Log::error('SoundCloud token refresh failed in middleware', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Disconnect SoundCloud
            $this->disconnectSoundCloud($user);

            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out due to SoundCloud authentication failure', [
                'user_id' => $user->id,
            ]);

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
            ]);

            Log::info('SoundCloud credentials cleared for user', [
                'user_id' => $user->id,
            ]);
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
    private function handleFailedAuthentication(Request $request, string $message): Response
    {
        Log::info('Handling failed authentication', [
            'is_livewire' => $request->header('X-Livewire') ? 'yes' : 'no',
            'expects_json' => $request->expectsJson() ? 'yes' : 'no',
            'message' => $message,
        ]);

        // For Livewire requests - Return HTML with redirect script
        if ($request->header('X-Livewire')) {
            // This will cause Livewire to reload the entire page
            // which will then hit the auth middleware and redirect to login
            return response()->json([
                'effects' => [
                    'html' => '<script>window.location.href = "' . route('f.landing') . '";</script>',
                ],
            ], 401);
        }

        // For AJAX/JSON requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'redirect' => route('f.landing'),
            ], 401);
        }

        // For regular web requests
        return redirect()->route('f.landing')
            ->with('error', $message)
            ->with('soundcloud_error', true);
    }
}
