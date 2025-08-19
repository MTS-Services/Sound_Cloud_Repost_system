<?php

namespace App\Http\Controllers;

use App\Events\UserNotificationSent;
use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\EmailVerificationMail;
use App\Models\CreditTransaction;
use App\Models\CustomNotification;
use App\Models\RepostRequest as ModelsRepostRequest;
use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserInformation;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\RepostManagement\RepostRequestService;
use App\Services\Admin\RepostManagement\RepostService;
use App\Services\ProfileService;
use App\Services\TrackService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected TrackService $trackService;
    protected RepostService $repostService;
    protected RepostRequestService $RepostRequestService;
    protected CreditTransactionService $creditTransactionService;
    protected ProfileService $profileService; // Declare the ProfileService property

    public function __construct(
        TrackService $trackService,
        RepostService $repostService,
        RepostRequestService $RepostRequestService,
        CreditTransactionService $creditTransactionService,
        ProfileService $profileService // Inject ProfileService
    ) {
        $this->trackService = $trackService;
        $this->repostService = $repostService;
        $this->RepostRequestService = $RepostRequestService;
        $this->creditTransactionService = $creditTransactionService;
        $this->profileService = $profileService; // Assign the injected service
    }
    public function profile()
    {
        $data['tracks'] = $this->trackService->getTracks()->where('user_urn', user()->urn)->count();
        $data['tracks_today'] = $this->trackService->getTracks()->whereDate('created_at_soundcloud', today())->count();

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
        // Validate incoming request data
        $validated = $request->validate([
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . user()->id],
            'genres' => ['sometimes', 'required', 'array', 'min:5', 'max:5'],
            'genres.*' => ['sometimes', 'required', 'string'],
        ]);

        // Find the user by URN
        $user = User::where('urn', user()->urn)->first();

        $user->fill($validated);

        // Update the genres
        UserGenre::where('user_urn', $user->urn)->delete();
        foreach ($validated['genres'] as $genre) {
            UserGenre::create([
                'user_urn' => $user->urn,
                'genre' => $genre,
                'creater_id' => $user->id,
                'creater_type' => get_class($user)
            ]);
        }

        // Create email verification notification
        $notification = CustomNotification::create([
            'receiver_id' => $user->id,
            'receiver_type' => get_class($user),
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => 'Email Verification Required',
                'message' => 'Please verify your email address to verify your account.',
                'description' => 'Click the link in your inbox to complete the verification process.',
                'icon' => 'fa-solid fa-envelope', // Email verification icon
            ],
        ]);

        broadcast(new UserNotificationSent($notification));

        // Send the verification email
        $this->profileService->sendEmailVerification($user, $request);

        // Flash success message
        session()->flash('success', 'Registration completed successfully! Please check your email to verify your account.');

        // Redirect to dashboard
        return redirect()->route('user.dashboard');
    }


    public function resendEmailVerification(Request $request): RedirectResponse
    {
        // Ensure the user is authenticated
        $user = $request->user(); // Assuming the user is logged in
        if (!$user) {
            session()->flash('error', 'You must be logged in to resend the verification email.');
            return redirect()->route('login');
        }

        // Check if the user's email is already verified
        if ($user->hasVerifiedEmail()) {
            session()->flash('info', 'Your email is already verified!');
            return redirect()->route('user.dashboard');
        }

        // Generate a new token and expiry
        $this->profileService->sendEmailVerification($user, $request);

        // Create email verification resend notification
        $notification = CustomNotification::create([
            'receiver_id' => $user->id,
            'receiver_type' => get_class($user),
            'type' => CustomNotification::TYPE_USER,
            'message_data' => [
                'title' => 'Email Verification Resent',
                'message' => 'A new verification email has been sent to your inbox.',
                'description' => 'Please check your email and verify your account.',
                'icon' => 'fa-solid fa-envelope', // Email verification icon
            ],
        ]);

        broadcast(new UserNotificationSent($notification));

        // Flash success message
        session()->flash('success', 'A new verification email has been sent to your inbox. Please check your email.');

        // Redirect back to the previous page
        return redirect()->back();
    }
}
