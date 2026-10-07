<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;

class EmailTemplateService
{
    /**
     * @param  array<string, scalar|null>  $vars
     * @return array{subject: string, body: string, bcc: ?string}|null
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
            'body' => $this->interpolate($template->body, $vars),
            'bcc' => $template->bcc,
        ];
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
