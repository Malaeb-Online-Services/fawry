<?php

namespace AymanElshehawy\LaravelFawry;

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

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fawry');
        
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/fawry'),
        ], 'fawry-translations');
    }
} 