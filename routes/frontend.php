<?php

use App\Http\Controllers\Backend\Admin\PaymentController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', [HomeController::class, 'landing'])->name('landing');
    
    // Stripe Payments Routes
    Route::controller(PaymentController::class)->name('payment.')->prefix('payment')->group(function () {
        Route::get('/', 'showPaymentForm')->name('form');
        Route::post('/create-intent', 'createPaymentIntent')->name('create-intent');
        Route::get('/success', 'paymentSuccess')->name('success');
        Route::get('/cancel', 'paymentCancel')->name('cancel');
    });
});
