<?php

return [
    'mode' => env('DOPRINT_MODE', 'sandbox'),
    'sandbox' => [
        'api_key' => env('DOPRINT_SANDBOX_API_KEY', ''),
        'wsdl' => env('DOPRINT_SANDBOX_WSDL', ''),
        'requestor_id' => env('DOPRINT_SANDBOX_REQUESTOR_ID', ''),
    ],
    'live' => [
        'api_key' => env('DOPRINT_LIVE_API_KEY', ''),
        'wsdl' => env('DOPRINT_LIVE_WSDL', ''),
        'requestor_id' => env('DOPRINT_LIVE_REQUESTOR_ID', ''),
    ]
];
