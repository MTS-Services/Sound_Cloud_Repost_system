<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\PackageManagement\CreditController;
use App\Http\Controllers\Backend\Admin\UserManagement\UserController;

Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

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
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{user}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{user}', 'permanentDelete')->name('permanent-delete');
        });
    });
});
