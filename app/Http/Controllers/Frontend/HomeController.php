<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function landing(): View
    {
        return view('frontend.pages.landing-page.landing');
    }
}
