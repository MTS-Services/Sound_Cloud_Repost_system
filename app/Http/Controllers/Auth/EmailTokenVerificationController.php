<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\User;
use App\Services\User\UserSettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailTokenVerificationController extends Controller
{
    protected UserSettingsService $userSettingsService;
    public function __construct(UserSettingsService $userSettingsService)
    {
        $this->userSettingsService = $userSettingsService;
    }

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
        DB::transaction(function () use ($user) {
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

            $keys = [
                'em_new_repost',
                'em_repost_accepted',
                'em_repost_declined',
                'em_repost_expired',
                'em_campaign_summary',
                'em_free_boost',
                'em_feedback_campaign',
                'em_feedback_rated',
                'em_referrals',
                'em_reputation',
                'em_inactivity_warn',
                'em_marketing',
                'em_chart_entry',
                'em_mystery_box',
                'em_discussions',
                'em_competitions',

                'accept_repost',
            ];

            $data = array_merge(
                ['user_urn' => $user->urn],
                array_fill_keys($keys, 1)
            );

            $this->userSettingsService->createOrUpdate($user->urn, $data);
            $loging_bonus = ApplicationSetting::where('key', 'login_bonus')->value('value');
            if($loging_bonus > 0){
                CreditTransaction::create([
                    'receiver_urn' => $user->urn,
                    'credits' => $loging_bonus,
                    'type' => CreditTransaction::TYPE_BONUS,
                    'calculation_type' => CreditTransaction::CALCULATION_TYPE_DEBIT,
                    'status' => CreditTransaction::STATUS_SUCCEEDED,
                    'transaction_type' => CreditTransaction::TYPE_BONUS,
                    'source_id' => user()->id,
                    'source_type' => get_class(user()),
                    'description' => 'First login bonus',
                ]);
            }

        });



        $request->session()->forget('email_verification_token');
        $request->session()->forget('email_verification_user_id');

        return redirect()->route('user.dashboard')->with('success', 'Your email has been successfully verified!');
    }
}
