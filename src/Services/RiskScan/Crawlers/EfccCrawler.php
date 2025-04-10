<?php

namespace Cloudspace\AML\Services\RiskScan\Crawlers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Cloudspace\AML\Traits\KeywordConfidenceTrait;

class EfccCrawler
{
    use KeywordConfidenceTrait;

    public function scan(string $fullName): array
    {
        sleep(2); // Simple throttle — avoid hammering
        $userAgent = 'CloudspaceAMLBot/1.0 (+https://yourdomain.com/bot)';

        $response = Http::withHeaders([
            'User-Agent' => $userAgent
        ])->get('https://efcc.gov.ng/news');

        if (!$response->successful()) return [];

        $crawler = new Crawler($response->body());
        $matches = [];

        $crawler->filter('.item-list .views-row')->each(function ($node) use (&$matches, $fullName) {
            $title = $node->filter('h3')->text();
            $summary = $node->filter('.field-content')->text('');

            $combined = strtolower($title . ' ' . $summary);

            if (str_contains($combined, strtolower($fullName))) {
                $matches[] = [
                    'source' => 'EFCC Website',
                    'match_type' => 'crawled match',
                    'description' => trim($summary),
                    'confidence' => $this->guessConfidence($combined),
                ];
            }
        });

        return $matches;
    }
}
