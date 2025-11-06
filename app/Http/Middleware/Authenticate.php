<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $userId = Auth::id();
            
            // Single query: Update last_seen_at and check genres existence in one go
            $hasGenres = User::where('id', $userId)
                ->whereExists(function ($query) use ($userId) {
                    $query->select(DB::raw(1))
                        ->from('user_genres')
                        ->whereColumn('user_genres.user_urn', 'users.urn')
                        ->whereNull('user_genres.deleted_at');
                })
                ->exists();
            
            // Separate update query (can't combine with exists check)
            User::where('id', $userId)
                ->whereNull('deleted_at')
                ->update([
                    'last_seen_at' => now(),
                    'updated_at' => now()
                ]);
            
            // if ($user->email == null) {
            //     return redirect()->route('user.email.add');
            // }

            if (!$hasGenres) {
                if (!$request->routeIs('user.email.add') && !$request->routeIs('user.email.store') || $request->routeIs('user.dashboard')) {
                    return redirect()->route('user.email.add');
                }
            }
            
            Log::info('User authenticated');
        } else {
            Log::info('User not authenticated');
            return parent::handle($request, $next, ...$guards);
        }

        if (Auth::guard('web')->check() && Auth::user()->banned_at != null) {
            Auth::guard('web')->logout();
            return redirect()->route('f.landing')
                ->with('error', 'Your account has been banned from Repostchain. If you believe this was a mistake, please contact our support team for verification.');
        }

        return $next($request);
    }
}
