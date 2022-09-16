<?php

namespace App\Console\Commands;

use App\Console\CommandWrapper;
use App\Imports\MedmalRenewalsImport;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

class MedImporter extends CommandWrapper
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'med:importer
    {--file=} Specify the name of the CSV file inside the .\storage\app\fixtures\ folder.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a specific CSV file into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // warn the developer if he's not in local environment
        if (!App::environment(['local', 'staging'])) {
            $this->warn('You are not in local environment.');
            if (!$this->confirm('Do you wish to continue?')) {
                return CommandWrapper::FAILURE;
            }
        }

        $csvFile = $this->option('file') && strlen($this->option('file')) ? $this->option('file') : null;

        // check if provided file has a CSV extension
        if (substr($csvFile, -4) !== '.csv') {
            $this->error('The provided file is not a CSV file.');
            return CommandWrapper::FAILURE;
        }

        // check if provided file exists
        if (!file_exists(storage_path('app/fixtures/' . $csvFile))) {
            $this->error('The provided file does not exist.');
            return CommandWrapper::FAILURE;
        }

        // collect the emails in the CSV file
        $emails = [];
        $index = 0;
        $csv = fopen(storage_path('app/fixtures/' . $csvFile), 'r');
        while (($row = fgetcsv($csv, 4096)) !== false) {
            $index ++;
            if ($index === 1) {
                continue;
            }
            $emails[] = $row[10];
        }

        // check if there are colliding Users with the same email in the DB
        $collidingUsers = User::whereIn('email', $emails)->get();
        if ($collidingUsers->count()) {
            $this->error('There are colliding Users with the same email in the DB. Cannot import. Please, fix every collision before importing.');
            foreach ($collidingUsers as $collidingUser) {
                $this->error("Colliding email: " . $collidingUser->email);
            }
            return CommandWrapper::FAILURE;
        }

        // import the CSV file
        try {
            Excel::import(new MedmalRenewalsImport, storage_path('app/fixtures/' . $csvFile));
        } catch (QueryException $e) {
            $this->error('[ERROR] Cannot import user: ' . $e->getMessage());
        }
    }
}
