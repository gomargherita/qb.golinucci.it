<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductOffer;
use App\Services\EmailTemplateService;

class FinalEmailWithAttachments extends Notification
{
    use Queueable;

    protected $cart;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart)
    {
        $this->cart = $cart;
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
        $user = $this->cart->user;
        $storage_path = Storage::disk('private')->getAdapter()->getPathPrefix();
        $certificate_folder = 'certificates/user_' . $user->id . '/cart_' . $this->cart->id;
        $file_path = $storage_path . $certificate_folder;

        if($this->cart->product->product_type_id == 1){
            $mail = (new MailMessage)
                ->bcc('sanitari@golinucci.it')
                ->from(['sanitari@golinucci.it' => 'Q&B Golinucci']);
            $mail = $this->applyCopy($mail, 'final_documents_medici', $user, function ($mail) use ($user) {
                return $mail
                    ->subject('Q&B Golinucci - Polizza e documenti')
                    ->greeting("Gentile $user->name")
                    ->line('Può trovare in allegato la sua polizza e tutta la documentazione correlata al prodotto che ha acquistato.')
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!');
            });

            foreach (['certificate-final.pdf', 'declarations.pdf', 'payment-certificate.pdf'] as $name) {
                $full = $file_path . '/' . $name;
                if (is_file($full)) {
                    $mail->attach($full);
                }
            }

            return $mail;
        }
        else if($this->cart->product->product_type_id == 2){
            switch($this->cart->product->id){
                case 2:
                    $poid = $this->cart->product_offer_id;
                    $offer = ProductOffer::find($poid);
                    $mup = $offer->MUP;
                    $mup = str_replace("/storage", "", $mup);
                    $wording = Storage::disk('public')->getAdapter()->getPathPrefix()."/contracts/CGA-RC-BANCARI-CONVENZIONE-2026-FCER-GOLINUCCI.pdf";
                    $mup = Storage::disk('public')->getAdapter()->getPathPrefix().$mup;

                    $mail = (new MailMessage)
                        ->mailer('pec')
                        ->cc('bancari@golinucci.it')
                        ->from(['bancari@pec.golinucci.it' => 'Q&B Golinucci']);
                    $mail = $this->applyCopy($mail, 'final_documents_bancari', $user, function ($mail) use ($user) {
                        return $mail
                            ->subject('Q&B Golinucci - Polizza e documenti')
                            ->greeting("Gentile $user->name")
                            ->line('Può trovare in allegato la sua polizza e tutta la documentazione correlata al prodotto che ha acquistato.')
                            ->line('Le ricordiamo ai sensi di legge  che sull’acquisto effettuato è previsto un periodo di ripensamento (Periodo di Recesso ) di 14 giorni dal momento d’acquisto. Qualora dovesse decidere che, per un qualsiasi motivo, questa assicurazione non soddisfa le sue esigenze, ha 14 giorni per procedere alla cancellazione ed ottenere un rimborso totale, segnalando il suo diritto di recesso tramite email al nostro indirizzo bancari@golinucci.it.')
                            ->line('Al verificarsi di un sinistro si metta in contatto con la Golinucci Srl entro 10 gg lavorativi dall’ammanco di cassa tramite una mail a sinistribancari@golinucci.it.')
                            ->line('Vorremmo inoltre sottolineare che se ci fosse un cambiamento di circostanze o venisse a conoscenza di eventuali modifiche rilevanti che possono influenzare questa sua copertura assicurativa , deve avvisare immediatamente Golinucci srl.')
                            ->line('Possiamo cogliere questa occasione per ringraziare per la fiducia accordataci e se possiamo essere di ulteriore aiuto o assistenza su altre questioni assicurative, non esiti a contattarci.')
                            ->line("Golinucci Srl Viale Bovio 194 - 47521- Cesena
                        Tel: 054722351 Fax: 054725382
                        E-mail: bancari@golinucci.it
                        Broker di Assicurazioni Iscrizione RUI n. B000070946 del 19/03/2007 intermediario soggetto al controllo dell'Ivass")
                            ->salutation('Grazie per aver scelto i prodotti Golinucci!');
                    });

                    return $mail
                        ->attach($file_path . '/certificate-final.pdf')
                        ->attach($file_path . '/declarations.pdf')
                        ->attach(Storage::disk('public')->getAdapter()->getPathPrefix()."/contracts/RC-Rischi-diversi_DIP_e_DIPAGG.pdf")
                        ->attach($mup)
                        ->attach($wording);

                    break;
                case 3:
                    $wording = Storage::disk('public')->getAdapter()->getPathPrefix()."/contracts/italia-sardegna.pdf";
                    break;
                case 4:
                    $wording = Storage::disk('public')->getAdapter()->getPathPrefix()."/contracts/C.G.A.-CONV.-GOLINUCCI.pdf";
                    break;
            }


            $mail = (new MailMessage)
                ->bcc('bancari@golinucci.it')
                ->from(['bancari@golinucci.it' => 'Q&B Golinucci']);
            $mail = $this->applyCopy($mail, 'final_documents_bancari', $user, function ($mail) use ($user) {
                return $mail
                    ->subject('Q&B Golinucci - Polizza e documenti')
                    ->greeting("Gentile $user->name")
                    ->line('Può trovare in allegato la sua polizza e tutta la documentazione correlata al prodotto che ha acquistato.')
                    ->line('Le ricordiamo ai sensi di legge  che sull’acquisto effettuato è previsto un periodo di ripensamento (Periodo di Recesso ) di 14 giorni dal momento d’acquisto. Qualora dovesse decidere che, per un qualsiasi motivo, questa assicurazione non soddisfa le sue esigenze, ha 14 giorni per procedere alla cancellazione ed ottenere un rimborso totale, segnalando il suo diritto di recesso tramite email al nostro indirizzo bancari@golinucci.it.')
                    ->line('Al verificarsi di un sinistro si metta in contatto con la Golinucci Srl entro 10 gg lavorativi dall’ammanco di cassa tramite una mail a sinistribancari@golinucci.it.')
                    ->line('Vorremmo inoltre sottolineare che se ci fosse un cambiamento di circostanze o venisse a conoscenza di eventuali modifiche rilevanti che possono influenzare questa sua copertura assicurativa , deve avvisare immediatamente Golinucci srl.')
                    ->line('Possiamo cogliere questa occasione per ringraziare per la fiducia accordataci e se possiamo essere di ulteriore aiuto o assistenza su altre questioni assicurative, non esiti a contattarci.')
                    ->line("Golinucci Srl Viale Bovio 194 - 47521- Cesena
                Tel: 054722351 Fax: 054725382
                E-mail: bancari@golinucci.it
                Broker di Assicurazioni Iscrizione RUI n. B000070946 del 19/03/2007 intermediario soggetto al controllo dell'Ivass")
                    ->salutation('Grazie per aver scelto i prodotti Golinucci!');
            });

            return $mail
                ->attach($file_path . '/certificate-final.pdf')
                ->attach($wording);
        }
    }

    private function applyCopy(MailMessage $mail, string $key, $user, \Closure $fallback, array $options = []): MailMessage
    {
        $rendered = app(EmailTemplateService::class)->render($key, [
            'name' => $user->name ?? '',
        ]);

        if (!$rendered) {
            return $fallback($mail);
        }

        return app(EmailTemplateService::class)->applyToMail($mail, $rendered, $options);
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
