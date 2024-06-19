<?php

namespace App\Notifications;

use App\Http\Controllers\SiteBuyProductController;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RenewalsEmail;
use Illuminate\Support\Facades\DB;



class ReminderEmailPassed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $cart;
    protected $end_validity_date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart)
    {
        $this->cart = $cart->cart;
        $this->end_validity_date = $cart->end_validity_date;

        //error_log($cart->cart->product);die;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            if($this->cart->product_id == 1){
                $cc = "sanitari@golinucci.it";
            }
            else{
                $cc = "bancari@golinucci.it";
            }
            $user = $this->cart->user;
            $uncrypted_password = Str::random(10);
            $user->password = $uncrypted_password;
            $user->email_verified_at = now();
            $user->save();
            $url = route('front.product.renew', [$this->cart->product_id, $this->cart->cart_ref]);
            DB::statement('INSERT INTO renewals_email (old_cart_ref) VALUES ("'. $this->cart->cart_ref.'");');
            $renewal_date = $this->end_validity_date->format('d/m/Y');

            return (new MailMessage)
                //->bcc($cc)
                ->subject('Q&B Golinucci - Rinnova la tua polizza')
                ->greeting("Gentile $user->name")
                ->line("Ricordiamo che il $renewal_date è scaduta la tua polizza di Responsabilita’ Civile Professionale.")
                ->line("Per procedere al rinnovo ed ottenere la nuova polizza online ti basta accedere al nostro sito con le seguenti credenziali:")
                ->line("Email: $user->email")
                ->line("Password: $uncrypted_password")
                ->line("Clicca sul pulsante sottostante per iniziare la procedura di rinnovo.")
                ->action('Rinnova la tua polizza', $url)
                ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from([$cc => 'Q&B Golinucci']);;
            } catch (\Exception $e) {
            abort(500);
            }
        }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
