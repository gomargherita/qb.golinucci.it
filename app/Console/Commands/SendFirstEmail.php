<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Notifications\FirstEmailWithCredentials;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendFirstEmail extends Command
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
        $num_of_days = 14;
        $target_date = Carbon::now()->addDays($num_of_days)->format('Y-m-d');

        $carts = Cart::with('user')
            ->whereDate('renewal_date', '<', $target_date)
            ->whereHas('user', function ($q) {
                // TODO aggiungere ignorare clienti con with_accident a true
                $q->whereNull('email_verified_at');
            })
            ->get();

        $carts->each(function ($cart) {
            $user = $cart->user;
            $user->notify(new FirstEmailWithCredentials($cart));
            $this->info(now() . ': first email sent to '. $cart->user->email);
        });
    }
}
