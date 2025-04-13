<?php

namespace Cloudspace\AML\Providers;

use Illuminate\Support\ServiceProvider;
use Cloudspace\AML\Services\RiskScan\RiskScannerService;

class RiskScannerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('RiskScanner', function () {
            return new RiskScannerService();
        });
    }
}
