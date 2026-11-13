<?php

namespace App\Notifications;

use App\Models\Reminder;
use App\Notifications\Concerns\IssuesRenewalAccess;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ReminderEmail extends Notification implements ShouldQueue
{
    use Queueable;
    use IssuesRenewalAccess;

    protected $cart;
    protected $end_validity_date;
    protected $reminder;

    public function __construct($cart)
    {
        $this->cart = $cart->cart;
        $this->end_validity_date = $cart->end_validity_date;
        $this->reminder = Reminder::where('cart_id', $cart->cart->id)->first();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            $cc = $this->renewalCcAddress();
            $user = $this->cart->user;
            $url = $this->renewalAccessUrl();
            $this->markRenewalEmailSent();
            $renewal_date = $this->end_validity_date
                ? $this->end_validity_date->format('d/m/Y')
                : now()->format('d/m/Y');

            $isMedici = in_array((int) $this->cart->product_id, [1, 5], true);
            $key = $isMedici ? 'renewal_invite_medici' : 'renewal_invite';
            $rendered = app(EmailTemplateService::class)->render($key, [
                'name' => $user->name ?? '',
                'renewal_date' => $renewal_date,
                'email' => $user->email ?? '',
                'url' => $url,
            ]);

            $mail = (new MailMessage)
                ->from([$cc => 'Q&B Golinucci']);

            if ($rendered) {
                return app(EmailTemplateService::class)->applyToMail($mail, $rendered, [
                    'action_text' => 'Rinnova la tua polizza',
                    'action_url' => $url,
                ]);
            }

            if ($isMedici) {
                return $mail
                    ->subject('Q&B Golinucci - Rinnova la tua polizza')
                    ->greeting("Gentile $user->name")
                    ->line("Ricordiamo che il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                    ->line("Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante qui sotto (link personale, senza password temporanea).")
                    ->line("Email di riferimento: $user->email")
                    ->action('Rinnova la tua polizza', $url)
                    ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!');
            }

            return $mail
                ->subject('Q&B Golinucci - Rinnova la tua polizza')
                ->greeting("Gentile $user->name")
                ->line("Ricordiamo che il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                ->line("Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante qui sotto (link personale, senza password temporanea).")
                ->line("Email di riferimento: $user->email")
                ->action('Rinnova la tua polizza', $url)
                ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                ->salutation('Grazie per aver scelto i prodotti Golinucci!');
        } catch (\Exception $e) {
            Log::error('ReminderEmail failed', [
                'cart_ref' => $this->cart->cart_ref ?? null,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
