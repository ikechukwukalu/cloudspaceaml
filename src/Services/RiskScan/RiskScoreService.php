<?php

namespace Cloudspace\AML\Services\RiskScan;

class RiskScoreService
{
    public function calculate(array $matches): string
    {
        if (empty($matches)) {
            return 'low';
        }

        $contains = collect($matches);

        if ($contains->contains(fn ($m) =>
                ($m['confidence'] ?? 0) >= 85 ||
                ($m['match_type'] ?? '') === 'corruption registry'
            )
        ) {
            $riskLevel = 'high';
        } elseif ($contains->contains(fn ($m) => $m['confidence'] >= 85)) {
            $riskLevel = 'high';
        } elseif ($contains->contains(fn ($m) => $m['confidence'] >= 60)) {
            $riskLevel = 'medium';
        } else {
            $riskLevel = 'low';
        }

        return $riskLevel;
    }
}
