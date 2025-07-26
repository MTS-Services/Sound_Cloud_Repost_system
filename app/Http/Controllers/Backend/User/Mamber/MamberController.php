<?php

namespace App\Http\Controllers\Backend\User\Mamber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MamberController extends Controller
{
    public function index()
    {
        return view('backend.user.mamber.index');
    }
}
