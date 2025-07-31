<?php

namespace App\Services\Admin\RepostManagement;

use App\Models\Repost;


class RepostTrackingService
{


    public function getReposts($orderBy = 'sort_order', $order = 'asc')
    {
        return Repost::orderBy($orderBy, $order)->with('reposter', 'campaign')->latest();
    }

    public function getRepost(string $encryptedId)
    {
       return Repost::where('id', decrypt($encryptedId))->first();
    }

    
    
}