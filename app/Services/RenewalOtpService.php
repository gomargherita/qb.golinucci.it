<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\RenewalOtp;
use App\Models\User;
use App\Notifications\RenewalOtpNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RenewalOtpService
{
    public function ttlMinutes(): int
    {
        return max(5, (int) config('renewal.otp_ttl_minutes', 60));
    }

    public function maxAttempts(): int
    {
        return max(3, (int) config('renewal.otp_max_attempts', 5));
    }

    public function resendSeconds(): int
    {
        return max(30, (int) config('renewal.otp_resend_seconds', 60));
    }

    /**
     * Invalidate previous open OTPs and send a new one.
     *
     * @return array{ok: bool, reason: string, retry_after?: int}
     */
    public function issue(Cart $cart, User $user): array
    {
        $latest = RenewalOtp::query()
            ->where('cart_id', $cart->id)
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if ($latest && $latest->created_at) {
            $elapsed = $latest->created_at->diffInSeconds(Carbon::now());
            if ($elapsed < $this->resendSeconds()) {
                return [
                    'ok' => false,
                    'reason' => 'throttle',
                    'retry_after' => $this->resendSeconds() - $elapsed,
                ];
            }
        }

        RenewalOtp::query()
            ->where('cart_id', $cart->id)
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => Carbon::now()]);

        $length = max(6, min(8, (int) config('renewal.otp_length', 6)));
        $plain = '';
        for ($i = 0; $i < $length; $i++) {
            $plain .= (string) random_int(0, 9);
        }

        RenewalOtp::create([
            'cart_id' => $cart->id,
            'user_id' => $user->id,
            'code_hash' => hash('sha256', $plain),
            'expires_at' => Carbon::now()->addMinutes($this->ttlMinutes()),
            'attempts' => 0,
        ]);

        $user->notify(new RenewalOtpNotification($plain, $this->ttlMinutes()));

        Log::info('renewal.otp.issued', [
            'cart_ref' => $cart->cart_ref,
            'user_id' => $user->id,
            'ttl_minutes' => $this->ttlMinutes(),
        ]);

        return ['ok' => true, 'reason' => 'sent'];
    }

    /**
     * @return array{ok: bool, reason: string}
     */
    public function verify(Cart $cart, User $user, string $code): array
    {
        $code = preg_replace('/\s+/', '', $code);
        if ($code === '' || !ctype_digit($code)) {
            return ['ok' => false, 'reason' => 'invalid_format'];
        }

        $otp = RenewalOtp::query()
            ->where('cart_id', $cart->id)
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (!$otp) {
            return ['ok' => false, 'reason' => 'missing'];
        }

        if (Carbon::now()->greaterThan($otp->expires_at)) {
            $otp->consumed_at = Carbon::now();
            $otp->save();

            return ['ok' => false, 'reason' => 'expired'];
        }

        if ((int) $otp->attempts >= $this->maxAttempts()) {
            return ['ok' => false, 'reason' => 'locked'];
        }

        $otp->attempts = (int) $otp->attempts + 1;
        $otp->save();

        if (!hash_equals($otp->code_hash, hash('sha256', $code))) {
            Log::notice('renewal.otp.failed', [
                'cart_ref' => $cart->cart_ref,
                'attempts' => $otp->attempts,
            ]);

            return ['ok' => false, 'reason' => 'mismatch'];
        }

        $otp->consumed_at = Carbon::now();
        $otp->save();

        return ['ok' => true, 'reason' => 'verified'];
    }
}
