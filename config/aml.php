<?php

return [
    'api_base_url' => env('AML_API_BASE_URL', 'http://localhost:8084'),
    'white_list' => ['name', 'birthDate', 'gender'],
    'optional_array_list' => ['address', 'phone', 'email', 'website'],
    'black_list' => ['ssn', 'passport', 'bank_account'],
    'web_search' => [
        'driver' => env('AML_WEB_SEARCH_DRIVER', 'bing'),

        'bing_api_key' => env('BING_SEARCH_API_KEY'),
        'contextual_api_key' => env('CONTEXTUAL_API_KEY'),
    ],
    'google_api_key' => env('GOOGLE_SEARCH_API_KEY'),
    'google_cse_id' => env('GOOGLE_CSE_ID'),
    'google_search_url' => env('GOOGLE_SEARCH_API_ENDPOINT', 'https://www.googleapis.com/customsearch/v1'),
    'news_api_key' => env('NEWS_API_KEY'),
    'news_api_url' => env('NEWS_API_ENDPOINT', 'https://newsapi.org/v2/everything'),
    'alert_email' => env('AML_ALERT_EMAIL', 'compliance@yourdomain.com'),
];
