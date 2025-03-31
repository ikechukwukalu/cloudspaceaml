<?php

return [
    'api_base_url' => env('AML_API_BASE_URL', 'http://localhost:8084'),
    'white_list' => ['name', 'birthDate', 'gender'],
    'optional_array_list' => ['address', 'phone', 'email', 'website'],
    'black_list' => ['ssn', 'passport', 'bank_account']
];
