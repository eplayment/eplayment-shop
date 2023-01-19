<?php

namespace Epaygames\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes for epaygames
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load translation files
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'epaygames');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        Http::macro('epaygames', function ($host, $token, $endpoint, $data) {
            return Http::withToken($token)
                ->baseUrl($host)
                ->post($endpoint, $data);
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'paymentmethods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/app.php', 'app'
        );
    }
}
