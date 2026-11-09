<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * KAN-12: paypal_payment_cancel had no From seed (fallback = default mailer).
 * bank_transfer_confirmed stays empty on purpose: From is set in PHP by product.
 */
class EmailTemplatesPaypalCancelFrom extends Migration
{
    public function up()
    {
        DB::table('email_templates')->where('key', 'paypal_payment_cancel')->update([
            'from_email' => 'posta@golinucci.it',
            'from_name' => 'Q&B Golinucci',
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('email_templates')->where('key', 'paypal_payment_cancel')->update([
            'from_email' => null,
            'from_name' => null,
            'updated_at' => now(),
        ]);
    }
}
