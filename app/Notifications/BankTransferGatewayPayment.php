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
        return (new MailMessage)
            ->greeting('Grazie per aver scelto i prodotti Golinucci!')
            ->line('Per procedere con l\'acquisto della propria polizza occorre pagare la totalità del premio.')
            ->line('Ecco i dati per effettuare il bonifico:')
            ->line('Importo: ' . $this->payment->total_payable . '€')
            ->line('IBAN: IT17Y0200805366907500043291')
            ->line('Rag.Soc.: Golinucci Srl')
            ->line('Nota importante: la transazione (e la generazione della polizza) potrà
            essere portata avanti nel giorno lavorativo successivo a quello di data
            valuta di pagamento con bonifico bancario.
            Riceverai successivamente una email con un link e ulteriori indicazioni per
            procedere con la generazione della tua polizza.')
            ->action('Notification Action', url('/'));
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
