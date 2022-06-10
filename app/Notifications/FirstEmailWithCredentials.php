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
        $user = $notifiable;
        $cart = $this->cart;
        Log::debug($user, ['notification']);
        Log::debug($cart, ['notification cart']);
        // generate a new password, hash it and save
        $uncrypted_password = Str::random(10);
        $user->password = $uncrypted_password;
        $user->email_verified_at = now();
        $user->save();
        $url = route('front.product.buy', [$cart->product->id, $cart->cart_ref]);
        $renewal_date = $cart->renewal_date->format('d/m/Y');

        return (new MailMessage)
            ->subject('Q&B Golinucci - Rinnova la tua polizza')
            ->greeting("Salve $user->name")
            ->line("Il $renewal_date scadrà la tua polizza.")
            ->line("Per procedere al rinnovo ti basta accedere al nostro sito con le seguenti credenziali:")
            ->line("Email: $user->email")
            ->line("Password: $uncrypted_password")
            ->line("Clicca sul pulsante sottostante per iniziare la procedura di rinnovo.")
            ->action('Rinnova la tua polizza', $url)
            ->salutation('Grazie per aver scelto i prodotti Golinucci!');
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
