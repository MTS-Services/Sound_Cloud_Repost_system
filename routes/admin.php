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
use App\Http\Controllers\Backend\Admin\Faq\FaqCategotyController;
use App\Http\Controllers\Backend\Admin\Faq\FaqController;
use App\Http\Controllers\Backend\Admin\NotificationController;
use App\Http\Controllers\Backend\Admin\RepostManagement\RepostController;
use App\Http\Controllers\Backend\Admin\RepostManagement\RepostRequestController;
use App\Http\Controllers\Backend\Admin\UserManagement\UserPlaneController;
use App\Models\Faq;

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
        Route::get('status/{feature_category}', [FeatureCategoryController::class, 'status'])->name('feature-category.status');
        // Feature Routes
        Route::resource('feature', FeatureController::class);
        Route::controller(FeatureController::class)->name('feature.')->prefix('feature')->group(function () {});
        Route::get('status/{feature}', [FeatureController::class, 'status'])->name('feature.status');
        // Plan Routes
        Route::resource('plan', PlanController::class);
        Route::controller(PlanController::class)->name('plan.')->prefix('plan')->group(function () {
            Route::post('/show/{plan}', 'show')->name('show');
            Route::get('/status/{plan}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{plan}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{plan}', 'permanentDelete')->name('permanent-delete');
        });

        // Credit Routes
        Route::resource('credit', CreditController::class);
        Route::controller(CreditController::class)->name('credit.')->prefix('credit')->group(function () {
            Route::get('/status/{credit}', 'status')->name('status');
            Route::post('/show/{credit}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{credit}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{credit}', 'permanentDelete')->name('permanent-delete');
            Route::get('/detail/{credit}', 'detail')->name('detail');
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
            Route::get('/user-detail/{user}', 'detail')->name('detail');
            //palylist
            Route::get('/playlist/{user}', 'playlist')->name('playlist'); // all playlist
            Route::get('/playlist-detail/{playlist}', 'playlistDetail')->name('playlist.details');
            Route::get('/playlist-tracks/{soundcloudUrn}', 'playlistTracks')->name('playlist.track-list'); // all tracks under playlist

            Route::get('/tracklist/{user}', 'tracklist')->name('tracklist'); // all tracklist
            Route::get('/details/{tracklist}', 'tracklistDetail')->name('tracklist.detail');
            Route::post('/tracklist/{urn}', 'tracklistShow')->name('tracklist.show');
            Route::get('/detail/{user}', 'detail')->name('detail');
            Route::post('/add-credit/{user_urn}', 'addCredit')->name('add-credit');
            Route::post('/add-plan', 'addPlan')->name('add-plan');
        });

        Route::resource('user-plane', UserPlaneController::class);
        Route::controller(UserPlaneController::class)->name('user-plane.')->prefix('user-plane')->group(function () {
            Route::post('/show/{user_plane}', 'show')->name('show');
            Route::get('/status/{user_plane}', 'status')->name('status');
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
            Route::get('/detail/{order}', 'detail')->name('detail');
        });

        // Credit Transaction Routes
        Route::controller(CreditTransactionController::class)->name('credit-transaction.')->prefix('credit-transaction')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/show/{credit_transaction}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::get('/purchase', 'purchase')->name('purchase');
            Route::get('/details/{transaction}', 'paymentDetails')->name('payment-detail');
            Route::get('/payments', 'payments')->name('payments');
            Route::get('/detail/{payment}', 'detail')->name('detail');
        });
    });

    // Repost Management Routes
    Route::group(['as' => 'rm.', 'prefix' => 'repost-management'], function () {
        Route::resource('repost', RepostController::class);
        Route::controller(RepostController::class)->name('repost.')->prefix('repost')->group(function () {

            Route::get('/detail/{repost}', 'detail')->name('detail');
        });
    });
    // Repost Request Management Routes
    Route::group(['as' => 'rrm.', 'prefix' => 'request-management'], function () {
        Route::resource('request', RepostRequestController::class);
        Route::controller(RepostRequestController::class)->name('request.')->prefix('request')->group(function () {

            Route::get('/detail/{request}', 'detail')->name('detail');
        });
    });

    Route::group(['as' => 'fm.', 'prefix' => 'faq-management'], function () {
        // Song Routes
        Route::resource('faq', FaqController::class);
        Route::controller(FaqController::class)->name('faq.')->prefix('faq')->group(function () {
            Route::get('/status/{faq}', 'status')->name('status');
            Route::post('/show/{faq}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{faq}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{faq}', 'permanentDelete')->name('permanent-delete');
        });
        Route::resource('faq-category', FaqCategotyController::class);
        Route::get('status/{faq_category}', [FaqCategotyController::class, 'status'])->name('faq-category.status');
        Route::post('show/{faq_category}', [FaqCategotyController::class, 'show'])->name('faq-category.show');
        Route::get('trash/bin', [FaqCategotyController::class, 'trash'])->name('faq-category.trash');
        Route::get('restore/{faq_category}', [FaqCategotyController::class, 'restore'])->name('faq-category.restore');
        Route::delete('permanent-delete/{faq_category}', [FaqCategotyController::class, 'permanentDelete'])->name('faq-category.permanent-delete');
    });


    // Admin Notification Routes
    Route::controller(NotificationController::class)->prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{encryptedId}', 'show')->name('show');
        Route::get('/api', 'getNotifications')->name('api');
        Route::get('/unread-count', 'getUnreadCount')->name('unread-count');
        Route::post('/mark-as-read', 'markAsRead')->name('mark-as-read');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
});
