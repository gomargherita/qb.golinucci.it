<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Renewal email access token TTL (days)
    |--------------------------------------------------------------------------
    |
    | Opaque token embedded in renewal emails (hashed at rest on reminders).
    | Alone it is not enough: KAN-8 requires a short-lived email OTP.
    |
    */
    'access_token_ttl_days' => (int) env('RENEWAL_ACCESS_TOKEN_TTL_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Renewal OTP (KAN-8)
    |--------------------------------------------------------------------------
    */
    'otp_ttl_minutes' => (int) env('RENEWAL_OTP_TTL_MINUTES', 60),
    'otp_length' => (int) env('RENEWAL_OTP_LENGTH', 6),
    'otp_max_attempts' => (int) env('RENEWAL_OTP_MAX_ATTEMPTS', 5),
    'otp_resend_seconds' => (int) env('RENEWAL_OTP_RESEND_SECONDS', 60),

];
