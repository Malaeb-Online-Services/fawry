<?php

namespace AymanZayedElshehawy\Fawry;

use Illuminate\Support\ServiceProvider;

class FawryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/fawry.php', 'fawry'
        );

        $this->app->singleton('fawry', function ($app) {
            return new Services\FawryExpressCheckoutService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/Config/fawry.php' => config_path('fawry.php'),
        ], 'fawry-config');

        $this->loadTranslationsFrom(lang_path(), 'fawry');
        
        $this->publishes([
            lang_path('ar/fawry.php') => lang_path('ar/fawry.php'),
            lang_path('en/fawry.php') => lang_path('en/fawry.php'),
        ], 'fawry-translations');
    }
} 