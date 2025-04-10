<?php

namespace Cloudspace\AML\Services\RiskScan;

use Illuminate\Support\Facades\Http;
use Cloudspace\AML\Contracts\WebSearchScannerInterface;
use Cloudspace\AML\Traits\KeywordConfidenceTrait;

class ContextualWebNewsScanner implements WebSearchScannerInterface
{
    use KeywordConfidenceTrait;

    /**
     * Scan the web for news articles related to the given full name.
     *
     * @param string $fullName
     * @return array
     */
    public function scan(string $fullName, null|int $scanResultId = null): array
    {
        $apiKey = config('aml.web_search.contextual_api_key');
        $query = urlencode("$fullName fraud OR scam OR efcc OR laundering");
        $url = "https://contextualwebsearch-websearch-v1.p.rapidapi.com/api/search/NewsSearchAPI?q={$query}&pageNumber=1&pageSize=10";

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $apiKey,
            'X-RapidAPI-Host' => 'contextualwebsearch-websearch-v1.p.rapidapi.com'
        ])->get($url);

        $matches = [];

        if ($response->successful()) {
            foreach ($response->json('value') ?? [] as $article) {
                $matches[] = [
                    'source' => parse_url($article['url'], PHP_URL_HOST),
                    'match_type' => 'news mention',
                    'description' => $article['description'] ?? 'No description',
                    'confidence' => $this->guessConfidence($article['description'] ?? ''),
                ];
            }
        }

        return $matches;
    }
}
