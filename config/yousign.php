<?php

return [
    'key' => env('YOUSIGN_KEY', ''),
    'api_url' => env('YOUSIGN_API_URL', 'https://api.yousign.app/v3/'),
    'app_url' => env('YOUSIGN_APP_URL', 'https://staging-app.yousign.com/'),
    'staging' => [
        'email' => env('YOUSIGN_STAGING_EMAIL', ''),
        'phone' => env('YOUSIGN_STAGING_CELLPHONE', ''),
    ]
];
