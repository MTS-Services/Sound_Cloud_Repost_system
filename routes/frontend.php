<?php

use App\Http\Controllers\Backend\Admin\PaymentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\PaypalController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', [HomeController::class, 'landing'])->name('landing');
    
    // Stripe Payments Routes
    Route::controller(PaymentController::class)->name('payment.')->prefix('payment')->group(function () {
        Route::get('/method/{credit_id}', 'paymentMethod')->name('method');
        Route::get('/{order_id}', 'showPaymentForm')->name('form');
        Route::post('/create-intent', 'createPaymentIntent')->name('create-intent');
        Route::get('/success/page', 'paymentSuccess')->name('success');
        Route::get('/cancel', 'paymentCancel')->name('cancel');
    });
});
Route::get('/products/payment', [PaypalController::class, 'paypalPaymentLink'])->name('paypal.paymentLink');
Route::get('/products/payment/success/', [PaypalController::class, 'paypalPaymentSuccess'])->name('paypal.paymentSuccess');
Route::get('/products/payment/cancel', [PaypalController::class, 'paypalPaymentCancel'])->name('paypal.paymentCancel');