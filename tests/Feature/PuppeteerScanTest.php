<?php

namespace Cloudspace\AML\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Cloudspace\AML\Services\RiskScan\Crawlers\PuppeteerCrawler;

class PuppeteerScanTest extends TestCase
{

    public function test_it_scans_bing_news()
    {
        $crawler = new PuppeteerCrawler;
        $results = $crawler->scan('Emeka Anaga');

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);

        $first = $results[0];
        $this->assertArrayHasKey('description', $first);
        $this->assertArrayHasKey('confidence', $first);
    }
}
