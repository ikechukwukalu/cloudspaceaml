<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Mail\RiskAlertMail;
use Cloudspace\AML\Models\RiskScanLog;
use Cloudspace\AML\Models\RiskScanResult;
use Illuminate\Support\Facades\Mail;

class RiskScannerService
{
    public function scan(string $name, null|string $bvn = null, null|string $nin = null): RiskScanResult
    {
        // 1. Store base info
        $result = RiskScanResult::create([
            'full_name' => $name,
            'bvn' => $bvn,
            'nin' => $nin,
            'risk_level' => 'low',
        ]);

        // 2. Crawl and match
        $matches = app(SanctionsScanner::class)->scan($name);
        $matches = array_merge(
            $matches,
            app(WebSearchScannerInterface::class)->scan($name)
        );

        // 3. Save matches
        foreach ($matches as $match) {
            $result->matches()->create($match);
        }

        // 4. Score
        $risk = app(RiskScoreService::class)->calculate($matches);
        $result->update(['risk_level' => $risk]);

        $result->refresh();

        // 5. Notify if high risk
        if ($result->risk_level === 'high') {
            Mail::to(config('aml.alert_email'))->queue(new RiskAlertMail($result));
        }

        RiskScanLog::create([
            'full_name' => $name,
            'bvn' => $bvn,
            'nin' => $nin,
            'risk_level' => $result->risk_level,
            'match_count' => $result->matches->count(),
            'summary' => [
                'match_sources' => $result->matches->pluck('source')->unique()->values(),
            ],
            'scanned_at' => now(),
        ]);

        return $result->refresh();
    }
}
