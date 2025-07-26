<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Services\Admin\PackageManagement\CreditService;
use Illuminate\Http\Request;

class AddCaeditsController extends Controller
{
    protected CreditService $creditService;

    public function __construct(CreditService $creditService)
    {
        $this->creditService = $creditService;
    }
     public function addCredits()
    {
        $data['credits'] = $this->creditService->getCredits()->active()->get();
        return view('backend.user.add-credits',$data);
    }
}
