<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController as UserAuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController as UserConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController as UserEmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController as UserEmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController as UserNewPasswordController;
use App\Http\Controllers\Auth\PasswordController as UserPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController as UserPasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController as UserRegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController as UserVerifyEmailController;

use App\Http\Controllers\backend\admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\backend\admin\Auth\ConfirmablePasswordController as AdminConfirmablePasswordController;
use App\Http\Controllers\backend\admin\Auth\EmailVerificationNotificationController as AdminEmailVerificationNotificationController;
use App\Http\Controllers\backend\admin\Auth\EmailVerificationPromptController as AdminEmailVerificationPromptController;
use App\Http\Controllers\backend\admin\Auth\NewPasswordController as AdminNewPasswordController;
use App\Http\Controllers\backend\admin\Auth\PasswordController as AdminPasswordController;
use App\Http\Controllers\backend\admin\Auth\PasswordResetLinkController as AdminPasswordResetLinkController;
use App\Http\Controllers\backend\admin\Auth\VerifyEmailController as AdminVerifyEmailController;

use Illuminate\Support\Facades\Route;


// User Auth Routes

Route::middleware('guest')->group(function () {
    Route::get('register', [UserRegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [UserRegisteredUserController::class, 'store']);

    Route::get('login', [UserAuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [UserAuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [UserPasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [UserPasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [UserNewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [UserNewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth:web')->group(function () {
    Route::get('verify-email', UserEmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', UserVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [UserEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [UserConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [UserConfirmablePasswordController::class, 'store']);

    Route::put('password', [UserPasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [UserAuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});


// Admin Auth Rotues
Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [AdminNewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('verify-email', AdminEmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', AdminVerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [AdminEmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [AdminConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [AdminConfirmablePasswordController::class, 'store']);

        Route::put('password', [AdminPasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
