<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

            Auth::user()->update(['last_seen_at' => now()]);

            $user->load('genres');

            // if ($user->email == null) {
            //     return redirect()->route('user.email.add');
            // }

            if ($user->genres()->count() == 0) {
                // return redirect()->route('user.genre.add');
                if (!$request->routeIs('user.email.add') && !$request->routeIs('user.email.store') && $request->routeIs('user.dashboard')) {
                    return redirect()->route('user.email.add');
                }
            }
        } else {
            Log::info('User not authenticated');
            return parent::handle($request, $next, ...$guards);
        }
        Log::info('User authenticated');
    }
}
