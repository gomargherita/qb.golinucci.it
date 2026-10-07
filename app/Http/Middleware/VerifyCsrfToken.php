<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Yousign server-to-server webhooks (KAN-5). Auth via VerifyYousignWebhook when secret set.
        'yousign/*',
        // PayPal server-to-server webhooks (KAN-6).
        'paypal/webhook',
    ];
}

