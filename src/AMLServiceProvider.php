<?php

namespace Cloudspace\AML;

use Cloudspace\AML\Console\Commands\ScanUserRisk;
use Cloudspace\AML\Jobs\ScanNewUsersJob;
use Cloudspace\AML\Providers\RiskScannerServiceProvider;
use Cloudspace\AML\Services\SanctionScan\AMLService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AMLServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/aml.php', 'aml');
        $this->app->singleton('aml', function () {
            return new AMLService();
        });
        $this->app->register(RiskScannerServiceProvider::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/aml.php' => config_path('aml.php'),
        ], 'aml-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/aml'),
        ], 'aml-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'aml-migrations');

        // $this->publishes([
        //     __DIR__.'/../resources/lang' => resource_path('lang/vendor/aml'),
        // ], 'aml-lang');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'aml');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ScanUserRisk::class,
            ]);
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                ScanUserRisk::class,
            ]);

            $this->app->booted(function () {
                app(Schedule::class)->job(new ScanNewUsersJob)->dailyAt('2:00');
            });
        }
    }
}
