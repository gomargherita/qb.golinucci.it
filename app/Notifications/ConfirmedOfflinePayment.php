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

        if($this->payment->cart->product->id == 1){
            return (new MailMessage)
                ->subject('Q&B Golinucci - Conferma pagamento tramite bonifico bancario')
                ->greeting("Gentile $user->name")
                ->line('Abbiamo appena confermato il pagamento del tuo prodotto.')
                ->line('Per procedere e firmare la tua polizza per procedere e firmare la polizza ti basta cliccare sul pulsante sottostante e seguire le istruzioni.')
                ->action('Clicca per procedere', $url)
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['sanitari@golinucci.it' => 'Q&B Golinucci']);
        }
        else if($this->payment->cart->product->id == 2 || $this->payment->cart->product->id == 3 || $this->payment->cart->product->id == 4){
            return (new MailMessage)
                ->subject('Q&B Golinucci - Conferma pagamento tramite bonifico bancario')
                ->greeting("Gentile $user->name")
                ->line('Abbiamo appena confermato il pagamento del tuo prodotto.')
                ->line('Per procedere e firmare la tua polizza per procedere e firmare la polizza ti basta cliccare sul pulsante sottostante e seguire le istruzioni.')
                ->action('Clicca per procedere', $url)
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
            
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
