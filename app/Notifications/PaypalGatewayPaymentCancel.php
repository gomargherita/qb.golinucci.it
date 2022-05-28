<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaypalGatewayPaymentCancel extends Notification implements ShouldQueue
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
        $bcc_address = config('mail-extra.mail_bcc_to');

        return (new MailMessage)
            ->error()
            ->subject('Q&B Golinucci - Annullamento pagamento tramite Paypal')
            ->greeting("Salve $user->name")
            ->line('Attenzione: abbiamo riscontrato un errore e/o non è stato completato correttamente l\'ordine di pagamento con Paypal.')
            ->line('Ci siamo già attivati per gestire la problematica.')
            ->line('Ti contatteremo il prima possibile.')
            ->line('Grazie per l\'attenzione.')
            ->line('Ref cart: ' . $this->payment->cart->cart_ref)
            ->bcc($bcc_address);
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
