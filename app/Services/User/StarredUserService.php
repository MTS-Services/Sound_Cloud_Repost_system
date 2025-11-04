<?php

namespace App\Services\User;

use App\Models\StarredUser;

class StarredUserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function toggleStarMark($followerUrn, $starredUserUrn)
    {
        if ($followerUrn == $starredUserUrn) {
            return ['status' => 'invalid'];
        }

        $existingRecord = StarredUser::withTrashed()
            ->where('follower_urn', $followerUrn)
            ->where('starred_user_urn', $starredUserUrn)
            ->first();

        if ($existingRecord) {
            if ($existingRecord->trashed()) {
                $existingRecord->restore();
                return ['status' => 'starred'];
            }
            $existingRecord->delete();
            return ['status' => 'unstarred'];
        }


        StarredUser::create([
            'follower_urn' => $followerUrn,
            'starred_user_urn' => $starredUserUrn,
        ]);
        return ['status' => 'starred'];
    }
}
