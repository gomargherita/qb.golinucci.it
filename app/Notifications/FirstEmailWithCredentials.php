<?php

namespace App\Notifications;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FirstEmailWithCredentials extends Notification implements ShouldQueue
{
    use Queueable;

    protected $cart;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
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
            $user = $notifiable;
            $cart = $this->cart;
            Log::debug($user, ['notification']);
            Log::debug($cart, ['notification cart']);
            // generate a new password, hash and save it 
            $uncrypted_password = Str::random(10);
            $user->password = $uncrypted_password;
            $user->email_verified_at = now();
            $user->save();
            $url = route('front.product.renew', [$cart->product->id, $cart->cart_ref]);
            $renewal_date = $cart->renewal_date->format('d/m/Y');

            if($this->cart->product_id == 1){
                return (new MailMessage)
                    ->bcc($cc)
                    ->subject('Q&B Golinucci - Rinnova la tua polizza')
                    ->greeting("Gentile $user->name")
                    ->line("Il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                    ->line("Per procedere al rinnovo ed ottenere la nuova polizza online ti basta accedere al nostro sito con le seguenti credenziali:")
                    ->line("Email: $user->email")
                    ->line("Password: $uncrypted_password")
                    ->line("Clicca sul pulsante sottostante per iniziare la procedura di rinnovo.")
                    ->action('Rinnova la tua polizza', $url)
                    ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['sanitari@golinucci.it' => 'Q&B Golinucci']);
            }
            else{
                return (new MailMessage)
                    ->bcc($cc)
                    ->subject('Q&B Golinucci - Rinnova la tua polizza')
                    ->greeting("Gentile $user->name")
                    ->line("Il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                    ->line("Per procedere al rinnovo ed ottenere la nuova polizza online ti basta accedere al nostro sito con le seguenti credenziali:")
                    ->line("Email: $user->email")
                    ->line("Password: $uncrypted_password")
                    ->line("Clicca sul pulsante sottostante per iniziare la procedura di rinnovo.")
                    ->action('Rinnova la tua polizza', $url)
                    ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
            }
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
