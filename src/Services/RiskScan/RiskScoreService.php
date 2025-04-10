<?php

namespace Cloudspace\AML\Services\RiskScan;

class RiskScoreService
{
    public function calculate(array $matches): string
    {
        if (empty($matches)) {
            return 'low';
        }

        if (collect($matches)->contains(fn ($m) => $m['confidence'] >= 85)) {
            $riskLevel = 'high';
        } elseif (collect($matches)->contains(fn ($m) => $m['confidence'] >= 60)) {
            $riskLevel = 'medium';
        } else {
            $riskLevel = 'low';
        }

        return $riskLevel;
    }
}
