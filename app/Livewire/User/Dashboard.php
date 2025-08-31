<?php

namespace App\Livewire\User;

use App\Livewire\User\RepostRequest as RepostRequestComponent;
use App\Models\Campaign;
use App\Models\RepostRequest;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Dashboard extends Component
{
    protected CreditTransactionService $creditTransactionService;

    public $total_credits;
    public $totalCount;
    public $repostRequests;
    public $totalCams;
    public $creditPercentage;
    public $campaignPercentage;
    public $repostRequestPercentage;

    // Campaign create modal
    public $showCampaignsModal = false;
    public $showSubmitModal = false;
    public $showLowCreditWarningModal = false;
    public $momentumEnabled = false;

    public $tracks = [];
    public $playlists = [];
    public $playlistTracks = [];
    public $activeTab = 'tracks';

    public $track = null;
    public $credit = 50;
    public $commentable = true;
    public $likeable = true;
    public $proFeatureEnabled = false;
    public $proFeatureValue = 0;
    public $maxFollower = 0;
    public $maxRepostLast24h = 0;
    public $maxRepostsPerDay = 0;
    public $anyGenre = 'anyGenre';
    public $trackGenre = '';
    public $targetGenre = '';
    public $user = null;

    public $musicId = null;
    public $musicType = null;
    public $title = null;
    public $description = null;
    public $playlistId = null;

    // Properties for track type filtering
    public $selectedTrackTypes = [];
    public $selectedTrackType = 'all';
    public $genres = [];
    public $showTrackTypes = false;

    // Properties to track budget warnings and validation
    public $showBudgetWarning = false;
    public $budgetWarningMessage = '';
    public $canSubmit = false;


    public function boot(CreditTransactionService $creditTransactionService)
    {
        $this->creditTransactionService = $creditTransactionService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->total_credits = $this->creditTransactionService->getUserTotalCredits();

        $this->totalCount = RepostRequest::where('requester_urn', user()->urn)
            ->orWhere('status', RepostRequest::STATUS_PENDING)
            ->orWhere('status', RepostRequest::STATUS_APPROVED)
            ->orWhere('status', RepostRequest::STATUS_DECLINE)
            ->count();

        $this->repostRequests = RepostRequest::where('target_user_urn', user()->urn)
            ->with(['track', 'requester'])
            ->latest()
            ->take(2)
            ->get();

        $this->totalCams = Campaign::where('user_urn', user()->urn)
            ->orWhere('status', [Campaign::STATUS_COMPLETED, Campaign::STATUS_OPEN])
            ->count();

        // Available Credit
        $userId = user()->urn;
        $this->creditPercentage = $this->creditTransactionService->getWeeklyChangeByCredit($userId);

        // Campaign Percentage
        $this->campaignPercentage = $this->creditTransactionService->getWeeklyCampaignChange($userId);

        // Repost Request Percentage
        $this->repostRequestPercentage = $this->creditTransactionService->getWeeklyRepostRequestChange($userId);
    }

    public function directRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->repost($requestId);

            // Refresh data after successful repost
            $this->loadDashboardData();
            $this->dispatch('alert', 'success', 'Repost request sent successfully.');
        } catch (Throwable $e) {
            Log::error("Error sending repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);

            $this->dispatch('alert', 'error', 'Failed to send repost request. Please try again.');
        }
    }

    public function declineRepost($encryptedRequestId)
    {
        try {
            $requestId = decrypt($encryptedRequestId);

            $component = new RepostRequestComponent();
            $component->declineRepostRequest($requestId);

            // Refresh data after successful decline
            $this->loadDashboardData();

            $this->dispatch('alert', 'success', 'Repost request declined successfully.');
        } catch (Throwable $e) {
            Log::error("Error declining repost request: " . $e->getMessage(), [
                'exception' => $e,
                'encrypted_request_id' => $encryptedRequestId,
                'user_urn' => user()->urn ?? 'N/A',
            ]);
            $this->dispatch('alert', 'error', 'Failed to decline repost request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
