<?php

use App\Models\ConditionalField;
use App\Models\ProductOffer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOfferConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_offer_conditions', function (Blueprint $table) {
            $table->foreignIdFor(ProductOffer::class)->constrained();
            $table->foreignIdFor(ConditionalField::class)->constrained();
            $table->text('selected_field_value');
            $table->timestamps();

            $table->index('product_offer_id');
            $table->index('conditional_field_id');

            $table->primary(['product_offer_id', 'conditional_field_id'], 'pocf_product_offer_id_conditional_field_id_primary')->index('pocf_po_id_cf_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_offer_conditions');
    }
}
