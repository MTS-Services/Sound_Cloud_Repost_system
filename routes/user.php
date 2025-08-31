<?php

use App\Http\Controllers\Backend\User\AddCaeditsController;
use App\Http\Controllers\Backend\User\AnalyticsController;
use App\Livewire\User\AddCredit;
use App\Livewire\User\CampaignManagement\Campaign;
use App\Livewire\User\CampaignManagement\MyCampaign;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Faq;
use App\Livewire\User\HelpAndSupport;
use App\Livewire\User\Member;
use App\Livewire\User\RepostRequest;
use App\Livewire\User\Notification\NotificationList;
use App\Livewire\User\Notification\NotificationShow;
use App\Livewire\User\Plans;
use App\Livewire\User\MyAccount;
use App\Livewire\User\Settings;
use App\Livewire\User\TrackSubmit;


// SoundCloud Routes
Route::prefix('auth/soundcloud')->name('soundcloud.')->group(function () {
    Route::get('redirect', [SoundCloudController::class, 'redirect'])->name('redirect');
    Route::get('callback', [SoundCloudController::class, 'callback'])->name('callback');
    Route::post('disconnect', [SoundCloudController::class, 'disconnect'])->name('disconnect')->middleware('auth:web');
    Route::post('sync', [SoundCloudController::class, 'sync'])->name('sync')->middleware('auth:web');
});

// User routes (The 'verified' middleware has been removed)
Route::group(['middleware' => ['auth:web'], 'as' => 'user.', 'prefix' => 'user'], function () {
    Route::get('/profile-info', [ProfileController::class, 'emailAdd'])->name('email.add');
    Route::post('/profile-info/update', [ProfileController::class, 'emailStore'])->name('email.store');
    Route::get('/user-profile/{user_urn}', [ProfileController::class, 'profile'])->name('profile');
    Route::post('email/resend-verification', [ProfileController::class, 'resendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('email.resend.verification');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');


    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::group(['as' => 'cm.', 'prefix' => 'campaign-management'], function () {
        Route::get('/my-campaigns', MyCampaign::class)->name('my-campaigns');
        Route::get('/campaigns', Campaign::class)->name('campaigns');
    });
    Route::name('notifications.')->prefix('notifications')->group(function () {
        Route::get('/', NotificationList::class)->name('index');
        Route::get('/{encryptedId}', NotificationShow::class)->name('show');
    });
    Route::get('members', Member::class)->name('members');
    Route::get('reposts-request', RepostRequest::class)->name('reposts-request');
    Route::get('frequently-asked-questions', Faq::class)->name('faq');
    Route::get('plans', Plans::class)->name('plans');
    Route::get('my-account/{user_urn?}', MyAccount::class)->name('my-account');
    Route::get('help-support', HelpAndSupport::class)->name('help-support');
    Route::get('track/submit', TrackSubmit::class)->name('track.submit');
    Route::get('settings', Settings::class)->name('settings');
    Route::view('charts', 'backend.user.chart')->name('charts');

    Route::get('/add-credits', AddCredit::class)->name('add-credits');
    Route::post('/buy-credits', [AddCaeditsController::class, 'buyCredits'])->name('buy-credits');

});




// Static page routes

