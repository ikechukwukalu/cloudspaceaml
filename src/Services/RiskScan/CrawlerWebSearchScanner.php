<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Services\RiskScan\Crawlers\CrawlAggregator;

class CrawlerWebSearchScanner implements WebSearchScannerInterface
{
    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        return app(CrawlAggregator::class)->scan($fullName);
    }
}
