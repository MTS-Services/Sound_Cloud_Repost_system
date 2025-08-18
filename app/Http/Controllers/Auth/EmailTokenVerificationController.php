<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmailTokenVerificationController extends Controller
{

    public function confirmEmail(Request $request): RedirectResponse
    {

        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'token' => ['required', 'string', 'size:60'],
        ]);

        $user = User::find($request->id);

        if (!$user || $user->email_token !== $request->token) {
            Log::warning('Email verification failed: Invalid user ID or token', [
                'user_id_attempt' => $request->id,
                'token_attempt' => $request->token,
                'user_stored_token' => $user ? $user->email_token : 'N/A',
            ]);
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link or token.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('info', 'Your email is already verified.');
        }


        if ($user->email_token_expires_at && now()->gt($user->email_token_expires_at)) {
            Log::warning('Email verification failed: Token expired', ['user_id' => $user->id]);

            return redirect()->route('login')->withErrors(['email' => 'Your email verification link has expired. Please request a new one.']);
        }


        $user->markEmailAsVerified();

        $user->email_token = null;
        $user->email_token_expires_at = null;
        $user->save();

        if (!Auth::check()) {
            Auth::login($user);
        }
        $notification = CustomNotification::create([
            'receiver_id' => $user->id,
            'receiver_type' => get_class($user),
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => 'Email Verified Successfully!',
                'message' => 'Your email has been successfully verified. Welcome aboard!',
                'description' => 'You can now access your account and start using the platform.',
                'icon' => 'check-circle',
            ],
        ]);

        broadcast(new UserNotificationSent($notification));


        $request->session()->forget('email_verification_token');
        $request->session()->forget('email_verification_user_id');

        return redirect()->route('user.dashboard')->with('success', 'Your email has been successfully verified!');
    }
}
