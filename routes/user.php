<?php

use App\Http\Controllers\Backend\User\DashboardController;
use App\Http\Controllers\Backend\Admin\OrderManagement\OrderController as UserOrderController;
use App\Http\Controllers\Backend\User\AddCaeditsController;
use App\Http\Controllers\Backend\User\AnalyticsController;
use App\Livewire\User\CampaignManagement\Campaign;
use App\Livewire\User\CampaignManagement\MyCampaign;
use App\Http\Controllers\Backend\User\Members\MemberController;
use App\Http\Controllers\Backend\User\PromoteController;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\User\HelpAndSupport;
use App\Livewire\User\MemberManagement\Member;
use App\Livewire\User\MemberManagement\RepostRequest;
use App\Livewire\User\Notification\NotificationList;
use App\Livewire\User\Notification\NotificationShow;
use App\Livewire\User\PackageManagement\Pricing;
use App\Livewire\User\ProfileManagement\MyAccount;
use Illuminate\Validation\Rules\Unique;
use PHPUnit\TextUI\Help;

Route::prefix('auth/soundcloud')->name('soundcloud.')->group(function () {
    Route::get('redirect', [SoundCloudController::class, 'redirect'])->name('redirect');
    Route::get('callback', [SoundCloudController::class, 'callback'])->name('callback');
    Route::post('disconnect', [SoundCloudController::class, 'disconnect'])->name('disconnect')->middleware('auth:web');
    Route::post('sync', [SoundCloudController::class, 'sync'])->name('sync')->middleware('auth:web');
});

// Dashboard and other routes
Route::group(['middleware' => ['auth:web'], 'as' => 'user.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


    Route::get('/user-profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');
    Route::get('/add-credits', [AddCaeditsController::class, 'addCredits'])->name('add-credits');
    Route::get('/promote', [PromoteController::class, 'tracks'])->name('promote');

    // Campaign Management
    Route::group(['as' => 'cm.', 'prefix' => 'campaign-management'], function () {
        // Campaign Routes
        Route::get('/my-campaigns', MyCampaign::class)->name('my-campaigns');
        Route::get('/campaigns', Campaign::class)->name('campaigns');
    });
    Route::group(['as' => 'pkm.', 'prefix' => 'package-management'], function () {
        Route::get('/pricing', Pricing::class)->name('pricing');
    });
    // Member Management
    Route::group(['as' => 'mm.', 'prefix' => 'member-management'], function () {
        // Member Routes
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/request', [MemberController::class, 'request'])->name('members.request');
        Route::post('/confirm/repost/{id}', [MemberController::class, 'confirmRepost'])->name('repost.confirm');
    });
    Route::get('members', Member::class)->name('members');
    Route::get('reposts-request', RepostRequest::class)->name('reposts-request');
    // Order Manaagement Routes
    Route::controller(UserOrderController::class)->name('order.')->prefix('order')->group(function () {
        Route::post('/store', 'store')->name('store');
    });

    Route::group(['as' => 'pm.', 'prefix' => 'profile-management'], function () {

        Route::get('/my-account', MyAccount::class)->name('my-account');
    });

    // Help Support
    Route::get('/help-support', HelpAndSupport::class)->name('help-support');

    // Notification Routes 
    Route::name('notifications.')->prefix('notifications')->group(function () {
        Route::get('/', NotificationList::class)->name('index');
        Route::get('/{customNotification}', NotificationShow::class)->name('show');
    });
});



// static page route

// Route::view('/help-support', 'backend.user.help-support')->name('help-support');
Route::view('/settings', 'backend.user.settings')->name('settings');
Route::view('/page', 'backend.user.dummy-page')->name('page');
Route::view('/charts', 'backend.user.chart')->name('charts');
