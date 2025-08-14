<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CreditTransaction;
use App\Models\RepostRequest as ModelsRepostRequest;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\RepostManagement\RepostRequestService;
use App\Services\Admin\RepostManagement\RepostService;
use App\Services\TrackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected TrackService $trackService;
    protected RepostService $repostService;
    protected RepostRequestService $RepostRequestService;
    protected CreditTransactionService $creditTransactionService;
    public function __construct(TrackService $trackService, RepostService $repostService, RepostRequestService $RepostRequestService, CreditTransactionService $creditTransactionService)
    {
        $this->trackService = $trackService;
        $this->repostService = $repostService;
        $this->RepostRequestService = $RepostRequestService;
        $this->creditTransactionService = $creditTransactionService;
    }
    public function profile()
    {
        $data['tracks'] = $this->trackService->getTracks()->where('user_urn', user()->urn)->count();
        $data['gevened_repostRequests'] = $this->RepostRequestService->getRepostRequests()->where('requester_urn', user()->urn)->count();
        $data['received_repostRequests'] = $this->RepostRequestService->getRepostRequests()->where('target_user_urn', user()->urn)->count();
        $data['credit_transactions'] = $this->creditTransactionService->getUserTransactions()->where('receiver_urn', user()->urn)->load('sender');
        $data['total_erned_credits'] = $data['credit_transactions']->where('transaction_type', CreditTransaction::TYPE_EARN)->sum('credits');
        $data['completed_reposts'] = $this->RepostRequestService->getRepostRequests()->where('requester_urn', user()->urn)->where('status', ModelsRepostRequest::STATUS_APPROVED)->count();
        $data['reposted_genres'] = $this->trackService->getTracks()->where('user_urn', user()->urn)->pluck('genre')->unique()->values()->count();
        $data['repost_requests'] = ModelsRepostRequest::with(['track', 'targetUser'])->where('requester_urn', user()->urn)->Where('campaign_id', null)->where('status', ModelsRepostRequest::STATUS_APPROVED)->orderBy('sort_order', 'asc')->take(10)->get();
        $data['user'] = UserInformation::where('user_urn', user()->urn)->first();
        return view('backend.user.profile.profile', $data);
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function emailAdd(): View
    {
        $user = user()->load('userInfo');
        return view('backend.user.profile.email-add', compact('user'));
    }
    public function emailStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'genres' => ['required', 'array', 'min:5', 'max:5'],
            'genres.*' => ['required', 'string'],
        ]);
        $user = User::where('urn', user()->urn)->first();
        $user->update($validated);
        return redirect()->route('user.dashboard');
    }
}
