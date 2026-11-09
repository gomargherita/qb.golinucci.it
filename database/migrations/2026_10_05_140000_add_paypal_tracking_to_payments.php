<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-6: track PayPal order lifecycle without a full attempts ledger yet.
 * payment_events keeps an append-only audit of capture/webhook/reconcile.
 */
class AddPaypalTrackingToPayments extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paypal_status', 64)->nullable()->after('transaction_ref');
            $table->timestamp('last_capture_at')->nullable()->after('paypal_status');
        });

        Schema::create('payment_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('payment_id')->constrained('payments');
            $table->string('source', 32); // browser|webhook|reconcile|system
            $table->string('event', 64);
            $table->string('paypal_order_id', 125)->nullable();
            $table->string('paypal_status', 64)->nullable();
            $table->string('debug_id', 64)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['payment_id', 'created_at']);
            $table->index('paypal_order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_events');

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paypal_status', 'last_capture_at']);
        });
    }
}
