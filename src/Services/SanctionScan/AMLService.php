<?php

namespace Cloudspace\AML\Services\SanctionScan;

use Cloudspace\AML\Traits\ResponseData;
use Illuminate\Support\Facades\Http;

class AMLService
{
    use ResponseData;

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('aml.api_base_url');
    }

    public function checkSanctions(array $piiData)
    {
        $whiteList = config('aml.white_list');
        $optionalArrayList = config('aml.optional_array_list');

        foreach ($whiteList as $value) {
            if (!array_key_exists($value, $piiData)) {
                return $this->responseData(false, null, "Missing required field: $value");
            }
        }

        $sanctionData = [
            'type' => 'person',
            'name' => $piiData['name'] ?? null,
            'birthDate' => $piiData['birthDate'] ?? null,
            'gender' => $piiData['gender'] ?? null,
            'minMatch' => '0.75',
        ];

        foreach ($optionalArrayList as $value) {
            if (array_key_exists($value, $piiData)) {
                if (!is_array($piiData[$value])) {
                    return $this->responseData(false, null, "Field '{$value}' must be an array");
                }

                $sanctionData[$value] = $piiData[$value];
            }
        }

        $response = Http::get("{$this->baseUrl}/search", $sanctionData);

        return $this->responseData($response->successful(), $response->body());
    }
}
