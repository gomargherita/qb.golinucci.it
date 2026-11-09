<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStripePaymentGateway extends Migration
{
    public function up()
    {
        $exists = DB::table('payment_gateways')->where('name', 'Stripe')->exists();
        if (!$exists) {
            DB::table('payment_gateways')->insert([
                'name' => 'Stripe',
                'public' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('payment_gateways')->where('name', 'Stripe')->delete();
    }
}
