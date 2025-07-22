<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;

class AddCaeditsController extends Controller
{
     public function addCredits()
    {
        $data['credits'] =Credit::all();
        return view('backend.user.add-credits',$data);
    }
}
