<?php

namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var string */
    private $code;

    /** @var int */
    private $ttlMinutes;

    public function __construct(string $code, int $ttlMinutes)
    {
        $this->code = $code;
        $this->ttlMinutes = $ttlMinutes;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $rendered = app(EmailTemplateService::class)->render('renewal_otp', [
            'name' => $notifiable->name ?? '',
            'code' => $this->code,
            'ttl_minutes' => $this->ttlMinutes,
        ]);

        if ($rendered) {
            $mail = (new MailMessage)
                ->subject($rendered['subject'])
                ->greeting('')
                ->salutation('');

            foreach (preg_split("/\r\n|\n|\r/", $rendered['body']) as $line) {
                if (trim($line) === '') {
                    $mail->line(' ');
                } else {
                    $mail->line($line);
                }
            }

            if (!empty($rendered['bcc'])) {
                $mail->bcc($rendered['bcc']);
            }

            return $mail;
        }

        // Fallback if template missing / inactive
        return (new MailMessage)
            ->subject('Q&B Golinucci - Codice di accesso al rinnovo')
            ->greeting('Gentile ' . ($notifiable->name ?? ''))
            ->line('Per continuare il rinnovo della polizza inserisci questo codice:')
            ->line('Codice: ' . $this->code)
            ->line('Il codice scade tra ' . $this->ttlMinutes . ' minuti.')
            ->line('Se non hai richiesto tu questo accesso, ignora questa email.')
            ->salutation('Grazie — Q&B Golinucci');
    }
}
