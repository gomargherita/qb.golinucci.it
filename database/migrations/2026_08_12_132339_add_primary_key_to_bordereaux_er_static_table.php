<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddPrimaryKeyToBordereauxErStaticTable extends Migration
{
    public function up()
    {
        Schema::table('bordereaux_er_static', function (Blueprint $table) {
            $table->id()->first();
        });
    }
    public function down()
    {
        Schema::table('bordereaux_er_static', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
