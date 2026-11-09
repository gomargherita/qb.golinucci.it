<?php

namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BankTransferGatewayPayment extends Notification
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
        $productId = (int) $this->payment->cart->product_id;
        $amount = $this->payment->total_payable;
        $causale = ($user->name ?? '') . ' RC Professionale';
        $vars = [
            'name' => $user->name ?? '',
            'amount' => $amount,
            'causale' => $causale,
        ];

        if ($productId === 1 || $productId === 5) {
            $from = ['sanitari@golinucci.it' => 'Q&B Golinucci'];
            $mail = (new MailMessage)->from($from);
            $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'bank_transfer_medici', $vars);
            if ($applied) {
                return $applied;
            }

            return $mail
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')
                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT69 D030 6923 9031 0000 0007 600 presso Banca Intesa San Paolo')
                ->line('Importo: ' . $amount . '€')
                ->line('Causale: ' . $causale)
                ->line('Il giorno lavorativo successivo a quello di data valuta del pagamento invieremo via e-email un link e ulteriori informazioni per firmare la polizza da remoto.')
                ->salutation('Grazie per aver scelto i prodotti Golinucci!');
        }

        if ($productId === 2) {
            $mail = (new MailMessage)->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
            $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'bank_transfer_er', $vars);
            if ($applied) {
                return $applied;
            }

            return $mail
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')
                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT31 Z062 3023 9000 0003 0575 079 presso Crédit agricole Cariparma')
                ->line('Importo: ' . $amount . '€')
                ->line('Causale: ' . $causale)
                ->line('In seguito riceverà via e-mail il certificato di polizza.')
                ->salutation('Grazie per aver scelto i prodotti Golinucci!');
        }

        if ($productId === 4 || $productId === 3) {
            $mail = (new MailMessage)->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
            $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'bank_transfer_bancari', $vars);
            if ($applied) {
                return $applied;
            }

            return $mail
                ->subject('Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario')
                ->greeting("Gentile $user->name")
                ->line('Per procedere all\'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:')
                ->line('Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT63 J032 7323 9000 0020 0102 910 presso BCC Credito Cooperativo romagnolo')
                ->line('Importo: ' . $amount . '€')
                ->line('Causale: ' . $causale)
                ->line('In seguito riceverà via e-mail il certificato di polizza.')
                ->salutation('Grazie per aver scelto i prodotti Golinucci!');
        }
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
