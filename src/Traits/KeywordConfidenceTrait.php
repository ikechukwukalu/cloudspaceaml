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
            'efcc'              => 95,
            'fraud'             => 90,
            'scam'              => 85,
            'syndicate'         => 80,
            'arrest'            => 80,
            'money laundering'  => 85,
            'cybercrime'        => 80,
            'billion'           => 75,
            'bribe'             => 75,
            'prosecution'       => 70,
            'suspect'           => 70,
            'charge'            => 70,
            'court'             => 70,
            'transaction'       => 65,
            'investigation'     => 65,
            'criminal'          => 65,
            'nigeria'           => 50
        ];
    }
}
