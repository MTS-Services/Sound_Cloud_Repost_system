<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Livewire\User\MemberManagement\RepostRequest as RepostRequestComponent;
use App\Models\Campaign;
use App\Models\RepostRequest;
use App\Models\User;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $data['totalCount'] = RepostRequest::where('requester_urn', user()->urn)
            ->orWhere('status', RepostRequest::STATUS_PENDING)
            ->orWhere('status', RepostRequest::STATUS_APPROVED)
            ->orWhere('status', RepostRequest::STATUS_DECLINE)
            ->count();

        $data['repostRequests'] = RepostRequest::where('target_user_urn', user()->urn)
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
        // Repost Request Percentage
        $data['repostRequestPercentage'] = $this->creditTransactionService->getWeeklyRepostRequestChange($userId);
        return view('backend.user.dashboard', $data);
    }
    public function directRepost($requestId)
    {
        $requestId = decrypt($requestId);
        try {
            $component = new RepostRequestComponent();
            $component->repost($requestId);
            return redirect()->back()->with('success', 'Repost request sent successfully.');
        } catch (Throwable $e) {
            Log::error("Error sending repost request: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            return redirect()->back()->with('error', 'Failed to send repost request. Please try again.');
        }
    }
    public function declineRepost($requestId)
    {
        $requestId = decrypt($requestId);
        try {
            $component = new RepostRequestComponent();
            $component->declineRepostRequest($requestId);
            // dd($component);
            return redirect()->back()->with('success', 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'request_id' => $requestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            return redirect()->back()->with('error', 'Failed to decline repost request. Please try again.');
        }
    }
}
