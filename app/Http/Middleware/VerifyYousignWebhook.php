<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Optional shared-secret gate for Yousign webhooks.
 * If YOUSIGN_WEBHOOK_SECRET is empty, only logs and allows (CSRF already excluded).
 */
class VerifyYousignWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $secret = (string) config('yousign.webhook_secret', '');
        if ($secret === '') {
            return $next($request);
        }

        $provided = $request->header('X-Yousign-Webhook-Secret')
            ?: $request->header('X-Webhook-Secret')
            ?: (string) $request->query('webhook_secret', '');

        if (!hash_equals($secret, (string) $provided)) {
            Log::warning('yousign.webhook.unauthorized', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
