<?php

use App\Livewire\Home;
use App\Livewire\PlanPage;
use App\Livewire\analyticsPage;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', Home::class)->name('landing');
    Route::get('/plan', PlanPage::class)->name('plan');
    // Route::get('/analytics ', analyticsPage::class)->name('analytics');
});
