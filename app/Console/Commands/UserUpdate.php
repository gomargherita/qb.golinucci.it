<?php

namespace App\Console\Commands;

use Illuminate\Database\Eloquent\Model;
use App\Console\CommandWrapper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserUpdate extends CommandWrapper
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update
    {--all : Whether to run the user update over ALL users. BE CAREFUL.}
    {--email=} The user\'s Email
    {--id=} The user\'s ID';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modify a user';

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
        $allUsers = $this->option('all') ? true : false;
        $userEmail = $this->option('email') && strlen($this->option('email')) ? $this->option('email') : null;
        $userId = $this->option('id') && strlen($this->option('id')) ? $this->option('id') : null;

        if ($allUsers) {
            $this->info("ATTENTION! You're going to perform update operations over ALL users!");
            $users = User::withTrashed()->where('id', '>', 1)->get();
        } else {
            if (!$userEmail && !$userId) {
                $this->error('You must specify either an email or an ID, or specify the --all option to work on all users.');
                return CommandWrapper::FAILURE;
            } elseif ($userEmail) {
                $users = User::withTrashed()->where(['email' => $userEmail])->get();
            } else {
                $users = User::withTrashed()->where(['id' => $userId])->get();
            }
        }

        if ($users->count() === 0) {
            $this->error('No users found');
            return CommandWrapper::FAILURE;
        }

        // optionally change the password
        $password = null;
        $a = $this->ask('Change password and Activate? (y/n)', 'n');
        if ($a === 'y') {
            $password = $this->secret('Password: ');

            // we don't need to guard Models when updating the password
            Model::unguard();

            $users->map(function (User $user) use ($password) {
                $this->info("Working with USER_ID={$user->id}, EMAIL={$user->email}");
    
                // tell the user if the current user is trashed
                if ($user->trashed()) {
                    $this->info("WARNING! Current user is TRASHED, we're going to restore it.");
                }
    
                if ($password) {
                    $user->password = Hash::make($password);
                    $user->email_verified_at = now();
                    $user->deleted_at = null;
                    $user->save();
                }
            });

            // reguard the models
            Model::reguard();
        }

        return CommandWrapper::SUCCESS;
    }
}
