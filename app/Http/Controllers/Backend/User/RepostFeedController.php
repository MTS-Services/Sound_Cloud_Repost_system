<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RepostFeedController extends Controller
{
    public function repostFeed(){
        return view('backend.user.repost-feed');
    }
}
