<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Mail\RiskAlertMail;
use Cloudspace\AML\Models\RiskMatch;
use Cloudspace\AML\Models\RiskScanLog;
use Cloudspace\AML\Models\RiskScanResult;
use Illuminate\Support\Facades\Mail;

class RiskScannerService
{
    public function scan(array $data): RiskScanResult
    {
        // 1. Store base info
        $result = RiskScanResult::create([
            'full_name' => $data['full_name'] ?? null,
            'bvn' => $data['bvn'] ?? null,
            'nin' => $data['nin'] ?? null,
            'other_identifiable_code' => $data['other_identifiable_code'] ?? null,
            'other_identifiable_type' => $data['other_identifiable_type'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'address' => $data['address'] ?? null,
            'website' => $data['website'] ?? null,
            'risk_level' => 'low',
        ]);
        $result->refresh();

        // 2. Crawl and match
        $matches = collect($this->getMatches($result, $data['full_name'], CorruptionCasesScanner::class))
                        ->merge($this->getMatches($result, $data['full_name'], BingWebScanner::class))
                        ->unique('match_hash')->toArray();

        // 3. Save matches
        foreach ($matches as $match) {
            if (!RiskMatch::where('match_hash', $match['match_hash'])->exists()) {
                $result->matches()->create($match);
            }
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
            'risk_scan_result_id' => $result->id,
            'risk_level' => $result->risk_level,
            'match_count' => $result->matches->count(),
            'summary' => [
                'match_sources' => $result->matches->pluck('source')->unique()->values(),
            ],
            'scanned_at' => now(),
        ]);

        return $result->refresh();
    }

    private function getMatches(RiskScanResult $result, string $name, string $scannerService): array
    {
        $webScanner = app($scannerService);

        // Inject risk_scan_result_id only if supported
        if (method_exists($webScanner, 'withScanResultId'))
        {
            $webScanner = $webScanner->withScanResultId($result->id);
        }

        return $webScanner->scan($name);
    }
}
