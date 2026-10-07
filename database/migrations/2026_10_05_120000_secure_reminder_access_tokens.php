<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-4: stop plaintext passwords on reminders.
 * - add hashed, expiring access tokens for renewal emails
 * - wipe historical reminders.pw values (never reusable as credentials)
 */
class SecureReminderAccessTokens extends Migration
{
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->string('access_token_hash', 64)->nullable()->after('pw');
            $table->timestamp('access_token_expires_at')->nullable()->after('access_token_hash');
        });

        // Controlled cleanup of historical plaintext passwords.
        DB::table('reminders')->whereNotNull('pw')->update(['pw' => null]);
    }

    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['access_token_hash', 'access_token_expires_at']);
        });
    }
}
