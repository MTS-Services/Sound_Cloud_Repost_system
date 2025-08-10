<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\DatatableController;
use App\Http\Controllers\Backend\FileManagementController;
use App\Http\Controllers\Backend\Admin\CampaignManagement\CampaignController;
use App\Livewire\NotificationList;
use App\Livewire\NotificationShow;
use App\Livewire\StatsCard;
use Illuminate\Support\Facades\Broadcast;

Route::post('update/sort/order', [DatatableController::class, 'updateSortOrder'])->name('update.sort.order');
Route::post('/content-image/upload', [FileManagementController::class, 'contentImageUpload'])->name('file.ci_upload');
Route::get('/details', [DetailsController::class, 'details'])->name('detils.show');



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/frontend.php';

Route::get('/receive', function () {
    return view('receive');
});
Route::get('/send', function () {
    return view('send');
});
Route::post('/send-notification', [App\Http\Controllers\TestController::class, 'sendNotification'])->name('send-notification');
Route::get('/say-hi', [App\Http\Controllers\TestController::class, 'sayHi'])->name('say-hi');
// Route to show the form for sending private notifications
Route::get('/send-private-message', function () {
    // We'll create a new blade file for this form.
    return view('send-private-message');
})->name('send-private-message.form');

// Route to handle the form submission and dispatch the event
// This route will call a method in your controller.
Route::post('/send-private-message', [App\Http\Controllers\TestController::class, 'sendPrivateMessage'])->name('send-private-message.send');