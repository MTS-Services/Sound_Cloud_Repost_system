<?php

use App\Http\Controllers\Backend\DatatableController;
use App\Http\Controllers\Backend\FileManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('update/sort/order', [DatatableController::class, 'updateSortOrder'])->name('update.sort.order');
Route::post('/content-image/upload', [FileManagementController::class, 'contentImageUpload'])->name('file.ci_upload');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/frontend.php';
