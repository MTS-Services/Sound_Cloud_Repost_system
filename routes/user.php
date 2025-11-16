<?php

use App\Http\Controllers\Api\CampaignPlaybackController;
use App\Http\Controllers\Backend\User\PaymentController;
use App\Livewire\User\AddCredit;
use App\Livewire\User\Analytics;
use App\Livewire\User\CampaignManagement\Campaign;
use App\Livewire\User\CampaignManagement\MyCampaign;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\User\CampaignManagement\RedesignCampaign;
use App\Livewire\User\Chart;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Faq;
use App\Livewire\User\FavouriteMember;
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



    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::group(['as' => 'cm.', 'prefix' => 'campaign-management'], function () {
        Route::get('/my-campaigns', MyCampaign::class)->name('my-campaigns');
        Route::get('/campaigns', Campaign::class)->name('campaigns');
        Route::get('/campaigns2', RedesignCampaign::class)->name('campaigns2');
    });
    Route::name('notifications.')->prefix('notifications')->group(function () {
        Route::get('/', NotificationList::class)->name('index');
        Route::get('/{encryptedId}', NotificationShow::class)->name('show');
    });
    Route::get('members', Member::class)->name('members');
    Route::get('reposts-request', RepostRequest::class)->name('reposts-request');
    Route::get('frequently-asked-questions', Faq::class)->name('faq');
    Route::get('plans', Plans::class)->name('plans');
    // Route::get('my-account/{user_name?}', MyAccount::class)->name('my-account');
    Route::get('profile/{user_name}', MyAccount::class)
        // ->where('user_name', '^(?!my-account$).*') // avoid conflict with /my-account
        ->name('my-account.user');

    // Route for default "My Account"
    Route::get('/my-account', MyAccount::class)
        ->name('my-account');
    Route::get('help-support', HelpAndSupport::class)->name('help-support');
    Route::get('track/submit', TrackSubmit::class)->name('track.submit');
    Route::get('settings', Settings::class)->name('settings');
    Route::get('charts', Chart::class)->name('charts');

    Route::get('/add-credits', AddCredit::class)->name('add-credits');
    Route::get('/analytics', Analytics::class)->name('analytics');



    // Payment Routes
    Route::controller(PaymentController::class)->name('payment.')->prefix('payment')->group(function () {
        Route::get('/method/{order_id}', 'paymentMethod')->name('method');
        Route::get('/{order_id}', 'showPaymentForm')->name('form');
        Route::post('/create-intent', 'createPaymentIntent')->name('create-intent');
        Route::get('/success/page', 'paymentSuccess')->name('success');
        Route::get('/cancel', 'paymentCancel')->name('cancel');
        Route::get('/paypal/paymentLink/{encryptedOrderId}', 'paypalPaymentLink')->name('paypal.paymentLink');
        Route::get('/paypal/payment/success/', 'paypalPaymentSuccess')->name('paypal.paymentSuccess');
        Route::get('/paypal/payment/cancel', 'paypalPaymentCancel')->name('paypal.paymentCancel');
    });

    // Favourite / Starred Users Routes
    Route::get('favourites', FavouriteMember::class)->name('favourites');


    Route::post('/campaign/track-playback', [CampaignPlaybackController::class, 'trackPlayback'])
        ->name('api.campaign.track-playback');
});
