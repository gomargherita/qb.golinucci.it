<?php

namespace App\Notifications\Concerns;

use App\Models\Reminder;
use App\Services\RenewalAccessService;
use Illuminate\Support\Facades\DB;

trait IssuesRenewalAccess
{
    protected function ensureReminder(): Reminder
    {
        if ($this->reminder) {
            return $this->reminder;
        }

        $this->reminder = Reminder::firstOrCreate([
            'cart_id' => $this->cart->id,
        ]);

        return $this->reminder;
    }

    protected function renewalAccessUrl(): string
    {
        $reminder = $this->ensureReminder();

        return app(RenewalAccessService::class)->issueAccessUrl($reminder, $this->cart);
    }

    protected function renewalCcAddress(): string
    {
        if ($this->cart->product_id == 1 || $this->cart->product_id == 5) {
            return 'sanitari@golinucci.it';
        }

        return 'bancari@golinucci.it';
    }

    protected function markRenewalEmailSent(): void
    {
        $exists = DB::table('renewals_email')
            ->where('old_cart_ref', $this->cart->cart_ref)
            ->exists();

        if (!$exists) {
            DB::table('renewals_email')->insert([
                'old_cart_ref' => $this->cart->cart_ref,
            ]);
        }
    }
}
