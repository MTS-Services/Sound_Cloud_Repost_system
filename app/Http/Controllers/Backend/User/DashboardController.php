<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
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
        $data['totalCount'] = RepostRequest::whereIn('status', [
            RepostRequest::STATUS_APPROVED,
            RepostRequest::STATUS_PENDING,
            RepostRequest::STATUS_DECLINE
        ])->count();

        $data['repostRequests'] = RepostRequest::where('requester_urn', user()->urn)
            ->with(['track', 'requester'])
            ->latest()
            ->take(2)
            ->get();
        $data['totalCams'] = Campaign::where('user_urn', user()->urn)->orWhere('status', [Campaign::STATUS_COMPLETED, Campaign::STATUS_OPEN])->count();
        // Available Creadit
        $userId = user()->urn;
        $data['creditPercentage'] = $this->creditTransactionService->getWeeklyChangeByCredit($userId);
        // Campaign Percentage
        $data['campaignPercentage'] = $this->creditTransactionService->getWeeklyCampaignChange($userId);

        return view('backend.user.dashboard', $data);
    }
}
