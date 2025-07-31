<?php

namespace App\Services\Admin\RepostManagement;

use App\Models\Repost;


class RepostTrackingService
{


    public function getReposts()
    {
        return Repost::query();
    }

    public function getRepost()
    {
        return Repost::find($id);
    }
}