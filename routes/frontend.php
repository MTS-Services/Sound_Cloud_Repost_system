<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\PaypalController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', [HomeController::class, 'landing'])->name('landing');

});
Route::get('/products/payment', [PaypalController::class, 'paypalPaymentLink'])->name('paypal.paymentLink');
Route::get('/products/payment/success', [PaypalController::class, 'paypalPaymentSuccess'])->name('paypal.paymentSuccess');
Route::get('/products/payment/cancel', [PaypalController::class, 'paypalPaymentCancel'])->name('paypal.paymentCancel');