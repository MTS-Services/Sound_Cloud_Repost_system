<?php

namespace App\Services;


use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProfileService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

        public function sendEmailVerification(User $user, Request $request): void
    {
        $token = Str::random(60);
        $user->email_token = $token;
        
        $user->email_token_expires_at = now()->addMinutes(config('auth.verification.expire', 60));
        $user->save();

        Mail::to($user->email)->send(new EmailVerificationMail($user, $token));

        $request->session()->put('email_verification_token', $token);
        $request->session()->put('email_verification_user_id', $user->id);
    }
}
