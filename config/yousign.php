<?php

return [
    'key' => env('YOUSIGN_KEY', ''),
    'api_url' => env('YOUSIGN_API_URL', 'https://staging-api.yousign.com/'),
    'app_url' => env('YOUSIGN_APP_URL', 'https://staging-app.yousign.com/'),
    'validator' => [
        'first_name' => env('YOUSIGN_VALIDATOR_FIRST_NAME', ''),
        'last_name' => env('YOUSIGN_VALIDATOR_LAST_NAME', ''),
        'email' => env('YOUSIGN_VALIDATOR_EMAIL', ''),
        'phone' => env('YOUSIGN_VALIDATOR_PHONE', ''),
    ]
];
