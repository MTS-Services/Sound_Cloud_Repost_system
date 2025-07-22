<?php

namespace App\Services\Admin;

use App\Models\Track;

class TrackService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getTracks($orderBy = 'id', $order = 'asc')
    {
        return Track::orderBy($orderBy, $order)->latest();
    }
    public function getTrack(string $encryptedId)
    {
        $track = Track::where('id', $encryptedId)->first();
        return $track;
    }
}
