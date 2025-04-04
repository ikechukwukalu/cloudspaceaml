<?php

namespace Cloudspace\AML\Providers;

use Illuminate\Support\ServiceProvider;
use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Services\RiskScan\RiskScannerService;
use Cloudspace\AML\Services\RiskScan\WebSearchScannerResolver;

class RiskScannerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('RiskScanner', function () {
            return new RiskScannerService();
        });

        $this->app->singleton(WebSearchScannerInterface::class, WebSearchScannerResolver::class);
    }
}
