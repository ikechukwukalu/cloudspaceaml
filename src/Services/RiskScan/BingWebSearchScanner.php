<?php

namespace Cloudspace\AML\Services\RiskScan;

use Illuminate\Support\Facades\Http;
use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Traits\KeywordConfidenceTrait;

class BingWebSearchScanner implements WebSearchScannerInterface
{
    use KeywordConfidenceTrait;

    /**
     * Scan the web for mentions of a full name.
     *
     * @param string $fullName
     * @return array
     */
    public function scan(string $fullName): array
    {
        $apiKey = config('aml.web_search.bing_api_key');
        $query = urlencode("$fullName fraud OR scam OR efcc OR laundering");
        $url = "https://api.bing.microsoft.com/v7.0/search?q={$query}";

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $apiKey,
        ])->get($url);

        $matches = [];

        if ($response->successful()) {
            $webPages = $response->json('webPages.value') ?? [];

            foreach ($webPages as $page) {
                $matches[] = [
                    'source' => $page['provider'][0]['name'] ?? 'Bing',
                    'match_type' => 'media mention',
                    'description' => $page['snippet'] ?? 'No summary',
                    'confidence' => $this->guessConfidence($page['snippet'] ?? ''),
                ];
            }
        }

        return $matches;
    }

}
