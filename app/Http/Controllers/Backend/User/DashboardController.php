<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\Repost;
use App\Models\RepostRequest;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use Illuminate\Database\Eloquent\Model;
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
        $data['totalCount'] = RepostRequest::where('requester_urn', user()->urn)->get()->count();

        $data['repostRequests'] = RepostRequest::where('requester_urn', user()->urn)
            ->with(['track', 'requester'])
            ->latest()
            ->take(2)
            ->get();

        $userId = user()->urn;
        $data['percentageChange'] = $this->creditTransactionService->getWeeklyChangeByUrn($userId);
        //  = $creditStats['percentage_change'];
        return view('backend.user.dashboard', $data);
    }
}
