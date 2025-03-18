<?php

namespace Cloudspace\AML;

use Illuminate\Support\Facades\Http;

class AMLService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('aml.api_base_url');
    }

    public function checkSanctions(array $piiData)
    {
        $sanctionData = [
            'type' => 'person',
            'name' => $piiData['name'] ?? null,
            'birthDate' => $piiData['dob'] ?? null,
            'gender' => $piiData['gender'] ?? null,
        ];

        $response = Http::get("{$this->baseUrl}/search", $sanctionData);

        return $response->json();
    }
}
