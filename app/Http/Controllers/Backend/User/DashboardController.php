<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\RepostRequest;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditTransactionService;

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
        $data['totalCount'] = RepostRequest::where('requester_urn', user()->urn)->get()->count();

        $data['repostRequests'] = RepostRequest::where('requester_urn', user()->urn)
            ->with(['track', 'requester'])
            ->latest()
            ->take(2)
            ->get();

        // Available Creadit
        $userId = user()->urn;
        $data['percentageChange'] = $this->creditTransactionService->getWeeklyChangeByCredit($userId);
        $data['campaignChange'] = $this->creditTransactionService->getWeeklyCampaignChange($userId);
       
        return view('backend.user.dashboard', $data);
    }
}
