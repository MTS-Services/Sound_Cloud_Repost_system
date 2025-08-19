<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('f.landing');
        }

        return null;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next, ...$guards): Response
    {
        // Check if a user is authenticated with the 'web' guard
        if (Auth::guard('web')->check() && $request->routeIs('user.*')) {
            $user = Auth::user();

            $user->load('genres');

            // if ($user->email == null) {
            //     return redirect()->route('user.email.add');
            // }

            if ($user->genres()->count() == 0) {
                // return redirect()->route('user.genre.add');
                if (!$request->routeIs('user.email.add') && !$request->routeIs('user.email.store')) {
                    return redirect()->route('user.email.add');
                }
            }

            // Check if the access token is expired.
            // SoundCloud's expires_in is an integer in seconds from the time of issue.
            // You should store a timestamp of when the token was issued.
            // A better approach is to store the expiration timestamp directly.
            // For example, user()->token_expires_at.
            // if ($user->expires_in < time()) {
            //     Log::info('Time Over')
            //     // Token has expired, try to refresh it
            //     if ($user->refresh_token) {
            //         try {
            //             $response = Http::asForm()->post('https://secure.soundcloud.com/oauth/token', [
            //                 'grant_type' => 'refresh_token',
            //                 'client_id' => config('services.soundcloud.client_id'),
            //                 'client_secret' => config('services.soundcloud.client_secret'),
            //                 'refresh_token' => $user->refresh_token,
            //             ]);

            //             if ($response->successful()) {
            //                 $data = $response->json();
            //                 // Update user's token information
            //                 $user->token = $data['token'];
            //                 $user->refresh_token = $data['refresh_token'] ?? $user->refresh_token; // Refresh token can be reused or a new one provided
            //                 $user->expires_in = time() + $data['expires_in'];
            //                 $user->save();
            //             } else {
            //                 // Refresh token failed, log out the user
            //                 Auth::logout();
            //                 return redirect()->route('f.landing')->with('error', 'Session expired, please log in again.');
            //             }
            //         } catch (\Exception $e) {
            //             // Handle exceptions, e.g., network error
            //             Auth::logout();
            //             return redirect()->route('f.landing')->with('error', 'An error occurred, please log in again.');
            //         }
            //     } else {
            //         // No refresh token available, log out the user
            //         Auth::logout();
            //         return redirect()->route('f.landing')->with('error', 'Session expired, please log in again.');
            //     }
            // }
        }

        return parent::handle($request, $next, ...$guards);
    }
}
