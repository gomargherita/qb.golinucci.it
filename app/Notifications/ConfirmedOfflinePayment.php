<?php

namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmedOfflinePayment extends Notification
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
        $productId = (int) $this->payment->cart->product->id;
        $url = route('front.product.buy', [$this->payment->cart->product->id, $this->payment->cart->cart_ref]);
        $from = ($productId === 1 || $productId === 5)
            ? ['sanitari@golinucci.it' => 'Q&B Golinucci']
            : ['bancari@golinucci.it' => 'Q&B Golinucci'];

        if (!in_array($productId, [1, 2, 3, 4, 5], true)) {
            return;
        }

        $mail = (new MailMessage)->from($from);
        $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'bank_transfer_confirmed', [
            'name' => $user->name ?? '',
            'url' => $url,
        ], [
            'action_text' => 'Clicca per procedere',
            'action_url' => $url,
        ]);
        if ($applied) {
            return $applied;
        }

        return $mail
            ->subject('Q&B Golinucci - Conferma pagamento tramite bonifico bancario')
            ->greeting("Gentile $user->name")
            ->line('Abbiamo appena confermato il pagamento del tuo prodotto.')
            ->line('Per procedere e firmare la tua polizza per procedere e firmare la polizza ti basta cliccare sul pulsante sottostante e seguire le istruzioni.')
            ->action('Clicca per procedere', $url)
            ->salutation('Grazie per aver scelto i prodotti Golinucci!');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
