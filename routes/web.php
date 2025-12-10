<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DatatableController;
use App\Http\Controllers\Backend\FileManagementController;
use App\Http\Controllers\Backend\User\PaymentController;
use App\Http\Controllers\BlogController;

Route::post('update/sort/order', [DatatableController::class, 'updateSortOrder'])->name('update.sort.order');
Route::post('/content-image/upload', [FileManagementController::class, 'contentImageUpload'])->name('file.ci_upload');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/frontend.php';


// Test Notification Send Routes
Route::get('/send', function () {
    return view('send');
});
Route::post('/send-notification', [App\Http\Controllers\TestController::class, 'sendNotification'])->name('send-notification');
Route::get('/buttons', function () {
    return view('button-showcase');
});

Route::post('/webhook/stripe', [App\Http\Controllers\Webhooks\StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook');

// Route::post('/payment/create-subscription', [PaymentController::class, 'createSubscription'])
//     ->name('user.payment.create-subscription')
//     ->middleware(['auth']);

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
