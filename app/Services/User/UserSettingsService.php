<?php

namespace App\Services\User;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserPlan;
use App\Models\UserSetting;
use App\Models\UserSocialInformation;
use App\Models\CreditTransaction;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
class UserSettingsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getInitialData($user)
    {
        return [
            'availableGenres' => AllGenres(),
            'selectedGenres' => UserGenre::where('user_urn', $user->urn)->pluck('genre')->toArray(),
            'credits' => CreditTransaction::where('receiver_urn', $user->urn)->latest()->get(),
            'payments' => Payment::where('user_urn', $user->urn)->with('order.source')->latest()->get(),
            'activePlan' => optional(UserPlan::where('user_urn', $user->urn)->first()->plan)->name ?? 'Free Forever',
            'settings' => UserSetting::where('user_urn', $user->urn)->first(),
            'socialInfo' => UserSocialInformation::where('user_urn', $user->urn)->first(),
            'email' => User::where('urn', $user->urn)->value('email'),
        ];
    }

    public function saveSettings($user, array $data)
    {
        return UserSetting::updateOrCreate(
            ['user_urn' => $user->urn],
            $data
        );
    }

    public function saveProfile($user, array $profileData)
    {
        DB::transaction(function () use ($user, $profileData) {
            // email update
            User::where('urn', $user->urn)->update(['email' => $profileData['email']]);

            // genres update
            UserGenre::where('user_urn', $user->urn)->delete();
            UserGenre::insert($profileData['genres']);

            // social info update
            $socialData = $profileData['socialData'];
            $socialInfo = UserSocialInformation::self()->first();

            $hasAnySocial = collect($socialData)->except('user_urn')->filter()->isNotEmpty();

            if (!$socialInfo && $hasAnySocial) {
                UserSocialInformation::create($socialData);
            } elseif ($socialInfo && $hasAnySocial) {
                $socialInfo->update($socialData);
            } elseif ($socialInfo && !$hasAnySocial) {
                $socialInfo->delete();
            }
        });
    }

    public function downloadInvoice(Payment $payment)
    {
        return Pdf::loadView('components.user.settings.invoice-pdf', [
            'payment' => $payment
        ]);
    }

    public function deleteAccount($user)
    {
        return User::where('urn', $user->urn)->delete();
    }
}

