<?php

namespace App\Console\Commands;

use App\Console\CommandWrapper;
use App\Models\Cart;
use App\Notifications\FirstEmailWithCredentials;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendFirstEmail extends CommandWrapper
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:first_send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send first email with account details';

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
        // grab all carts with renewal_date < 14days
        // da importare un cron tipo 30  2  1-30  jun  *  php artisan mail:first_send...
        $this->info('#########################' . now() . ': START SCHEDULE');
        $num_of_days = 9;
        $target_date = Carbon::now()->addDays($num_of_days)->format('Y-m-d');

        $carts_query = Cart::with(['user', 'user.clientDetails'])
            // ->join('users', 'carts.user_id', '=',  'users.id')
            // ->join('client_details', 'carts.user_id', '=',  'client_details.user_id')
            // ->whereDate('carts.renewal_date', '<=', $target_date)
            // ->whereNull('users.email_verified_at')
            // ->where('client_details.with_accident', 0);

            ->whereDate('renewal_date', '<=', $target_date)
            ->whereHas('user', function ($q) {
                $q->whereNull('email_verified_at');
            })
            ->whereHas('user.clientDetails', function ($q) {
                $q->where('with_accident', 0);
            });

        $carts = $carts_query->get();
        foreach ($carts as $cart) {
            $user = $cart->user;
            Log::debug($user, ['command']);
            Log::debug($cart, ['command cart']);
            $user->notify(new FirstEmailWithCredentials($cart));
            $this->info(now() . ': first email sent to ' . $user->email);
        }
        // $carts->each(function ($cart) {
            
        // });
        $this->info('#########################' . now() . ': END SCHEDULE');
    }
}
