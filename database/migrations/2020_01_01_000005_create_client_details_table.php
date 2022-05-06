<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('fiscal_code', 16);
            $table->string('phone', 30);
            $table->string('address', 150);
            $table->string('city', 50);
            $table->string('region', 50);
            $table->string('country', 2);
            $table->string('postal_code', 5);
            $table->boolean('confirmation')->default(false);
            $table->string('contact_ref', 25)->nullable();
            $table->string('version_ref', 25)->nullable();
            $table->boolean('with_accident')->default(0);
            $table->boolean('flag_prev_2022')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('client_details');
    }
}
