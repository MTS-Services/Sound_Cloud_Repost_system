<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Socialite\SoundCloudProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditTransaction;


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
        Socialite::extend('soundcloud', function ($app) {
            $config = $app['config']['services.soundcloud'];

            return Socialite::buildProvider(SoundCloudProvider::class, $config);
        });



        Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');
        Blade::componentNamespace('App\\View\\Components\\User', 'user');
        Blade::componentNamespace('App\\View\\Components\\Frontend', 'frontend');
        Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();
        Gate::before(function ($admin, $ability) {
            return $admin->hasRole('Super Admin') ? true : null;
        });

        View::composer('backend.user.layouts.app', function ($view) {
            $totalCredits = 0;

            if (Auth::check()) {
                $user = Auth::user();

                // Check if urn exists, otherwise fallback to id
                $urnValue = $user->urn;
                $totalCredits = CreditTransaction::where('receiver_urn', $urnValue)
                    ->where('status', 'succeeded')
                    ->sum('credits');
                $totalCredits = number_format($totalCredits, 0);
            }

            $view->with('totalCredits', $totalCredits);
        });
    }
}
