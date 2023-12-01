<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class BdxErDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bordereaux_er_info', function (Blueprint $table) {
            $table->integer("product_offer_id");
            $table->string("combination");
            $table->string("cassieri");
            $table->string("rc_patrimoniale");
            $table->string("spese_legali");
            $table->string("length_months");
            $table->string("f7");
            $table->string("Q"); 
            $table->string("R"); 
            $table->string("S"); 
            $table->string("T"); 
            $table->string("U"); 
            $table->string("V"); 
            $table->string("W"); 
            $table->string("X"); 
            $table->string("Y"); 
            $table->string("Z"); 
            $table->string("AA");
            $table->string("AB");
            $table->string("AC");
            $table->string("AD");
            $table->string("AE");
            $table->string("AF");
            $table->string("AG");
            $table->string("AH");
            $table->string("AI");
            $table->string("AJ");
            $table->string("AK");
            $table->string("AL");
            $table->string("AM");
            $table->string("AN");
            $table->string("AO");
            $table->string("AP");
            $table->string("AQ");
            $table->string("AR");
            $table->string("AS");
    });


DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (73,'1','€ 6.000','€ 20.000 ','€ 5.000 ','12','','20000','5000','1','6000','20000','5000','0','0','13579','13580','ITFINC08653','32539JX','','28.63','6.37','21','4.09','0.91','21','0.00','0.00','30','0.00','0.00','20','25','','65','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (76,'2','€ 6.000','€ 60.000 ','€ 15.000 ','12','','60000','15000','2','6000','60000','15000','0','0','13579','13580','ITFINC08654','32539JX','','36.81','8.19','21','16.36','3.64','21','0.00','0.00','30','0.00','0.00','20','40','','105','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (79,'3','------- ','€ 60.000 ','€ 15.000 ','12','','60000','15000','3','0','60000','15000','0','0','13579','13580','ITFINC08655','32539JX','','0.00','0.00','21','16.36','3.64','21','0.00','0.00','30','0.00','0.00','20','30','','50','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (81,'4','€ 6.000','€ 150.000 ','€ 37.500','12','','150000','37500','4','6000','150000','37500','90000','22500','13579','13580','ITFINC08656','32539JX','','36.81','8.19','21','16.36','3.64','21','16.36','3.64','30','0.00','0.00','20','50','','135','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (83,'5','-------','€ 150.000','€ 37.500 ','12','','150000','37500','5','0','150000','37500','90000','22500','13579','13580','ITFINC08657','32539JX','','0.00','0.00','21','24.54','5.46','21','24.54','5.46','30','0.00','0.00','20','50','','110','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (85,'6','€ 6.000','€ 500.000','€ 125.000 ','12','','500000','125000','6','6000','500000','125000','440000','110000','13579','13580','ITFINC08658','32539JX','','36.81','8.19','21','36.81','8.19','21','40.90','9.10','30','0.00','0.00','20','50','','190','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (87,'7','-------','€ 500.000 ','€ 125.000 ','12','','500000','125000','7','0','500000','125000','440000','110000','13579','13580','ITFINC08659','32539JX','','0.00','0.00','21','24.54','5.46','21','0.00','0.00','30','36.81','8.19','20','50','','125','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (89,'8','-------','€ 1.000.000','€ 250.000','12','','1000000','250000','8','0','1000000','250000','940000','235000','13579','13580','ITFINC08660','32539JX','','0.00','0.00','21','40.90','9.10','21','0.00','0.00','30','57.26','12.74','20','50','','170','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (91,'9','-------','€ 2.000.000','€ 500.000 ','12','','2000000','500000','9','0','2000000','500000','1940000','485000','13579','13580','ITFINC08661','32539JX','','0.00','0.00','21','24.54','5.46','21','245.40','54.60','30','0.00','0.00','20','50','','380','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (93,'10','€ 10.000','€ 20.000','€ 5.000 ','12','','20000','5000','10','10000','20000','5000','0','0','13579','13580','ITFINC08662','32539JX','','28.63','6.37','21','4.09','0.91','21','0.00','0.00','30','28.63','6.37','20','50','','125','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (74,'1','€ 6.000','€ 20.000 ','€ 5.000 ','7','','20000','5000','1','6000','20000','5000','0','0','13579','13580','ITFINC08663','32539JX','','18.81','4.19','21','2.45','0.55','21','0.00','0.00','30','0.00','0.00','20','14','','40','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (77,'2','€ 6.000','€ 60.000 ','€ 15.000 ','7','','60000','15000','2','6000','60000','15000','0','0','13579','13580','ITFINC08664','32539JX','','29.45','6.55','21','10.63','2.37','21','0.00','0.00','30','0.00','0.00','20','14','','63','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (80,'3','------- ','€ 60.000 ','€ 15.000 ','7','','60000','15000','3','0','60000','15000','0','0','13579','13580','ITFINC08665','32539JX','','0.00','0.00','21','12.27','2.73','21','0.00','0.00','30','0.00','0.00','20','14','','29','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (82,'4','€ 6.000','€ 150.000 ','€ 37.500 ','7','','150000','37500','4','6000','150000','37500','90000','22500','13579','13580','ITFINC08666','32539JX','','24.54','5.46','21','18.00','4.00','21','9.00','2.00','30','0.00','0.00','20','14','','77','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (84,'5','-------','€ 150.000','€ 37.500 ','7','','150000','37500','5','0','150000','37500','90000','22500','13579','13580','ITFINC08667','32539JX','','0.00','0.00','21','26.18','5.82','21','13.09','2.91','30','0.00','0.00','20','14','','62','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (86,'6','€ 6.000','€ 500.000','€ 125.000 ','7','','500000','125000','6','6000','500000','125000','440000','110000','13579','13580','ITFINC08668','32539JX','','35.99','8.01','21','27.81','6.19','21','14.72','3.28','30','0.00','0.00','20','14','','110','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (88,'7','-------','€ 500.000 ','€ 125.000 ','7','','500000','125000','7','0','500000','125000','440000','110000','13579','13580','ITFINC08669','32539JX','','0.00','0.00','21','20.45','4.55','21','0.00','0.00','30','27.81','6.19','20','14','','73','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (90,'8','-------','€ 1.000.000','€ 250.000','7','','1000000','250000','8','0','1000000','250000','940000','235000','13579','13580','ITFINC08670','32539JX','','0.00','0.00','21','22.09','4.91','21','0.00','0.00','30','44.17','9.83','20','14','','95','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (92,'9','-------','€ 2.000.000','€ 500.000 ','7','','2000000','500000','9','0','2000000','500000','1940000','485000','13579','13580','ITFINC08671','32539JX','','0.00','0.00','21','22.09','4.91','21','130.06','28.94','30','0.00','0.00','20','14','','200','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (94,'10','€ 10.000','€ 20.000','€ 5.000 ','7','','20000','5000','10','10000','20000','5000','0','0','13579','13580','ITFINC08672','32539JX','','18.81','4.19','21','2.45','0.55','21','0.00','0.00','30','26.99','6.01','20','14','','73','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (75,'1','€ 6.000','€ 20.000 ','€ 5.000 ','3','','20000','5000','1','6000','20000','5000','0','0','13579','13580','ITFINC08673','32539JX','','13.91','3.09','21','1.64','0.36','21','0.00','0.00','30','0.00','0.00','20','9','','28','27556400')");
DB::statement("INSERT INTO bordereaux_er_info (product_offer_id,combination,cassieri,rc_patrimoniale,spese_legali,length_months,f7,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,`AS`) VALUES (78,'2','€ 6.000','€ 60.000 ','€ 15.000 ','3','','60000','15000','2','6000','60000','15000','0','0','13579','13580','ITFINC08674','32539JX','','15.54','3.46','21','12.27','2.73','21','0.00','0.00','30','0.00','0.00','20','9','','43','27556400')");
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
