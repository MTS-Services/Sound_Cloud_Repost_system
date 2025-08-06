<?php

namespace App\Services\Admin\RepostManagement;


use App\Models\RepostRequest;

class RepostRequestService
{


    public function getRepostRequests($orderBy = 'sort_order', $order = 'asc')
    {
        return RepostRequest::orderBy($orderBy, $order)->latest();
    }

    public function getRepostRequest(string $encryptedId , string $encryptedValue = 'id')
    {
       return RepostRequest::where($encryptedValue, decrypt($encryptedId))->first();
    }

    
    
}