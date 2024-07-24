<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SalutesportBuyers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE importo_libero_buyers ADD profession varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD fiscalcode varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD dob timestamp DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD phone varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD via varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD citta varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD cap varchar(255) DEFAULT null");
        DB::statement("ALTER TABLE importo_libero_buyers ADD provincia varchar(255) DEFAULT null");
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
