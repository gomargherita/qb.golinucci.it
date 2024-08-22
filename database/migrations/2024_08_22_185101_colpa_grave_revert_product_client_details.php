<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ConditionalField;

class ColpaGraveRevertProductClientDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $field = ConditionalField::find(1);

        $field->field_value = "";
        $field->save();
        $field->field_value = '{"1":"Dirigente Medico","2":"Medico Convenzionato","3":"Medico specialista in formazione","4":"Personale sanitario","5":"Dirigente Sanitario non Medico","6":"Dirigente Infermieristico"}';

        $field->save();
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
