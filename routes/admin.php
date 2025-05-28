<?php

use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;


Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        Route::resource('admin', AdminController::class);
    });
});
