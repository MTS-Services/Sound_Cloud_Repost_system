<?php

namespace App\Services\Admin;

use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

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
    public function getTrack(string $encryptedValue ,  string $field = 'id'): Track | Collection
    {
        return Track::where($field, decrypt($encryptedValue))->first();
    }
}
