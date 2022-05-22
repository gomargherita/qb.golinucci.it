<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionalFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditional_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique();
            $table->string('field_label')->unique();
            $table->string('field_type');
            $table->json('field_value');
            $table->boolean('only_client_details')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditional_fields');
    }
}
