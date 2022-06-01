<?php

namespace Database\Seeders;

use App\Imports\MedmalRenewalsImport;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class UserMedGiugno2022Seeder extends Seeder
{
    const NEW_LINE = "\r\n";
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            if (App::environment(['local', 'staging'])) {
                Excel::import(new MedmalRenewalsImport, storage_path('app/public/' . 'database-giugno-settembre-test.csv'));
            } else {
                Excel::import(new MedmalRenewalsImport, storage_path('app/public/' . 'database-giugno-settembre.csv'));
            }
        } catch (QueryException $e) {
            $this->output('- [ERROR] Cannot import user: ' . $e->getMessage());
        }
    }

    protected function output($message, $new_line = true)
    {
        echo $message . ($new_line ? self::NEW_LINE : '');
    }
}
