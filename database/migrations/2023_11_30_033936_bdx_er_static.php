<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BdxErStatic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bordereaux_er_static', function (Blueprint $table) {
            $table->integer("product_offer_id");
            $table->string("I");
            $table->string("J");
            $table->string("K");
            $table->string("L");
            $table->string("M");
            $table->string("N");
            $table->string("P"); 
        });


        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (73,'35','5','0','0','40','25','65')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (76,'45','20','0','0','65','40','105')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (79,'0','20','0','0','20','30','50')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (81,'45','20','20','0','85','50','135')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (83,'0','30','30','0','60','50','110')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (85,'45','45','50','0','140','50','190')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (87,'0','30','0','45','75','50','125')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (89,'0','50','0','70','120','50','170')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (91,'0','30','300','0','330','50','380')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (93,'35','5','0','35','75','50','125')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (74,'23','3','0','0','26','14','40')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (77,'36','13','0','0','49','14','63')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (80,'0','15','0','0','15','14','29')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (82,'30','22','11','0','63','14','77')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (84,'0','32','16','0','48','14','62')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (86,'44','34','18','0','96','14','110')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (88,'0','25','0','34','59','14','73')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (90,'0','27','0','54','81','14','95')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (92,'0','27','159','0','186','14','200')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (94,'23','3','0','33','59','14','73')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (75,'17','2','0','0','19','9','28')");
        DB::statement("INSERT INTO bordereaux_er_static (product_offer_id,I,J,K,L,M,N,P) VALUES (78,'19','15','0','0','34','9','43')");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            }
}
