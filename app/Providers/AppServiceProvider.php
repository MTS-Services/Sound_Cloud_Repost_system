<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');
        Blade::componentNamespace('App\\View\\Components\\User', 'user');
        Blade::componentNamespace('App\\View\\Components\\Frontend', 'frontend');
        Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();
        Gate::before(function ($admin, $ability) {
            return $admin->hasRole('Super Admin') ? true : null;
        });
    }
}
