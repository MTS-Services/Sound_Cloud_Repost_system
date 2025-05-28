<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('backend.admin.dashboard');
    }
    public function dashboard2nd()
    {
        return view('backend.admin.2nd');
    }
}
