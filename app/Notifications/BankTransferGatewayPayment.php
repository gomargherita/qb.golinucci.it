<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BankTransferGatewayPayment extends Notification
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
        
        return (new MailMessage)
            ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
            ->greeting("Gentile $user->name")
            ->line('Per procedere con l\'acquisto della tua polizza occorre pagare la totalità del premio.')
            ->line('Ecco i dati per effettuare il bonifico:')
            ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT69 D030 6923 9031 0000 0007 600 presso Banca Intesa San Paolo')
            ->line('Importo: ' . $this->payment->total_payable . '€')
            ->line('Causale: ' . $user->name . ' RC Professionale')
            ->line('Nota importante: l\'emissione, e la relativa copertura, potrà
            essere portata avanti nel giorno lavorativo successivo a quello di data
            valuta di pagamento con bonifico bancario.
            Riceverai successivamente una email con un link e ulteriori indicazioni per
            procedere con la generazione della tua polizza.')
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
