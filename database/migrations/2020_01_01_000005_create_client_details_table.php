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
            $table->string('fiscal_code', 16)->nullable();
            $table->string('phone', 30)->nullable();
            $table->char('gender', 1)->nullable();
            $table->date('dob')->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('province', 2)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 5)->nullable();
            $table->boolean('confirmation')->default(false);
            $table->string('contact_ref', 25)->nullable();
            $table->string('version_ref', 25)->nullable();
            $table->boolean('with_accident')->default(false);
            $table->boolean('flag_prev_2022')->default(false);
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
