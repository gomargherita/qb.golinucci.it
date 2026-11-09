<?php

namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaypalGatewayPaymentCancel extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $this->payment->cart->user;
        $bcc_address = config('mail-extra.mail_bcc_to');

        $mail = (new MailMessage)
            ->error()
            ->bcc($bcc_address);

        $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'paypal_payment_cancel', [
            'name' => $user->name ?? '',
            'cart_ref' => $this->payment->cart->cart_ref ?? '',
        ]);
        if ($applied) {
            return $applied;
        }

        return $mail
            ->subject('Q&B Golinucci - Annullamento pagamento tramite Paypal')
            ->greeting("Gentile $user->name")
            ->line('Attenzione: abbiamo riscontrato un errore e/o non è stato completato correttamente l\'ordine di pagamento con Paypal.')
            ->line('Ci siamo già attivati per gestire la problematica.')
            ->line('Ti contatteremo il prima possibile.')
            ->line('Grazie per l\'attenzione.')
            ->line('Ref cart: ' . $this->payment->cart->cart_ref);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
