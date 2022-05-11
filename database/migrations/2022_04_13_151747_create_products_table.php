<?php

use App\Models\Account;
use App\Models\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained();
            $table->foreignIdFor(ProductType::class)->constrained();
            $table->string('name')->unique();
            $table->text('description');
            $table->text('detailed_description');
            $table->text('pre_declarations');
            $table->text('post_declarations');
            $table->text('conditions');
            $table->text('payments_description');
            $table->string('hex_color');
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
        Schema::dropIfExists('products');
    }
}
