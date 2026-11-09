<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EmailTemplatesBodyMediumtext extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE email_templates MODIFY body MEDIUMTEXT NOT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE email_templates MODIFY body TEXT NOT NULL');
    }
}
