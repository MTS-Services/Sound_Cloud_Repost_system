<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoteController extends Controller
{
    public function tracks(){
        return view('backend.user.promote');
    }
}
