<?php

namespace App\Console\Commands;
use App\Models\Reminder;
use App\Models\Cart;

use App\Models\Certificate;
use App\Notifications\RenewEmail;
use App\Notifications\ReminderEmail;
use App\Notifications\ReminderEmailPassed;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Reminders;

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
        $num_of_days_past = 60;

        $target_date = Carbon::now()->addDays($num_of_days)->format('Y-m-d');
        $target_date_past = Carbon::now()->subDays($num_of_days_past)->format('Y-m-d');

        $carts_query = Certificate::with(['cart', 'cart.user', 'cart.user.clientDetails', 'cart.product'])
            ->whereDate('end_validity_date', '<=', $target_date)
            ->whereDate('end_validity_date', '>=', $target_date_past) // Non più vecchi di 60 giorni

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
                $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            if ($reminder == null){
                                $reminder = Reminder::create([
                                    'cart_id' => $certificate->cart->id,
                                ]);
                            }
                $user->notify(new RenewEmail($certificate));
                $this->info(now() . ': renewal email sent to ' . $user->email);
                DB::statement('INSERT INTO renewals_email (old_cart_ref) VALUES ("'. $certificate->cart->cart_ref.'");');
            }
            else{
                $newCart = DB::select(DB::raw('SELECT new_cart_ref FROM renewals WHERE old_cart_ref = "'.$certificate->cart->cart_ref.'";'));
                Log::info($newCart);
                if(!empty($newCart)){
                    $newCart2 = Cart::where('cart_ref', $newCart[0]->new_cart_ref)->first();
                    Log::info($newCart2);
                }

                //si cerca di capire se ha rinnovato già
                if(empty($newCart) || ($newCart2 != null && $newCart2->product_step_id < 5)){


                $old_cart = Cart::where('cart_ref', $old_cart_ref[0]->old_cart_ref)->first();

                if($old_cart->product_id == 1 || $old_cart->product_id == 5){

                    //si cerca di capire se ha rinnovato già
                    #Log::debug($old_cart_ref[0]->old_cart_ref);
                    #Log::debug("ho appena printato l'old cart ref");

                    #Log::debug($newCartFound->id);
                    
                    $end = Carbon::createFromTimestamp(strtotime($certificate->end_validity_date));
                    $now = Carbon::now();
                    
                    switch($now->diffInDays($end, false)){
                        case 6:
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            if ($reminder == null){
                                $reminder = Reminder::create([
                                    'cart_id' => $certificate->cart->id,
                                ]);
                            }
                            if($reminder->one_week_before == false){
                            $user->notify(new ReminderEmail($certificate));
                            $this->info(now() . ': reminder 7 days email sent to ' . $user->email);
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            $reminder->one_week_before = true;
                            $reminder->save();
                        }
                            break;
                        case -7:
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            if ($reminder == null){
                                $reminder = Reminder::create([
                                    'cart_id' => $certificate->cart->id,
                                ]);
                            }
                            if($reminder->one_week_after == false){
                            $user->notify(new ReminderEmailPassed($certificate));
                            $this->info(now() . ': reminder -7 days email sent to ' . $user->email);
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            $reminder->one_week_after = true;
                            $reminder->save();
                        }
                        break;
                        case -14:
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            if ($reminder == null){
                                $reminder = Reminder::create([
                                    'cart_id' => $certificate->cart->id,
                                ]);
                            }
                            if($reminder->two_weeks_after == false){
                                $user->notify(new ReminderEmailPassed($certificate));
                                $this->info(now() . ': reminder -14 days email sent to ' . $user->email);
                                $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                                $reminder->two_weeks_after = true;
                                $reminder->save();
                            }
                            break;
                        case -26:
                            $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                            if ($reminder == null){
                                $reminder = Reminder::create([
                                    'cart_id' => $certificate->cart->id,
                                ]);
                            }
                            if($reminder->one_month_after == false){
                                $user->notify(new ReminderEmailPassed($certificate));
                                $this->info(now() . ': reminder -26 days email sent to ' . $user->email);
                                $reminder = Reminder::where('cart_id', $certificate->cart->id)->first();
                                $reminder->one_month_after = true;
                                $reminder->save();
                            }
                            break;
                    }
                    
                }

                    
                }
            }
                        
        }
        // $carts->each(function ($cart) {
            
        // });
        $this->info('#########################' . now() . ': END SCHEDULE');
    }
}
