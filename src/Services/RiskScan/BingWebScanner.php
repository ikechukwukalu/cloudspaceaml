<?php

namespace Cloudspace\AML\Services\RiskScan;

use Illuminate\Support\Facades\Process;

class BingWebScanner extends ScannerService
{

    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        if (!$scanResultId) {
            $scanResultId = $this->riskScanResultId;
        }

        $source = config('aml.web_search.puppeteer_source', 'bing');

        $script = match ($source) {
            'bing' => 'puppeteer-bing-news.cjs',
            default => 'puppeteer-bing-news.cjs',
        };

        $scriptPath = base_path("packages/cloudspace/aml/src/Services/RiskScan/Crawlers/js/{$script}");

        $process = Process::timeout(30)
            ->run("node {$scriptPath} \"$fullName\"");

        if ($process->failed()) {
            report("Puppeteer scan failed: " . $process->errorOutput());
            return [];
        }

        $results = json_decode($process->output(), true);

       if (!is_array($results)) {
            return [];
        }

        $matches = [];

        // Store each match in DB
        foreach ($results as $match) {
            $matches[] = [
                'risk_scan_result_id' => $scanResultId,
                'source'              => $match['source'],
                'match_type'          => $match['match_type'],
                'description'         => $match['description'],
                'confidence'          => $match['confidence'],
                'source_url'          => $match['source_url'] ?? null,
                'match_hash'          => md5($match['source'].$match['match_type'].$match['description']),
                'response_payload' => null,
            ];
        }

        return $matches;
    }
}
