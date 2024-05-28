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
        
        if($this->payment->cart->product_id == 1 || $this->payment->cart->product_id == 5){
            return (new MailMessage)
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')

                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT69 D030 6923 9031 0000 0007 600 presso Banca Intesa San Paolo')
                ->line('Importo: ' . $this->payment->total_payable . '€')
                ->line('Causale: ' . $user->name . ' RC Professionale')
                ->line('Il giorno lavorativo successivo a quello di data valuta del pagamento invieremo via e-email un link e ulteriori informazioni per firmare la polizza da remoto.')
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['sanitari@golinucci.it' => 'Q&B Golinucci']);
        }
        else if($this->payment->cart->product_id == 2){
            return (new MailMessage)
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')

                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT31 Z062 3023 9000 0003 0575 079 presso Crédit agricole Cariparma')
                ->line('Importo: ' . $this->payment->total_payable . '€')
                ->line('Causale: ' . $user->name . ' RC Professionale')
                ->line('Il giorno lavorativo successivo a quello di data valuta del pagamento invieremo via e-email un link e ulteriori informazioni per firmare la polizza da remoto.')
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
        }
        else if($this->payment->cart->product_id == 4 || $this->payment->cart->product_id == 3){
            return (new MailMessage)
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')

                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT63 J032 7323 9000 0020 0102 910 presso BCC Credito Cooperativo romagnolo')
                ->line('Importo: ' . $this->payment->total_payable . '€')
                ->line('Causale: ' . $user->name . ' RC Professionale')
                ->line('Il giorno lavorativo successivo a quello di data valuta del pagamento invieremo via e-email un link e ulteriori informazioni per firmare la polizza da remoto.')
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
