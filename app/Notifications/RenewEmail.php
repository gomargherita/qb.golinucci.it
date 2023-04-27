<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $cart;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart)
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
        $user = $this->cart->user;
        $url = route('front.product.renew', [$this->cart->product_id, $this->cart->cart_ref]);
        $renewal_date = $this->cart->renewal_date->format('d/m/Y');

        return (new MailMessage)
            ->subject('Q&B Golinucci - Rinnova la tua polizza')
            ->greeting("Salve $user->name")
            ->line("Il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
            ->line("Per procedere al rinnovo ti basta accedere al nostro sito con le tue credenziali.")
            ->line("Clicca sul pulsante sottostante per iniziare la procedura di rinnovo.")
            ->action('Rinnova la tua polizza', $url)
            ->line("Se ha altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverà’ altra separata email per il loro rinnovo.")
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
