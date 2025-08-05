<?php

namespace App\Services;

use App\Http\Traits\FileManagementTrait;
use App\Models\Track;

class TrackService
{
    use FileManagementTrait;

   public function getTracks($orderBy = 'id', $order = 'asc')
    {
        return Track::orderBy($orderBy, $order)->latest();
    }
    public function getTrack(string $encryptedValue ,  string $field = 'id'): Track 
    {
        return Track::where($field, decrypt($encryptedValue))->first();
    }
   
}
