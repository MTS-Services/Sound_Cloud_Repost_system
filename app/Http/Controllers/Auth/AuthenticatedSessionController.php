<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\UserLoginRequest;

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
                Log::info("Authenticate session controller-1", ['name' => $name, 'ban_reason' => $ban_reason]);
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
            Log::info("Authenticate session controller-2", ['name' => $name, 'ban_reason' => $ban_reason]);
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
