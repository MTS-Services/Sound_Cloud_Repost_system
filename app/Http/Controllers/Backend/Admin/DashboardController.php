<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $data['user'] = User::all()->count();
        $data['track'] = Track::all()->count();
        $data['order'] = Order::all()->count();
        $data['active_campaign'] = Campaign::open()->count();
        $data['total_payment'] = Payment::all()->sum('amount');

        return view('backend.admin.dashboard', $data);
    }
}
