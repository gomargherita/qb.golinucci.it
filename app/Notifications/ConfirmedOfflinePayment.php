<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ConfirmedOfflinePayment extends Notification
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;

        if (App::environment(['local', 'staging'])) {
        }
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
        $user = $this->payment->cart->user;
        $url = route('front.product.buy', [$this->payment->cart->product->id, $this->payment->cart->cart_ref]);

        return (new MailMessage)
            ->subject('Q&B Golinucci - Conferma pagamento tramite bonifico bancario')
            ->greeting("Salve $user->name")
            ->line('Abbiamo appena confermato il pagamento del tuo prodotto.')
            ->line('Per procedere e concludere la procedura di emissione della tua polizza ti basta cliccare sul pulsante sottostante e seguire le istruzioni.')
            ->action('Clicca per procedere', $url)
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
