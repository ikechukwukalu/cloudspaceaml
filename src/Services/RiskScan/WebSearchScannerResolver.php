<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Contracts\WebSearchScannerInterface;

class WebSearchScannerResolver implements WebSearchScannerInterface
{
    protected WebSearchScannerInterface $scanner;

    public function __construct()
    {
        $this->scanner = match (config('aml.web_search.driver')) {
            'bing' => app(BingWebSearchScanner::class),
            'contextual' => app(ContextualWebNewsScanner::class),
            default => app(BingWebSearchScanner::class)
        };
    }

    public function scan(string $fullName): array
    {
        return $this->scanner->scan($fullName);
    }
}
