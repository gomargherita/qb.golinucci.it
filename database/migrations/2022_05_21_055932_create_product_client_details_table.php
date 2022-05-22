<?php

use App\Models\ClientDetail;
use App\Models\ConditionalField;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_client_details', function (Blueprint $table) {
            $table->foreignIdFor(ClientDetail::class)->constrained();
            $table->foreignIdFor(ConditionalField::class)->constrained();
            $table->text('selected_field_value')->nullable();
            $table->timestamps();

            $table->index('client_detail_id');
            $table->index('conditional_field_id');

            $table->primary(['client_detail_id', 'conditional_field_id'], 'cdcf_primary')->index('cdcf_cd_id_cf_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_client_details');
    }
}
