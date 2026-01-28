<?php

namespace app\Application\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
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
        /**
         * Prevent lazy-loading of models in a
         * non-production environment
         */
        Model::shouldBeStrict(!App::isProduction());

        // Force https scheme in production for added security
        if (App::isProduction()) {
            URL::forceScheme('https');
        }
    }
}
