<?php

namespace Cloudspace\AML;

use Illuminate\Support\ServiceProvider;

class AMLServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/aml.php', 'aml');
        $this->app->singleton('aml', function () {
            return new AMLService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/aml.php' => config_path('aml.php'),
        ], 'aml-config');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
