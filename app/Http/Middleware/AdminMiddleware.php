<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Get authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Redirect to verification page if email not verified
        if (!$admin->email_verified_at) {
            Log::info('Admin email not verified', ['admin_id' => $admin->id]);
            session()->flash('warning', 'Your admin account is not verified. Please verify your email.');
            return redirect()->route('admin.verification.notice');
        }

        return $next($request);
    }
}
