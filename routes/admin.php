<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\UserManagement\UserController;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\OrderManagement\OrderController;
use App\Http\Controllers\Backend\Admin\PackageManagement\PlanController;
use App\Http\Controllers\Backend\Admin\PackageManagement\CreditController;
use App\Http\Controllers\Backend\Admin\PackageManagement\FeatureController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\CampaignManagement\CampaignController;
use App\Http\Controllers\Backend\Admin\UserManagement\UserPlaylistController;
use App\Http\Controllers\Backend\Admin\UserManagement\UserTracklistController;
use App\Http\Controllers\Backend\Admin\OrderManagement\CreditTransactionController;
use App\Http\Controllers\Backend\Admin\PackageManagement\FeatureCategoryController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\RepostManagement\RepostController;
use App\Http\Controllers\Backend\Admin\RepostManagement\RepostRequestController;

Route::group(['middleware' => ['auth:admin', 'admin'], 'prefix' => 'admin'], function () {

    // Button UI Route 
    Route::get('/button-ui', function () {
        return view('backend.admin.ui.buttons');
    })->name('button-ui');

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Profile Routes
    Route::controller(App\Http\Controllers\Backend\Admin\AdminProfileController::class)->name('admin.profile.')->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        // Admin Routes
        Route::resource('admin', AdminController::class);
        Route::controller(AdminController::class)->name('admin.')->prefix('admin')->group(function () {
            Route::post('/show/{admin}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{admin}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{admin}', 'permanentDelete')->name('permanent-delete');
        });
        // Role Routes
        Route::resource('role', RoleController::class);
        Route::controller(RoleController::class)->name('role.')->prefix('role')->group(function () {
            Route::post('/show/{role}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{role}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{role}', 'permanentDelete')->name('permanent-delete');
        });
        // Permission Routes
        Route::resource('permission', PermissionController::class);
        Route::controller(PermissionController::class)->name('permission.')->prefix('permission')->group(function () {
            Route::post('/show/{permission}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{permission}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{permission}', 'permanentDelete')->name('permanent-delete');
        });
    });

    // Package Management
    Route::group(['as' => 'pm.', 'prefix' => 'package-management'], function () {
        // Feature Category Routes
        Route::resource('feature-category', FeatureCategoryController::class);
        Route::controller(FeatureCategoryController::class)->name('feature-category.')->prefix('feature-category')->group(function () {});
        // Feature Routes
        Route::resource('feature', FeatureController::class);
        Route::controller(FeatureController::class)->name('feature.')->prefix('feature')->group(function () {});
        Route::resource('plan', PlanController::class);

        // Credit Routes
        Route::resource('credit', CreditController::class);
        Route::controller(CreditController::class)->name('credit.')->prefix('credit')->group(function () {
            Route::get('/status/{credit}', 'status')->name('status');
            Route::post('/show/{credit}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{credit}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{credit}', 'permanentDelete')->name('permanent-delete');
        });
    });

    Route::group(['as' => 'um.', 'prefix' => 'user-management'], function () {
        Route::resource('user', UserController::class);
        Route::controller(UserController::class)->name('user.')->prefix('user')->group(function () {
            Route::post('/show/{user}', 'show')->name('show');
            Route::get('/status/{user}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{user}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{user}', 'permanentDelete')->name('permanent-delete');
            Route::get('/playlist/{user}','playlist')->name('playlist'); // all playlist
            Route::post('/playlist/{soundcloudUrn}', 'playlistShow')->name('playlist.show'); // single palylist details
            Route::get('/playlist-tracks/{soundcloudUrn}', 'playlistTracks')->name('playlist.track-list'); // all tracks under playlist
            Route::get('/tracklist/{user}', 'tracklist')->name('tracklist'); // all tracklist
            Route::post('/tracklist/{urn}', 'tracklistShow')->name('tracklist.show');
            Route::post('/add-credit/{user_urn}', 'addCredit')->name('add-credit');

        });

        // Route::resource('playlist', UserPlaylistController::class);
        // Route::controller(UserPlaylistController::class)->name('playlist.')->prefix('playlist')->group(function () {
        //     Route::post('/show/{playlist}', 'show')->name('show');
        //     Route::get('/trash/bin', 'trash')->name('trash');
        //     Route::get('/restore/{playlist}', 'restore')->name('restore');
        //     Route::delete('/permanent-delete/{playlist}', 'permanentDelete')->name('permanent-delete');
        // });
        // Route::resource('tracklist', UserTracklistController::class);
        // Route::controller(UserTracklistController::class)->name('tracklist.')->prefix('tracklist')->group(function () {
        //     Route::post('/show/{tracklist}', 'show')->name('show');
        //     Route::get('/trash/bin', 'trash')->name('trash');
        //     Route::get('/restore/{tracklist}', 'restore')->name('restore');
        //     Route::delete('/permanent-delete/{tracklist}', 'permanentDelete')->name('permanent-delete');
        // });
    });

    // Campaign Management
    Route::group(['as' => 'cm.', 'prefix' => 'Campaign-management'], function () {
        // Campaign Routes
        Route::resource('campaign', CampaignController::class);
        Route::controller(CampaignController::class)->name('campaign.')->prefix('campaign')->group(function () {
            Route::get('/status/{campaign}', 'status')->name('status');
            Route::post('/show/{campaign}', 'show')->name('show');
            Route::get('/detail/{campaign}', 'detail')->name('detail');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{campaign}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{campaign}', 'permanentDelete')->name('permanent-delete');
        });
    });


    // Order Management Routes
    Route::group(['as' => 'om.', 'prefix' => 'order-management'], function () {
        // Order Routes
        Route::resource('order', OrderController::class);
        Route::controller(OrderController::class)->name('order.')->prefix('order')->group(function () {
            Route::get('/status/{order}', 'status')->name('status');
            Route::post('/show/{order}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{order}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{order}', 'permanentDelete')->name('permanent-delete');
        });

        // Credit Transaction Routes
        Route::controller(CreditTransactionController::class)->name('credit-transaction.')->prefix('credit-transaction')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::get('/purchase', 'purchase')->name('purchase');
            Route::get('/payments', 'payments')->name('payments'); 
          
            
        });
    });

    // Repost Management Routes
    Route::group(['as' => 'rm.', 'prefix' => 'repost-management'], function () {
          Route::resource('repost', RepostController::class);
        Route::controller(RepostController::class)->name('repost.')->prefix('repost')->group(function () {
            Route::post('/show/{repost}', 'show')->name('show');

        });
    });
    // Repost Request Management Routes
    Route::group(['as' => 'rrm.', 'prefix' => 'request-management'], function () {
          Route::resource('request', RepostRequestController::class);
          Route::controller(RepostRequestController::class)->name('request.')->prefix('request')->group(function () {
            Route::post('/show/{request}', 'show')->name('show');

        });
    });
});
