<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Campaign;
use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Repost;
use App\Models\RepostRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\UserPlan;


class DashboardController extends Controller
{

    public function dashboard()
    {
        $data['admin'] =            Admin::all()->count();
        $data['active_admin'] =     Admin::where('status', 1)->count();
        $data['user'] =             User::all()->count();
        $data['active_user'] =      User::where('status', 1)->count();
        $data['track'] =            Track::all()->count();
        $data['order'] =            Order::all()->count();
        $data['completed_order'] =    Order::where('status', 'completed')->count();
        $data['active_campaign'] =  Campaign::open()->count();
        $data['completed_campaign'] = Campaign::where('status', 'completed')->count();
        $data['total_payment'] = Payment::where('status', 'success')->sum('amount');
        $data['user_plan'] =        UserPlan::all()->count();
        $data['plan'] =             Plan::all()->count();
        $data['credit'] =           Credit::all()->count();
        $data['report'] =           Repost::all()->count();
        $data['repost_request'] =   RepostRequest::all()->count();
        $data['payments'] =         Payment::all()->count();
        $data['amount'] =           Payment::all()->sum('amount');
        $data['transactions_credit'] = CreditTransaction::all()->sum('credits');

        return view('backend.admin.dashboard', $data);
    }
}
