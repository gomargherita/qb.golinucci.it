<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFinalDocumentsEmailTemplates extends Migration
{
    public function up()
    {
        $now = now();

        DB::table('email_templates')->insert([
            [
                'key' => 'final_documents_medici',
                'name' => 'Polizza e documenti (Medici)',
                'subject' => 'Q&B Golinucci - Polizza e documenti',
                'body' => "Gentile {{name}},\n\nPuò trovare in allegato la sua polizza e tutta la documentazione correlata al prodotto che ha acquistato.\n\nGrazie per aver scelto i prodotti Golinucci!",
                'bcc' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'final_documents_bancari',
                'name' => 'Polizza e documenti (Bancari)',
                'subject' => 'Q&B Golinucci - Polizza e documenti',
                'body' => "Gentile {{name}},\n\nPuò trovare in allegato la sua polizza e tutta la documentazione correlata al prodotto che ha acquistato.\n\nLe ricordiamo ai sensi di legge che sull’acquisto effettuato è previsto un periodo di ripensamento (Periodo di Recesso) di 14 giorni dal momento d’acquisto. Qualora dovesse decidere che, per un qualsiasi motivo, questa assicurazione non soddisfa le sue esigenze, ha 14 giorni per procedere alla cancellazione ed ottenere un rimborso totale, segnalando il suo diritto di recesso tramite email al nostro indirizzo bancari@golinucci.it.\n\nAl verificarsi di un sinistro si metta in contatto con la Golinucci Srl entro 10 gg lavorativi dall’ammanco di cassa tramite una mail a sinistribancari@golinucci.it.\n\nVorremmo inoltre sottolineare che se ci fosse un cambiamento di circostanze o venisse a conoscenza di eventuali modifiche rilevanti che possono influenzare questa sua copertura assicurativa, deve avvisare immediatamente Golinucci srl.\n\nPossiamo cogliere questa occasione per ringraziare per la fiducia accordataci e se possiamo essere di ulteriore aiuto o assistenza su altre questioni assicurative, non esiti a contattarci.\n\nGolinucci Srl Viale Bovio 194 - 47521- Cesena\nTel: 054722351 Fax: 054725382\nE-mail: bancari@golinucci.it\nBroker di Assicurazioni Iscrizione RUI n. B000070946 del 19/03/2007 intermediario soggetto al controllo dell'Ivass\n\nGrazie per aver scelto i prodotti Golinucci!",
                'bcc' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down()
    {
        DB::table('email_templates')->whereIn('key', [
            'final_documents_medici',
            'final_documents_bancari',
        ])->delete();
    }
}
