<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected CreditTransactionService $creditTransactionService;

    public function __construct(CreditTransactionService $creditTransactionService)
    {
        $this->creditTransactionService = $creditTransactionService;
    }
    public function dashboard()
    {
        $data['total_credits'] = $this->creditTransactionService->getUserTotalCredits();
        return view('backend.user.dashboard', $data);
    }
}
