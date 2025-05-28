<?php

use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;


Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        // Route::resource('admin', AdminController::class);
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        // DataTable API Routes
        Route::prefix('admin')->name('admin.')->group(function () {
            // Fetch data for DataTable
            Route::post('/fetch', [AdminController::class, 'fetch'])->name('fetch');

            // CRUD Operations
            Route::post('/save', [AdminController::class, 'save'])->name('save');
            Route::put('/update/{id}', [AdminController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('delete');

            // Bulk Operations
            Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk-action');

            // Export
            Route::get('/export', [AdminController::class, 'export'])->name('export');

            // Statistics
            Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        });
    });
});
