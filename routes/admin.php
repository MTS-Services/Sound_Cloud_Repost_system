<?php

use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;


Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('admin-management')->name('am.')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            // Main index page
            Route::get('/', [AdminController::class, 'index'])->name('index');

            // AJAX endpoints for DataTable
            Route::get('/fetch', [AdminController::class, 'fetch'])->name('fetch');
            Route::post('/save', [AdminController::class, 'save'])->name('save');
            Route::put('/{id}', [AdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'delete'])->name('delete');
            Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk-action');

            // Optional: Export endpoint
            Route::get('/export', [AdminController::class, 'export'])->name('export');
        });
    });
});
