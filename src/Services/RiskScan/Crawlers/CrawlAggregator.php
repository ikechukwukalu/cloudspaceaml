<?php

namespace Cloudspace\AML\Services\RiskScan\Crawlers;

class CrawlAggregator
{
    protected array $crawlers = [
        EfccCrawler::class,
        // VanguardCrawler::class,
    ];

    public function scan(string $fullName): array
    {
        $matches = [];

        foreach ($this->crawlers as $crawler) {
            $instance = app($crawler);
            $matches = array_merge($matches, $instance->scan($fullName));
        }

        return $matches;
    }
}
