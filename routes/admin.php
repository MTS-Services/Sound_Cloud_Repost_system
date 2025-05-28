<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;


Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/2nd', [AdminDashboardController::class, 'dashboard2nd'])->name('admin.dashboard.2');
});
