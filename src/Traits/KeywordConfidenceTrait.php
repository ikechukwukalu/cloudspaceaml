<?php

namespace Cloudspace\AML\Traits;

trait KeywordConfidenceTrait
{
    public function guessConfidence(string $text): int
    {
        $text = strtolower($text);
        $weights = $this->keywords();

        foreach ($weights as $keyword => $weight) {
            if (str_contains($text, $keyword)) {
                return $weight;
            }
        }

        return 50;
    }

    protected function keywords(): array
    {
        return [
            'efcc' => 90,
            'money laundering' => 85,
            'scam' => 80,
            'fraud' => 75,
            'arrested' => 70,
            'court' => 60,
            'financial crime' => 80,
            'nigeria police' => 65,
        ];
    }
}
