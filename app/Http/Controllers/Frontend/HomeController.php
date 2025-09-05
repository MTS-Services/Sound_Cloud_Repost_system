<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function landing(): View|RedirectResponse
    {
        if (Auth()->guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('frontend.pages.landing-page.landing');
    }
}
