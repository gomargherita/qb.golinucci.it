<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-8: short-lived email OTP after renewal access-link validation.
 */
class CreateRenewalOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('renewal_otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('user_id')->constrained('users');
            $table->string('code_hash', 64);
            $table->timestamp('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();

            $table->index(['cart_id', 'user_id', 'consumed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('renewal_otps');
    }
}
