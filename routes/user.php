<?php

use App\Http\Controllers\Backend\User\Faq\FaqController;
use App\Http\Controllers\Backend\User\DashboardController;
use App\Http\Controllers\Backend\Admin\OrderManagement\OrderController as UserOrderController;
use App\Http\Controllers\Backend\User\AddCaeditsController;
use App\Http\Controllers\Backend\User\AnalyticsController;
use App\Http\Controllers\Backend\User\FaqManagement\FaqController as FaqManagementFaqController;
use App\Livewire\User\CampaignManagement\Campaign;
use App\Livewire\User\CampaignManagement\MyCampaign;
use App\Http\Controllers\Backend\User\Members\MemberController;
use App\Http\Controllers\Backend\User\PromoteController;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\User\FaqManagement\Faq;
use App\Livewire\User\HelpAndSupport;
use App\Livewire\User\MemberManagement\Member;
use App\Livewire\User\MemberManagement\RepostRequest;
use App\Livewire\User\Notification\NotificationList;
use App\Livewire\User\Notification\NotificationShow;
use App\Livewire\User\PackageManagement\Pricing;
use App\Livewire\User\ProfileManagement\MyAccount;
use App\Livewire\User\TrackSubmit;
use App\Models\Faq as ModelsFaq;
use Illuminate\Validation\Rules\Unique;
use PHPUnit\TextUI\Help;


// SoundCloud Routes
Route::prefix('auth/soundcloud')->name('soundcloud.')->group(function () {
    Route::get('redirect', [SoundCloudController::class, 'redirect'])->name('redirect');
    Route::get('callback', [SoundCloudController::class, 'callback'])->name('callback');
    Route::post('disconnect', [SoundCloudController::class, 'disconnect'])->name('disconnect')->middleware('auth:web');
    Route::post('sync', [SoundCloudController::class, 'sync'])->name('sync')->middleware('auth:web');
});

// User routes (The 'verified' middleware has been removed)
Route::group(['middleware' => ['auth:web'], 'as' => 'user.', 'prefix' => 'user'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile-info', [ProfileController::class, 'emailAdd'])->name('email.add');
    Route::post('/profile-info/update', [ProfileController::class, 'emailStore'])->name('email.store');
    Route::get('/user-profile/{user_urn}', [ProfileController::class, 'profile'])->name('profile');
    Route::post('email/resend-verification', [ProfileController::class, 'resendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('email.resend.verification');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');
    Route::get('/add-credits', [AddCaeditsController::class, 'addCredits'])->name('add-credits');
    Route::post('/buy-credits', [AddCaeditsController::class, 'buyCredits'])->name('buy-credits');
    Route::get('/promote', [PromoteController::class, 'tracks'])->name('promote');

    // Campaign Management
    Route::group(['as' => 'cm.', 'prefix' => 'campaign-management'], function () {
        Route::get('/my-campaigns', MyCampaign::class)->name('my-campaigns');
        Route::get('/campaigns', Campaign::class)->name('campaigns');
    });
    Route::group(['as' => 'pkm.', 'prefix' => 'package-management'], function () {
        Route::get('/pricing', Pricing::class)->name('pricing');
    });

    // Member Management
    Route::group(['as' => 'mm.', 'prefix' => 'member-management'], function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/request', [MemberController::class, 'request'])->name('members.request');
        Route::post('/confirm/repost/{id}', [MemberController::class, 'confirmRepost'])->name('repost.confirm');
    });
    Route::get('members', Member::class)->name('members');
    Route::get('reposts-request', RepostRequest::class)->name('reposts-request');

    Route::group(['as' => 'pm.', 'prefix' => 'profile-management'], function () {
        Route::get('/my-account', MyAccount::class)->name('my-account');
    });

    // Help Support
    Route::get('/help-support', HelpAndSupport::class)->name('help-support');

    // Notification Routes
    Route::name('notifications.')->prefix('notifications')->group(function () {
        Route::get('/', NotificationList::class)->name('index');
        Route::get('/{encryptedId}', NotificationShow::class)->name('show');
    });

    // Track Submit Routes
    Route::get('/track/submit', TrackSubmit::class)->name('track.submit');
});

// Faq Management
Route::get('user/frequently-asked-questions', Faq::class)->name('user.faq')->middleware('auth:web');

// Static page routes
Route::view('user/settings', 'backend.user.settings')->name('settings')->middleware('auth:web');
Route::view('user/page', 'backend.user.dummy-page')->name('page')->middleware('auth:web');
Route::view('user/charts', 'backend.user.chart')->name('charts')->middleware('auth:web');