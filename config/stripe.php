<?php

return [
    'enabled' => env('STRIPE_ENABLED', false),
    'secret' => env('STRIPE_SECRET', ''),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
    'currency' => env('STRIPE_CURRENCY', 'eur'),
];
