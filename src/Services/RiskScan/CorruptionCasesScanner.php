<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Models\RiskMatch;
use Illuminate\Support\Facades\Http;
use Cloudspace\AML\Contracts\WebSearchScannerInterface;

class CorruptionCasesScanner implements WebSearchScannerInterface
{
    protected null|int $riskScanResultId = null;

    public function withScanResultId(int $id): static
    {
        $this->riskScanResultId = $id;
        return $this;
    }

    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        $response = Http::get("https://v1.corruptioncases.ng/api/cases/search?q=" . urlencode($fullName));

        if (!$response->successful()) {
            report('CorruptionCases API failed: ' . $response->body());
            return [];
        }

        $data = $response->json();
        if (!isset($data['cases']) || !is_array($data['cases'])) {
            return [];
        }

        $nameParts = explode(' ', strtolower($fullName));
        $matches = [];

        foreach ($data['cases'] as $item) {
            $content = strtolower($item['title'] ?? '') . ' ' . strtolower($item['description'] ?? '');
            $found = collect($nameParts)->filter(fn($part) => str_contains($content, $part));

            if ($found->count() >= 2) {
                $matches[] = [
                    'source' => 'CorruptionCasesNG',
                    'match_type' => 'corruption registry',
                    'description' => $item['title'] . ' — ' . $item['description'],
                    'confidence' => 90,
                    'source_url' => $item['url'] ?? null,
                    'response_payload' => json_encode($item),
                ];
            }
        }

        return $matches;
    }
}
