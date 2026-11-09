<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOperationalEmailTemplates extends Migration
{
    public function up()
    {
        $now = now();

        $rows = [
            [
                'key' => 'bank_transfer_medici',
                'name' => 'Bonifico (Medici / Giovani medici)',
                'subject' => 'Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario',
                'body' => "Gentile {{name}},\n\n"
                    . "Per procedere all'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:\n\n"
                    . "Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT69 D030 6923 9031 0000 0007 600 presso Banca Intesa San Paolo\n"
                    . "Importo: {{amount}}€\n"
                    . "Causale: {{causale}}\n"
                    . "Il giorno lavorativo successivo a quello di data valuta del pagamento invieremo via e-email un link e ulteriori informazioni per firmare la polizza da remoto.\n\n"
                    . "Grazie per aver scelto i prodotti Golinucci!",
            ],
            [
                'key' => 'bank_transfer_er',
                'name' => 'Bonifico (ER Bancari)',
                'subject' => 'Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario',
                'body' => "Gentile {{name}},\n\n"
                    . "Per procedere all'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:\n\n"
                    . "Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT31 Z062 3023 9000 0003 0575 079 presso Crédit agricole Cariparma\n"
                    . "Importo: {{amount}}€\n"
                    . "Causale: {{causale}}\n"
                    . "In seguito riceverà via e-mail il certificato di polizza.\n\n"
                    . "Grazie per aver scelto i prodotti Golinucci!",
            ],
            [
                'key' => 'bank_transfer_bancari',
                'name' => 'Bonifico (RI / altri Bancari)',
                'subject' => 'Q&B Golinucci - Procedi al pagamento tramite Bonifico Bancario',
                'body' => "Gentile {{name}},\n\n"
                    . "Per procedere all'acquisto della polizza mediante bonifico bancario può effettuare il pagamento alle seguenti coordinate:\n\n"
                    . "Bonifico Bancario su c/c intestato a Golinucci srl IBAN IT63 J032 7323 9000 0020 0102 910 presso BCC Credito Cooperativo romagnolo\n"
                    . "Importo: {{amount}}€\n"
                    . "Causale: {{causale}}\n"
                    . "In seguito riceverà via e-mail il certificato di polizza.\n\n"
                    . "Grazie per aver scelto i prodotti Golinucci!",
            ],
            [
                'key' => 'bank_transfer_confirmed',
                'name' => 'Conferma pagamento bonifico',
                'subject' => 'Q&B Golinucci - Conferma pagamento tramite bonifico bancario',
                'body' => "Gentile {{name}},\n\n"
                    . "Abbiamo appena confermato il pagamento del tuo prodotto.\n"
                    . "Per procedere e firmare la tua polizza ti basta cliccare sul pulsante (link personale) e seguire le istruzioni.\n\n"
                    . "Grazie per aver scelto i prodotti Golinucci!",
            ],
            [
                'key' => 'paypal_payment_cancel',
                'name' => 'Annullamento pagamento PayPal',
                'subject' => 'Q&B Golinucci - Annullamento pagamento tramite Paypal',
                'body' => "Gentile {{name}},\n\n"
                    . "Attenzione: abbiamo riscontrato un errore e/o non è stato completato correttamente l'ordine di pagamento con Paypal.\n"
                    . "Ci siamo già attivati per gestire la problematica.\n"
                    . "Ti contatteremo il prima possibile.\n"
                    . "Grazie per l'attenzione.\n"
                    . "Ref cart: {{cart_ref}}",
            ],
            [
                'key' => 'importo_libero',
                'name' => 'Importo libero — conferma acquisto',
                'subject' => 'Conferma acquisto',
                'body' => "Gentile Cliente {{name}} {{surname}},\n\n"
                    . "grazie per aver acquistato il prodotto, tramite GOLINUCCI srl.\n\n"
                    . "Riepilogo informazioni:\n"
                    . "Nome: {{name}}\n"
                    . "Cognome: {{surname}}\n"
                    . "Causale: {{causale}}\n"
                    . "Costo complessivo pagato: €{{amount}}\n\n"
                    . "A seguire la documentazione di sua spettanza.\n\n"
                    . "Distinti Saluti\nGolinucci srl\nViale Bovio 194 - 47521 CESENA\ntel.0547 22351 - info@golinucci.it",
            ],
            [
                'key' => 'importo_libero_conditions',
                'name' => 'Importo libero — conferma adesione',
                'subject' => 'Conferma adesione programma assicurativo',
                'body' => "Gentile Cliente {{name}} {{surname}},\n\n"
                    . "grazie per aver acquistato il programma assicurativo, tramite GOLINUCCI srl.\n\n"
                    . "Riepilogo informazioni:\n"
                    . "Nome: {{name}}\n"
                    . "Cognome: {{surname}}\n"
                    . "Professione: {{profession}}\n"
                    . "Codice Fiscale: {{fiscalcode}}\n"
                    . "Telefono: {{phone}}\n"
                    . "Data di nascita: {{dob}}\n"
                    . "Via: {{via}}\n"
                    . "Città: {{citta}}\n"
                    . "Cap: {{cap}}\n"
                    . "Provincia: {{provincia}}\n"
                    . "Costo complessivo pagato: €{{amount}}\n\n"
                    . "A seguire la documentazione di sua spettanza.\n\n"
                    . "Distinti Saluti\nGolinucci srl\nViale Bovio 194 - 47521 CESENA\ntel.0547 22351 - info@golinucci.it",
            ],
            [
                'key' => 'benefit',
                'name' => 'Sottoscrizione Benefit',
                'subject' => 'Sottoscrizione Benefit',
                'body' => "Gentile Cliente {{name}} {{surname}},\n\n"
                    . "grazie per aver acquistato il programma assicurativo BENEFIT, tramite GOLINUCCI srl.\n\n"
                    . "Riepilogo informazioni:\n"
                    . "Nome: {{name}}\n"
                    . "Cognome: {{surname}}\n"
                    . "Codice Fiscale: {{fiscalcode}}\n"
                    . "Via: {{via}}\n"
                    . "Città: {{citta}}\n"
                    . "Cap: {{cap}}\n"
                    . "Provincia: {{provincia}}\n\n"
                    . "In allegato le informazioni per attivare il programma Benefit.\n\n"
                    . "Distinti Saluti\nGolinucci srl\nViale Bovio 194 - 47521 CESENA\ntel.0547 22351 - info@golinucci.it",
            ],
            [
                'key' => 'ehealth',
                'name' => 'Carta Virtualhospital.blue',
                'subject' => 'CARTA VIRTUALHOSPITAL.BLUE',
                'body' => "Gentile Cliente {{name}} {{surname}},\n\n"
                    . "grazie per aver acquistato l’innovativo servizio VIRTUAL HOSPITAL di Blue Health Center, al costo di 90€, tramite GOLINUCCI srl.\n"
                    . "Le comunichiamo il suo PIN per l’accesso a www.virtualhospital.blue\n\n"
                    . "{{code}}\n\n"
                    . "La durata dell’abbonamento e’ di 12 mesi.\n\n"
                    . "Scadenza del termine per l’attivazione della presente Carta Virtualhospital.blue: 20/12/2024\n"
                    . "Le Condizioni di utilizzo della Carta Virtualhospital.blue sono riportate in fondo a questa email.\n\n"
                    . "Distinti Saluti\nGolinucci srl\nViale Bovio 194 - 47521 CESENA\ntel.0547 22351 - info@golinucci.it",
            ],
            [
                'key' => 'salute_sport',
                'name' => 'Conferma adesione Sport&Salute',
                'subject' => 'Conferma adesione programma assicurativo + Sport&Salute',
                'body' => "Gentile Cliente {{name}} {{surname}},\n\n"
                    . "grazie per aver acquistato il programma assicurativo + Sport&Salute, tramite GOLINUCCI srl.\n\n"
                    . "Riepilogo informazioni:\n"
                    . "Nome: {{name}}\n"
                    . "Cognome: {{surname}}\n"
                    . "Professione: {{profession}}\n"
                    . "Codice Fiscale: {{fiscalcode}}\n"
                    . "Telefono: {{phone}}\n"
                    . "Data di nascita: {{dob}}\n"
                    . "Via: {{via}}\n"
                    . "Città: {{citta}}\n"
                    . "Cap: {{cap}}\n"
                    . "Provincia: {{provincia}}\n\n"
                    . "Importo assicurato:\n"
                    . "Morte da Infortuni: {{morte_da_infortuni}}\n"
                    . "Invalidità Permanente da Infortuni: {{invalidita_permanente_infortuni}}, franchigia {{franchigia1}}\n"
                    . "Rimborso spese mediche da Infortuni: {{rimborso_infortuni}}, franchigia {{franchigia2}}\n\n"
                    . "Incluso nella polizza: Programma BENEFIT.\n"
                    . "Costo complessivo pagato: €{{amount}}\n"
                    . "Effetto copertura: ore 24 data emissione polizza, il primo giorno lavorativo dopo il pagamento online.\n\n"
                    . "A seguire la documentazione di sua spettanza e informazioni per attivare il programma Benefit.\n\n"
                    . "Distinti Saluti\nGolinucci srl\nViale Bovio 194 - 47521 CESENA\ntel.0547 22351 - info@golinucci.it",
            ],
        ];

        foreach ($rows as $row) {
            DB::table('email_templates')->insert(array_merge($row, [
                'bcc' => null,
                'is_active' => $row['key'] !== 'ehealth',
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down()
    {
        DB::table('email_templates')->whereIn('key', [
            'bank_transfer_medici',
            'bank_transfer_er',
            'bank_transfer_bancari',
            'bank_transfer_confirmed',
            'paypal_payment_cancel',
            'importo_libero',
            'importo_libero_conditions',
            'benefit',
            'ehealth',
            'salute_sport',
        ])->delete();
    }
}
