<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;

class PromoteController extends Controller
{
    public function tracks(){

        $data['tracks']=Track::where('user_urn', user()->urn)->orderBy('sort_order','asc')->get();
        return view('backend.user.promote',$data);
    }



}
