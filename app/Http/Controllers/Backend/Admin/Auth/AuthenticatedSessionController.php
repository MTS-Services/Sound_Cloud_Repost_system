<?php

namespace App\Http\Controllers\Backend\Admin\Auth;

use App\Events\AdminNotificationSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Models\CustomNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }
        return view('frontend.auth.admin.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $admin = Auth::guard('admin')->user();
        if ($admin->last_synced_at === null) {
            $notification = CustomNotification::create([
                'receiver_id' => $admin->id,
                'receiver_type' => get_class($admin),
                'type' => CustomNotification::TYPE_ADMIN,
                'message_data' => [
                    'title' => 'Welcome to ' . config('app.name'),
                    'message' => 'Your dashboard is ready. Let\'s get started!',
                    'description' => 'Explore your admin tools and monitor site activity.',
                    'icon' => 'cog',
                    'additional_data' => [
                        'link_text' => 'Go to Dashboard',
                        'link_url' => route('admin.dashboard'),
                    ]
                ],
            ]);
            broadcast(new AdminNotificationSent($notification));
        }

        $admin->update(['last_synced_at' => now()]);

        return redirect()->intended(route('admin.dashboard', absolute: false))->with('success', 'Logged in successfully');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }
}
