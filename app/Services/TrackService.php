<?php

namespace App\Services;

use App\Http\Traits\FileManagementTrait;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

class TrackService
{
    use FileManagementTrait;

    public function getTracks($orderBy = 'sort_order', $order = 'asc')
    {
        return Track::orderBy($orderBy, $order)->latest();
    }
    public function getTrack(string $encryptedUrn): Track | Collection
    {
        return Track::findOrFail(decrypt($encryptedUrn));
    }
   
}
