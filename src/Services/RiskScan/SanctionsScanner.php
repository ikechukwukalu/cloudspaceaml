<?php

namespace Cloudspace\AML\Services\RiskScan;

use Illuminate\Support\Facades\Http;

class SanctionsScanner extends ScannerService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('aml.api_base_url');
    }

    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        $query = [
            'name' => $fullName,,
            'birthDate' => '1962-11-23',
            'gender' => 'male',
            'type' => 'person',
            'limit' => 10,
            'minMatch' => 0.75,
        ];

        $response = Http::get("{$this->baseUrl}/search", $query);

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        $matches = [];

        // Extract from each populated sanctions group
        $sources = [
            'SDNs', 'altNames', 'deniedPersons', 'bisEntities', 'euConsolidatedSanctionsList', 'ukConsolidatedSanctionsList'
        ];

        foreach ($sources as $sourceKey) {
            if (!isset($data[$sourceKey]) || !is_array($data[$sourceKey])) {
                continue;
            }

            foreach ($data[$sourceKey] as $record) {
                $name = $record['Name'] ?? $record['matchedName'] ?? 'Sanctioned Entity';
                $url = $record['SourceInfoURL'] ?? $record['SourceListURL'] ?? null;
                $matchScore = $record['match'] ?? null;

                $matches[] = [
                    'risk_scan_result_id' => $scanResultId,
                    'title' => $name,
                    'url' => $url,
                    'source' => 'MoovWatchman',
                    'match_type' => 'sanctions',
                    'match_hash' => hash('sha256', $name . ($url ?? '') . $matchScore),
                    'content' => json_encode($record),
                ];
            }
        }

        return $matches;
    }
}
