<?php

namespace App\Services\Admin\RepostManagement;

use App\Models\Repost;


class RepostService
{


    public function getReposts($orderBy = 'sort_order', $order = 'asc')
    {
        return Repost::orderBy($orderBy, $order)->latest();
    }

    public function getRepost(string $encryptedId , string $encryptedValue = 'id')
    {
       return Repost::where($encryptedValue, decrypt($encryptedId))->first();
    }

    
    
}