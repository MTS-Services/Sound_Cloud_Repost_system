<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\User;
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
        if (Auth::guard('web')->check()) {
            // Banned user check
            if (Auth::user()->banned_at != null) {
                Auth::guard('web')->logout();
                return redirect()->route('f.landing')
                    ->with('error', 'Your account has been banned from Repostchain. If you believe this was a mistake, please contact Support.');
            }
            return redirect()->intended(route('user.my-account', absolute: false));
        }
        return view('auth.login');
        // return redirect()->route('soundcloud.redirect');
        // return redirect()->route('f.landing');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(UserLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        // Banned user check
        if (Auth::user()->banned_at != null) {
            Auth::guard('web')->logout();
            return redirect()->route('f.landing')
                ->with('error', 'Your account has been banned from Repostchain. If you believe this was a mistake, please contact Support.');
        }

        $request->session()->regenerate();
        session()->flash('success', 'Login successful!');
        return redirect()->intended(route('user.my-account', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // User::where('id', $request->user()->id)->update(
        //     [
        //         'token' => null,
        //         'refresh_token' => null,
        //         'expires_in' => null,
        //     ]
        // );

        Auth::guard('web')->logout();

        $sessionKey = 'login_web_' . sha1(config('auth.providers.users.model'));
        $request->session()->forget($sessionKey);
        $request->session()->regenerateToken();

        return redirect()->route('f.landing');
    }
}
