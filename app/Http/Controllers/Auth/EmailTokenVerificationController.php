<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmailTokenVerificationController extends Controller
{
    /**
     * Handle the email verification process via a token.
     */
    public function confirmEmail(Request $request): RedirectResponse
    {
        // Validate the incoming request parameters (id and token from URL)
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'token' => ['required', 'string', 'size:60'], // Ensure token size matches Str::random(60)
        ]);

        // Find the user by the provided ID
        $user = User::find($request->id);

        // Check if the user exists and the provided token matches the stored token
        if (!$user || $user->email_token !== $request->token) {
            Log::warning('Email verification failed: Invalid user ID or token', [
                'user_id_attempt' => $request->id,
                'token_attempt' => $request->token,
                'user_stored_token' => $user ? $user->email_token : 'N/A',
            ]);
            // Redirect to login with an error message
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link or token.']);
        }

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('info', 'Your email is already verified.');
        }

        // Check if the token has expired
        if ($user->email_token_expires_at && now()->gt($user->email_token_expires_at)) {
            Log::warning('Email verification failed: Token expired', ['user_id' => $user->id]);
            // Redirect to login with an error message
            return redirect()->route('login')->withErrors(['email' => 'Your email verification link has expired. Please request a new one.']);
        }

        // Mark the user's email as verified
        $user->markEmailAsVerified();
        // Clear the token and its expiry after successful verification
        $user->email_token = null;
        $user->email_token_expires_at = null;
        $user->save();

        // Log in the user if they are not already authenticated
        if (!Auth::check()) {
            Auth::login($user);
        }

        // Clear the token and user ID from the session, as they are no longer needed
        $request->session()->forget('email_verification_token');
        $request->session()->forget('email_verification_user_id');

        // Redirect to dashboard with a success message
        return redirect()->route('user.dashboard')->with('success', 'Your email has been successfully verified!');
    }
}
