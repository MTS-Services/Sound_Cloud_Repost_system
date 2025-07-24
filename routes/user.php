<?php

use App\Http\Controllers\Backend\User\AddCaeditsController;
use App\Http\Controllers\Backend\User\AnalyticsController;
use App\Http\Controllers\Backend\User\CampaignManagement\CampaignController;
use App\Http\Controllers\Backend\User\CampaignManagement\MyCampaignController;
use App\Http\Controllers\Backend\User\PromoteController;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::prefix('auth/soundcloud')->name('soundcloud.')->group(function () {
    Route::get('redirect', [SoundCloudController::class, 'redirect'])->name('redirect');
    Route::get('callback', [SoundCloudController::class, 'callback'])->name('callback');
    Route::post('disconnect', [SoundCloudController::class, 'disconnect'])->name('disconnect')->middleware('auth:web');
    Route::post('sync', [SoundCloudController::class, 'sync'])->name('sync')->middleware('auth:web');
});

// Dashboard and other routes
Route::group(['middleware' => ['auth:web'], 'as' => 'user.'], function () {
    Route::get('/dashboard', function () {

        return view('backend.user.dashboard');
    })->name('dashboard');

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
        Route::get('/campaigns', [MyCampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create/{track_id}', [MyCampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns', [MyCampaignController::class, 'store'])->name('campaigns.store');
    });

    // Repost Campaign tracks
    Route::controller(CampaignController::class)->name('campaign.')->prefix('campaign')->group(function () {
        Route::get('/feed', 'campaignFeed')->name('feed');
        Route::post('/{repost}', 'repost')->name('repost');
    });
});
