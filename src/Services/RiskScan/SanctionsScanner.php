<?php

namespace Cloudspace\AML\Services\RiskScan;

class SanctionsScanner
{
    public function scan(string $fullName): array
    {
        // In future, crawl EFCC/OFAC, UN, etc.
        // For now, return a dummy match if name contains a known suspicious keyword.
        $suspicious = ['abacha', 'osama', 'fraud', 'efcc'];

        foreach ($suspicious as $keyword) {
            if (stripos($fullName, $keyword) !== false) {
                return [[
                    'source' => 'OFAC Dummy List',
                    'match_type' => 'name',
                    'description' => "Name matched keyword: $keyword",
                    'confidence' => 90,
                ]];
            }
        }

        return [];
    }
}
