<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Services\RiskScan\Crawlers\PuppeteerCrawler;

class WebSearchScannerResolver implements WebSearchScannerInterface
{
    protected WebSearchScannerInterface $scanner;

    public function __construct()
    {
        $this->scanner = match (config('aml.web_search.driver')) {
            'bing'        => app(BingWebSearchScanner::class),
            'contextual'  => app(ContextualWebNewsScanner::class),
            'crawler'     => app(CrawlerWebSearchScanner::class),
            'puppeteer'   => app(PuppeteerCrawler::class),
            default => app(BingWebSearchScanner::class)
        };
    }

    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        \Illuminate\Support\Facades\Log::info($fullName);
        \Illuminate\Support\Facades\Log::info($scanResultId);
        return $this->scanner->scan($fullName, $scanResultId);
    }
}
