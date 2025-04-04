<?php

namespace Cloudspace\AML\Services\RiskScan;

class RiskScoreService
{
    public function calculate(array $matches): string
    {
        if (empty($matches)) {
            return 'low';
        }

        $score = 0;
        foreach ($matches as $match) {
            $score += $match['confidence'] ?? 50;
        }

        $average = $score / count($matches);

        return match (true) {
            $average >= 80 => 'high',
            $average >= 50 => 'medium',
            default        => 'low',
        };
    }
}
