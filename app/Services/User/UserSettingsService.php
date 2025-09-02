<?php

namespace App\Services\User;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserSetting;
use App\Models\UserSocialInformation;
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

    public function createOrUpdate($userUrn, array $data)
    {
        return UserSetting::updateOrCreate(
            ['user_urn' => $userUrn],
            $data
        );
    }

    public function saveSettings($userUrn, array $data)
    {
        return UserSetting::updateOrCreate(
            ['user_urn' => $userUrn],
            $data
        );
    }

    public function saveProfile($userUrn, array $profileData)
    {
        DB::transaction(function () use ($userUrn, $profileData) {
            User::where('urn', $userUrn)->update(['email' => $profileData['email']]);
            
            UserGenre::where('user_urn', $userUrn)->delete();
            UserGenre::insert($profileData['genres']);
            
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

    public function deleteAccount($userUrn)
    {
        return User::where('urn', $userUrn)->delete();
    }
}

