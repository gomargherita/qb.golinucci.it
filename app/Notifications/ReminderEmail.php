<?php

namespace App\Notifications;

use App\Models\Reminder;
use App\Notifications\Concerns\IssuesRenewalAccess;
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
            $renewal_date = $this->end_validity_date->format('d/m/Y');

            if ($this->cart->product_id == 1) {
                return (new MailMessage)
                    //->bcc($cc)
                    ->subject('Q&B Golinucci - Rinnova la tua polizza')
                    ->greeting("Gentile $user->name")
                    ->line("Ricordiamo che il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
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
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from([$cc => 'Q&B Golinucci']);
            }

            return (new MailMessage)
                //->bcc($cc)
                ->subject('Q&B Golinucci - Rinnova la tua polizza')
                ->greeting("Gentile $user->name")
                ->line("Ricordiamo che il $renewal_date scadrà la tua polizza di Responsabilita’ Civile Professionale.")
                ->line("Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante qui sotto (link personale, senza password temporanea).")
                ->line("Email di riferimento: $user->email")
                ->action('Rinnova la tua polizza', $url)
                ->line("Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.")
                ->salutation('Grazie per aver scelto i prodotti Golinucci!')->from([$cc => 'Q&B Golinucci']);
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
