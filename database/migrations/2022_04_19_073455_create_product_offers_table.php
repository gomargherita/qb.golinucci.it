<?php

use App\Models\Contract;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(Contract::class)->constrained();
            $table->string('name')->unique();
            $table->string('sku');
            $table->decimal('total_payable'); // quanto il cliente paga
            $table->decimal('admin_fee'); // commissione golinucci
            $table->decimal('gross_premium'); // ex price_with_tax; total_payable - admin_fee
            $table->decimal('tax'); // calcolato sul net_plus_access_premium
            $table->decimal('net_premium'); // netto base;
            $table->decimal('accessory'); // netto accessori
            $table->decimal('net_plus_access_premium'); // accessory + net premium; ex price
            $table->text('description');
            $table->integer('indemnity_limit');
            $table->tinyInteger('months')->unsigned();
            $table->boolean('active');
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
        Schema::dropIfExists('product_offers');
    }
}
