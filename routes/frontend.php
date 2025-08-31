<?php

use App\Http\Controllers\Backend\User\PaymentController;
use App\Http\Controllers\PaypalController;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', Home::class)->name('landing');

    // Stripe Payments Routes
    Route::controller(PaymentController::class)->name('payment.')->prefix('payment')->group(function () {
        Route::get('/method/{order_id}', 'paymentMethod')->name('method');
        Route::get('/{order_id}', 'showPaymentForm')->name('form');
        Route::post('/create-intent', 'createPaymentIntent')->name('create-intent');
        Route::get('/success/page', 'paymentSuccess')->name('success');
        Route::get('/cancel', 'paymentCancel')->name('cancel');
    })->middleware('auth:web');
});
Route::get('/paypal/paymentLink/{encryptedOrderId}', [PaypalController::class, 'paypalPaymentLink'])->name('paypal.paymentLink');
Route::get('/paypal/payment/success/', [PaypalController::class, 'paypalPaymentSuccess'])->name('paypal.paymentSuccess');
Route::get('/paypal/payment/cancel', [PaypalController::class, 'paypalPaymentCancel'])->name('paypal.paymentCancel');
