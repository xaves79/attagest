<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Entreprise;

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
        // Vérifier que la table 'entreprises' existe avant d'essayer d'y accéder
        if (Schema::hasTable('entreprises')) {
            View::share('entreprise', Entreprise::first());
        } else {
            View::share('entreprise', null);
        }
    }
}