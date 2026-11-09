<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-12: From address editable in BO. Empty = keep the Notification fallback.
 */
class EmailTemplatesFrom extends Migration
{
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('from_email', 255)->nullable()->after('bcc');
            $table->string('from_name', 125)->nullable()->after('from_email');
        });

        $defaults = [
            'renewal_otp' => ['posta@golinucci.it', 'Q&B Golinucci'],
            'final_documents_medici' => ['sanitari@golinucci.it', 'Q&B Golinucci'],
            'final_documents_bancari' => ['bancari@golinucci.it', 'Q&B Golinucci'],
            'renewal_invite_medici' => ['sanitari@golinucci.it', 'Q&B Golinucci'],
            'renewal_invite' => ['bancari@golinucci.it', 'Q&B Golinucci'],
            'bank_transfer_medici' => ['sanitari@golinucci.it', 'Q&B Golinucci'],
            'bank_transfer_er' => ['bancari@golinucci.it', 'Q&B Golinucci'],
            'bank_transfer_bancari' => ['bancari@golinucci.it', 'Q&B Golinucci'],
            'importo_libero' => ['info@golinucci.it', 'Golinucci SRL'],
            'importo_libero_conditions' => ['info@golinucci.it', 'Golinucci SRL'],
            'benefit' => ['info@golinucci.it', 'Golinucci SRL'],
            'ehealth' => ['info@golinucci.it', 'Golinucci SRL'],
            'salute_sport' => ['info@golinucci.it', 'Golinucci SRL'],
        ];

        foreach ($defaults as $key => $pair) {
            DB::table('email_templates')->where('key', $key)->update([
                'from_email' => $pair[0],
                'from_name' => $pair[1],
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['from_email', 'from_name']);
        });
    }
}
