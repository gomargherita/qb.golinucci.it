<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-44: bozza di emissione personalizzata DoPrint dal back-office.
 * Nessuna chiamata a Lloyd's in questa tabella.
 */
class CreateCustomDoprintEmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('custom_doprint_emissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 20)->default('draft');
            $table->string('coverholder_ref', 125);
            $table->string('contract_type', 125);
            $table->string('product_name', 125);
            $table->string('policy_text_version', 125);
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('fiscal_code', 16);
            $table->string('gender', 1);
            $table->date('dob')->nullable();
            $table->string('phone', 30);
            $table->string('address', 150);
            $table->string('city', 50);
            $table->string('province', 2);
            $table->string('country', 2);
            $table->string('postal_code', 5);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('premium_net', 10, 2);
            $table->decimal('premium_accessories', 10, 2);
            $table->decimal('premium_tax', 10, 2);
            $table->decimal('premium_gross', 10, 2);
            $table->string('policy_schedule_path', 255);
            $table->string('wording_path', 255);
            $table->string('attached_clauses_path', 255);
            $table->string('warranty_statement_path', 255);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_doprint_emissions');
    }
}
