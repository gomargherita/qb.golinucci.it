<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MUPBancari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE product_offers ADD MUP varchar(255) DEFAULT null");

        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/1/mup-anno.pdf' WHERE id = 73");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/1/mup-7mesi.pdf' WHERE id = 74");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/1/mup-3mesi.pdf' WHERE id = 75");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/2/mup-anno.pdf' WHERE id = 76");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/2/mup-7mesi.pdf' WHERE id = 77");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/2/mup-3mesi.pdf' WHERE id = 78");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/3/mup-anno.pdf' WHERE id = 79");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/3/mup-7mesi.pdf' WHERE id = 80");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/4/mup-anno.pdf' WHERE id = 81");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/4/mup-7mesi.pdf' WHERE id = 82");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/5/mup-anno.pdf' WHERE id = 83");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/5/mup-7mesi.pdf' WHERE id = 84");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/6/mup-anno.pdf' WHERE id = 85");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/6/mup-7mesi.pdf' WHERE id = 86");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/7/mup-anno.pdf' WHERE id = 87");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/7/mup-7mesi.pdf' WHERE id = 88");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/8/mup-anno.pdf' WHERE id = 89");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/8/mup-7mesi.pdf' WHERE id = 90");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/9/mup-anno.pdf' WHERE id = 91");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/9/mup-7mesi.pdf' WHERE id = 92");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/10/mup-anno.pdf' WHERE id = 93");
        DB::statement("UPDATE product_offers SET MUP = '/storage/MUP/10/mup-7mesi.pdf' WHERE id = 94");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
