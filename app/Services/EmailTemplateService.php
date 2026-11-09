<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class EmailTemplateService
{
    public const DESIGN_MARKER = '<!--qb-unlayer-design:';

    /**
     * @param  array<string, scalar|null>  $vars
     * @return array{subject: string, body: string, bcc: ?string, from_email: ?string, from_name: ?string}|null
     */
    public function render(string $key, array $vars = []): ?array
    {
        $template = EmailTemplate::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            Log::warning('email_template.missing', ['key' => $key]);
            return null;
        }

        return [
            'subject' => $this->interpolate($template->subject, $vars),
            'body' => $this->interpolate($this->htmlBody($template->body), $vars),
            'bcc' => $template->bcc,
            'from_email' => $template->from_email,
            'from_name' => $template->from_name,
        ];
    }

    /**
     * Strip the Unlayer design JSON comment; keep the HTML (or plain text) used when sending.
     */
    public function htmlBody(?string $body): string
    {
        $body = (string) $body;
        if (strpos($body, self::DESIGN_MARKER) !== 0) {
            return $body;
        }

        $end = strpos($body, '-->');
        if ($end === false) {
            return $body;
        }

        return ltrim(substr($body, $end + 3));
    }

    public function isHtml(string $body): bool
    {
        return (bool) preg_match('/<(html|head|body|table|div|p|br|h[1-6])\b/i', $body);
    }

    /**
     * @param  array<string, scalar|null>  $vars
     * @param  array{action_text?: string, action_url?: string, keep_from?: bool}  $options
     */
    public function applyIfPresent(MailMessage $mail, string $key, array $vars = [], array $options = []): ?MailMessage
    {
        $rendered = $this->render($key, $vars);

        return $rendered ? $this->applyToMail($mail, $rendered, $options) : null;
    }

    /**
     * Apply a rendered BO template onto an existing MailMessage (cc/attach stay on $mail).
     * From is taken from the template when set; pass keep_from to keep the Notification From (PEC).
     *
     * @param  array{subject: string, body: string, bcc: ?string, from_email?: ?string, from_name?: ?string}  $rendered
     * @param  array{action_text?: string, action_url?: string, keep_from?: bool}  $options
     */
    public function applyToMail(MailMessage $mail, array $rendered, array $options = []): MailMessage
    {
        $body = $rendered['body'];

        $mail->subject($rendered['subject'])
            ->greeting('')
            ->salutation('');

        $keepFrom = !empty($options['keep_from']) || (($mail->mailer ?? null) === 'pec');
        if (!$keepFrom && !empty($rendered['from_email'])) {
            $mail->from($rendered['from_email'], $rendered['from_name'] ?: 'Q&B Golinucci');
        }

        if ($this->isHtml($body)) {
            $mail->view([
                'html' => 'emails.bo-html',
                'text' => 'emails.bo-text',
            ], ['body' => $body]);
        } else {
            foreach (preg_split("/\r\n|\n|\r/", $body) as $line) {
                $mail->line(trim($line) === '' ? ' ' : $line);
            }
            if (!empty($options['action_text']) && !empty($options['action_url'])) {
                $mail->action($options['action_text'], $options['action_url']);
            }
        }

        if (!empty($rendered['bcc'])) {
            $mail->bcc($rendered['bcc']);
        }

        return $mail;
    }

    /**
     * @param  array<string, scalar|null>  $vars
     */
    public function interpolate(string $text, array $vars): string
    {
        $replace = [];
        foreach ($vars as $name => $value) {
            $replace['{{' . $name . '}}'] = (string) ($value ?? '');
        }

        return strtr($text, $replace);
    }
}
