<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $data['faq'] = Faq::where()->all();
        return view('backend.user.faq-management.faq',$data);
    }
}
