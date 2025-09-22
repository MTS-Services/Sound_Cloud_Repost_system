<?php

namespace App\Http\Traits;

use App\Services\SoundCloud\FollowerAnalyzer;
use App\Services\SoundCloud\SoundCloudService;

trait UserModificationTrait
{
    protected FollowerAnalyzer $followerAnalyzer;
    protected SoundCloudService $soundCloudService;
    public function __construct(FollowerAnalyzer $followerAnalyzer, SoundCloudService $soundCloudService)
    {
        $this->followerAnalyzer = $followerAnalyzer;
        $this->soundCloudService = $soundCloudService;
    }


    public function userRepostPrice($user)
    {
        $realFollowers = $this->followerAnalyzer->separateFollowers($this->soundCloudService->getAuthUserFollowers($user));

        $realFollowers = $realFollowers['counts']['real'];
        if ($realFollowers === null) {
            return 1;
        }
        return ceil($realFollowers / 100) ?: 1; // Ensure at least 1 credit
    }

    public function userRealFollowers($user)
    {
        $realFollowers = $this->followerAnalyzer->separateFollowers($this->soundCloudService->getAuthUserFollowers($user));
        return $realFollowers['counts']['real'] ?: 0;
    }
}
