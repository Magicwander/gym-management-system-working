<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force asset URLs to use the correct domain
        if (config('app.env') === 'local') {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
}
