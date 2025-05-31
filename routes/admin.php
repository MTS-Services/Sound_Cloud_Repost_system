<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;


Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        // Admin Routes
        Route::resource('admin', AdminController::class);
        Route::controller(AdminController::class)->name('admin.')->prefix('admin')->group(function () {
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{admin}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{admin}', 'permanentDelete')->name('permanent-delete');
        });
        // Role Routes
        Route::resource('role', RoleController::class);
        Route::controller(RoleController::class)->name('role.')->prefix('role')->group(function () {
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{role}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{role}', 'permanentDelete')->name('permanent-delete');
        });
        // Permission Routes
        Route::resource('permission', PermissionController::class);
        Route::controller(PermissionController::class)->name('permission.')->prefix('permission')->group(function () {
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{permission}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{permission}', 'permanentDelete')->name('permanent-delete');
        });
    });
});
