<?php

return [
    'key' => env('YOUSIGN_KEY', ''),
    'api_url' => env('YOUSIGN_API_URL', 'https://api.yousign.app/v3/'),
    'app_url' => env('YOUSIGN_APP_URL', 'https://staging-app.yousign.com/'),
    'staging' => [
        'email' => env('YOUSIGN_STAGING_EMAIL', ''),
        'phone' => env('YOUSIGN_STAGING_CELLPHONE', ''),
    ],
    // Optional shared secret checked by VerifyYousignWebhook (header X-Yousign-Webhook-Secret).
    'webhook_secret' => env('YOUSIGN_WEBHOOK_SECRET', ''),
    'webhook_url' => env(
        'YOUSIGN_WEBHOOK_URL',
        rtrim((string) env('APP_URL', ''), '/') . '/yousign/procedure_finished'
    ),
    /**
     * KAN-31: Yousign automatic reminders (API reminder_settings).
     * Requires delivery_mode=email; with delivery_mode=none Yousign does not email the signer.
     */
    'delivery_mode' => env('YOUSIGN_DELIVERY_MODE', 'none'),
    'reminders' => [
        'enabled' => filter_var(env('YOUSIGN_REMINDERS', false), FILTER_VALIDATE_BOOLEAN),
        'interval_in_days' => (int) env('YOUSIGN_REMINDER_DAYS', 2),
        'occurrence' => (int) env('YOUSIGN_REMINDER_COUNT', 5),
    ],
];

