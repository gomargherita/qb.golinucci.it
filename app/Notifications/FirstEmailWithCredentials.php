<?php

namespace App\Notifications;

use App\Models\Cart;
use App\Notifications\Concerns\IssuesRenewalAccess;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Manual / BO “first renewal” email (ER/RI buttons). KAN-8: access link + OTP, no temp password.
 */
class FirstEmailWithCredentials extends Notification implements ShouldQueue
{
    use Queueable;
    use IssuesRenewalAccess;

    protected $cart;

    /** @var \App\Models\Reminder|null Set by {@see IssuesRenewalAccess::ensureReminder()} */
    protected $reminder;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            $cc = $this->renewalCcAddress();
            $user = $notifiable;
            $url = $this->renewalAccessUrl();
            $this->markRenewalEmailSent();

            $renewal_date = $this->cart->renewal_date
                ? $this->cart->renewal_date->format('d/m/Y')
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
                ->bcc($cc)
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
                    ->line("Il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                    ->line("NOVITA’ 2026:")
                    ->line("Nelle condizioni di polizza è stato aggiunto l’art. 26:")
                    ->line("Articolo 26 OBBLIGO FORMATIVO (ECM)")
                    ->line("In caso di sinistro, se l'assicurato non è in regola con l'obbligo formativo di cui all'art. 38-bis DL 152/2021, convertito con L. 233/2021, l'assicurazione è operante a condizione che l'assicurato completi il credito formativo richiesto dalla legge, entro due mesi dalla data del sinistro.")
                    ->line("La clausola offre quindi un margine temporale per sanare l’eventuale inadempimento formativo, considerato che a partire dal triennio 2023-2025 l’efficacia delle polizze assicurative di responsabilità civile viene subordinata al rispetto dell’obbligo formativo individuale: in concreto, perché la polizza sia efficace il professionista sanitario deve aver completato almeno il 70% dell’obbligo di formazione continua in medicina (ECM) dell’ultimo triennio utile.")
                    ->line("Nessuna inefficacia della polizza, pertanto, se il professionista completa il credito formativo mancante entro il termine di due mesi dalla data della denuncia del sinistro.")
                    ->line("Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante qui sotto (link personale, senza password temporanea).")
                    ->line("Email di riferimento: $user->email")
                    ->action('Rinnova la tua polizza', $url)
                    ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!');
            }

            return $mail
                ->subject('Q&B Golinucci - Rinnova la tua polizza')
                ->greeting("Gentile $user->name")
                ->line("Il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                ->line("Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante qui sotto (link personale, senza password temporanea).")
                ->line("Email di riferimento: $user->email")
                ->action('Rinnova la tua polizza', $url)
                ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                ->salutation('Grazie per aver scelto i prodotti Golinucci!');
        } catch (\Exception $e) {
            Log::error('FirstEmailWithCredentials failed', [
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
