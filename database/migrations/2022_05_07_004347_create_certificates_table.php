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
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Cart::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(ProductOffer::class)->nullable()->constrained();
            $table->foreignIdFor(Contract::class)->nullable()->constrained();
            $table->string('certificate_no', 20)->unique();
            $table->datetime('renewal_date')->nullable();
            $table->decimal('total_payable')->nullable(); // quanto il cliente paga
            $table->decimal('admin_fee')->nullable(); // commissione golinucci
            $table->decimal('gross_premium')->nullable(); // ex price_with_tax; total_payable - admin_fee
            $table->decimal('tax')->nullable(); // calcolato sul net_plus_access_premium
            $table->decimal('net_premium')->nullable(); // netto base;
            $table->decimal('accessory')->nullable(); // netto accessori
            $table->decimal('net_plus_access_premium')->nullable(); // accessory + net premium; ex price
            $table->integer('indemnity_limit')->nullable();
            $table->integer('indemnity_limit_patrimonial')->nullable();
            $table->boolean('active')->default(true);
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
