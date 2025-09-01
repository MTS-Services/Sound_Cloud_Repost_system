<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Livewire\PlanPage;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', Home::class)->name('landing');
    Route::get('/plan', PlanPage::class)->name('plan');
});
