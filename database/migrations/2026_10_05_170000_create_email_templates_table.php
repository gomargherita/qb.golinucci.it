<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * KAN-12: editable email templates (BO), starting with renewal OTP as pilot.
 */
class CreateEmailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 64)->unique();
            $table->string('name', 125);
            $table->string('subject', 255);
            $table->text('body');
            $table->string('bcc', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        DB::table('email_templates')->insert([
            'key' => 'renewal_otp',
            'name' => 'Codice OTP rinnovo',
            'subject' => 'Q&B Golinucci - Codice di accesso al rinnovo',
            'body' => "Gentile {{name}},\n\nPer continuare il rinnovo della polizza inserisci questo codice:\n\nCodice: {{code}}\n\nIl codice scade tra {{ttl_minutes}} minuti.\n\nSe non hai richiesto tu questo accesso, ignora questa email.\n\nGrazie — Q&B Golinucci",
            'bcc' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}
