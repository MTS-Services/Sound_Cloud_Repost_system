<?php

use App\Livewire\Home;
use App\Livewire\PlanPage;
use App\Livewire\Frontend\FAQ;
use App\Livewire\analyticsPage;
use App\Livewire\Frontend\Features;
use App\Livewire\Frontend\ContactUs;
use App\Livewire\Frontend\HelpCenter;
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\PrivacyPolicy;
use App\Livewire\Frontend\RefundPolicy;
use App\Livewire\Frontend\TermsConditions;

Route::group(['as' => 'f.'], function () {
    // Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/', Home::class)->name('landing');
    Route::get('/plan', PlanPage::class)->name('plan');
    // Route::get('/analytics ', analyticsPage::class)->name('analytics');
    Route::get('/features', Features::class)->name('features');
    Route::get('/faq', FAQ::class)->name('faq');
    Route::get('/terms-and-conditions',  TermsConditions::class)->name('terms-and-conditions');
    Route::get('/refund-policy',  RefundPolicy::class)->name('refund-policy');
    Route::get('/privacy-policy',  PrivacyPolicy::class)->name('privacy-policy');
    Route::get('/contact-us', ContactUs::class)->name('contact-us');
    Route::get('/help', HelpCenter::class)->name('help');
});
