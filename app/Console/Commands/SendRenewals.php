<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Notifications\RenewEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendRenewals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email with renewal info';

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
        // grab all carts with renewal_date < 25 days
        // da importare un cron tipo 30  2  1-30  jun  *  php artisan mail:first_send...
        $this->info('#########################' . now() . ': START SCHEDULE');
        $num_of_days = 30;
        $target_date = Carbon::now()->addDays($num_of_days)->format('Y-m-d');

        $carts_query = Certificate::with(['cart', 'cart.user', 'cart.user.clientDetails', 'cart.product'])
            ->whereDate('end_validity_date', '<=', $target_date)
            ->whereHas('cart.user.clientDetails', function ($q) {
                $q->where('with_accident', 0);
            });
        $carts = $carts_query->get();

        foreach ($carts as $certificate) {
            $user = $certificate->cart->user;

            $old_cart_ref = DB::select(DB::raw('SELECT old_cart_ref FROM renewals_email WHERE old_cart_ref = "'.$certificate->cart->cart_ref.'";'));
            if(empty($old_cart_ref)){
                //error_log($user);die;
                Log::debug($user, ['command']);
                Log::debug($certificate, ['command cart']);//error_log($certificate);die;
                $user->notify(new RenewEmail($certificate));
                $this->info(now() . ': renewal email sent to ' . $user->email);
                DB::statement('INSERT INTO renewals_email (old_cart_ref) VALUES ("'. $certificate->cart->cart_ref.'");');
            }
        }
        // $carts->each(function ($cart) {
            
        // });
        $this->info('#########################' . now() . ': END SCHEDULE');
    }
}
