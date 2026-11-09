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
                'from_email' => $email_template->from_email,
                'from_name' => $email_template->from_name,
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
            'from_email' => ['nullable', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:125'],
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
            'final_documents_medici' => '{{name}}',
            'final_documents_bancari' => '{{name}}',
            'renewal_invite_medici' => '{{name}}, {{renewal_date}}, {{email}}, {{url}} — nel builder: pulsante con Merge Tag {{url}}',
            'renewal_invite' => '{{name}}, {{renewal_date}}, {{email}}, {{url}} — nel builder: pulsante con Merge Tag {{url}}',
            'bank_transfer_medici' => '{{name}}, {{amount}}, {{causale}}',
            'bank_transfer_er' => '{{name}}, {{amount}}, {{causale}}',
            'bank_transfer_bancari' => '{{name}}, {{amount}}, {{causale}}',
            'bank_transfer_confirmed' => '{{name}}, {{url}} — nel builder: pulsante con Merge Tag {{url}}',
            'paypal_payment_cancel' => '{{name}}, {{cart_ref}}',
            'importo_libero' => '{{name}}, {{surname}}, {{causale}}, {{amount}}',
            'importo_libero_conditions' => '{{name}}, {{surname}}, {{profession}}, {{fiscalcode}}, {{phone}}, {{dob}}, {{via}}, {{citta}}, {{cap}}, {{provincia}}, {{amount}}',
            'benefit' => '{{name}}, {{surname}}, {{fiscalcode}}, {{via}}, {{citta}}, {{cap}}, {{provincia}}',
            'ehealth' => '{{name}}, {{surname}}, {{code}}',
            'salute_sport' => '{{name}}, {{surname}}, {{profession}}, {{fiscalcode}}, {{phone}}, {{dob}}, {{via}}, {{citta}}, {{cap}}, {{provincia}}, {{morte_da_infortuni}}, {{invalidita_permanente_infortuni}}, {{franchigia1}}, {{rimborso_infortuni}}, {{franchigia2}}, {{amount}}',
        ];

        return $map[$key] ?? 'Usa variabili nel formato {{nome}}';
    }
}
