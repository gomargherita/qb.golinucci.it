<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlboEnrollmentYearToCarts extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedSmallInteger('albo_enrollment_year')->nullable()->after('retroactivity');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('albo_enrollment_year');
        });
    }
}
