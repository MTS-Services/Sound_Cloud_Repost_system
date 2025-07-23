<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
     public function index()
    {
        return view('backend.user.campains');
    }
}
