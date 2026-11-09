<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // Paksa HTTPS di production (Railway) supaya asset aman.
        if ($this->app->environment('production')) {
            $this->app['url']->forceScheme('https');
        }

        // Gunakan tampilan pagination kustom (tanpa ketergantungan Tailwind build).
        Paginator::defaultView('vendor.pagination.custom');
    }
}
