<?php

return [
    // TODO inserire altri dettagli che per ora vengono presi direttamente da .env
    'validator' => [
        'first_name' => env('YOUSIGN_VALIDATOR_FIRST_NAME', ''),
        'last_name' => env('YOUSIGN_VALIDATOR_LAST_NAME', ''),
        'email' => env('YOUSIGN_VALIDATOR_EMAIL', ''),
        'phone' => env('YOUSIGN_VALIDATOR_PHONE', ''),
    ]
];
