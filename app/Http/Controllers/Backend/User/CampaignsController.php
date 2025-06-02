<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
     public function campains()
    {
        return view('backend.user.campains');
    }
}
