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
            if (Auth::user()->banned_at != null) {
                $name = Auth::user()->name;
                $ban_reason = Auth::user()->ban_reason;
                Auth::guard('web')->logout();
                return redirect()->route('f.landing')
                    ->with('showBannedModal', true)
                    ->with('ban_reason', $ban_reason)
                    ->with('name', $name);
            }
            return redirect()->intended(route('user.my-account', absolute: false));
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(UserLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (Auth::user()->banned_at != null) {
            $name = Auth::user()->name;
            $ban_reason = Auth::user()->ban_reason;
            Auth::guard('web')->logout();
            return redirect()->route('f.landing')
                ->with('showBannedModal', true)
                ->with('ban_reason', $ban_reason)
                ->with('name', $name);
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
