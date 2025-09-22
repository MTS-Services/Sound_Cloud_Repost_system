<?php

namespace App\Http\Traits;

use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;

trait UserModificationTrait
{


    public function userRepostPrice(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService)
    {
        $realFollowers = $followerAnalyzer->separateFollowers($soundCloudService->getAuthUserFollowers($this));

        $realFollowers = $realFollowers['counts']['real'];
        if ($realFollowers === null) {
            return 1;
        }
        return ceil($realFollowers / 100) ?: 1; // Ensure at least 1 credit
    }

    public function userRealFollowers(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService)
    {
        $realFollowers = $followerAnalyzer->separateFollowers($soundCloudService->getAuthUserFollowers($this));
        return $realFollowers['counts']['real'] ?: 0;
    }
}
