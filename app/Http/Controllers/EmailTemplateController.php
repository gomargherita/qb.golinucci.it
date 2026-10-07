<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return Inertia::render('EmailTemplates/Index', [
            'templates' => EmailTemplate::query()
                ->orderBy('name')
                ->get()
                ->map(fn (EmailTemplate $t) => [
                    'id' => $t->id,
                    'key' => $t->key,
                    'name' => $t->name,
                    'subject' => $t->subject,
                    'is_active' => $t->is_active,
                    'updated_at' => optional($t->updated_at)->toDateTimeString(),
                ]),
        ]);
    }

    public function edit(EmailTemplate $email_template)
    {
        return Inertia::render('EmailTemplates/Edit', [
            'template' => [
                'id' => $email_template->id,
                'key' => $email_template->key,
                'name' => $email_template->name,
                'subject' => $email_template->subject,
                'body' => $email_template->body,
                'bcc' => $email_template->bcc,
                'is_active' => $email_template->is_active,
            ],
            'variables_help' => $this->variablesHelp($email_template->key),
        ]);
    }

    public function update(Request $request, EmailTemplate $email_template)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:125'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'bcc' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $email_template->fill($data);
        $email_template->updated_by = Auth::id();
        $email_template->save();

        return Redirect::route('emailTemplates.edit', $email_template)
            ->with('success', 'Template email aggiornato.');
    }

    private function variablesHelp(string $key): string
    {
        $map = [
            'renewal_otp' => '{{name}}, {{code}}, {{ttl_minutes}}',
        ];

        return $map[$key] ?? 'Usa variabili nel formato {{nome}}';
    }
}
