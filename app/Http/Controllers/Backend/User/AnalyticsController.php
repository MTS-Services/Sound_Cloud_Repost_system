<?php

    namespace App\Http\Controllers\Backend\User;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class AnalyticsController extends Controller
    {
        public function analytics(){
            return view('backend.user.analytics');
        }
    }
