<?php

use App\Models\Cart;
use App\Models\Contract;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(ProductOffer::class)->constrained();
            $table->foreignIdFor(Contract::class)->nullable()->constrained();
            $table->string('certificate_no', 20)->nullable()->unique();
            $table->string('doprint_token', 100)->nullable()->unique();
            $table->string('doprint_current_step', 20)->nullable();
            $table->string('doprint_certificate_no', 20)->nullable()->unique();
            $table->date('start_validity_date')->nullable();
            $table->date('end_validity_date')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('certificates');
    }
}
