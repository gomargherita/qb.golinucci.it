<?php

use App\Models\ConditionalField;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_conditions', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(ConditionalField::class)->constrained();
            $table->timestamps();

            $table->index('product_id');
            $table->index('conditional_field_id');

            $table->primary(['product_id', 'conditional_field_id'], 'pcf_product_id_conditional_field_id_primary')->index('pcf_p_id_c_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_conditions');
    }
}
