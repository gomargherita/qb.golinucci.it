<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRenewalInviteEmailTemplates extends Migration
{
    public function up()
    {
        $now = now();

        $medici = "Gentile {{name}},\n\n"
            . "Il {{renewal_date}} scadrà la tua polizza di Responsabilita’ Civile Professionale.\n\n"
            . "NOVITA’ 2026:\n"
            . "Nelle condizioni di polizza è stato aggiunto l’art. 26:\n"
            . "Articolo 26 OBBLIGO FORMATIVO (ECM)\n"
            . "In caso di sinistro, se l'assicurato non è in regola con l'obbligo formativo di cui all'art. 38-bis DL 152/2021, convertito con L. 233/2021, l'assicurazione è operante a condizione che l'assicurato completi il credito formativo richiesto dalla legge, entro due mesi dalla data del sinistro.\n\n"
            . "La clausola offre quindi un margine temporale per sanare l’eventuale inadempimento formativo, considerato che a partire dal triennio 2023-2025 l’efficacia delle polizze assicurative di responsabilità civile viene subordinata al rispetto dell’obbligo formativo individuale: in concreto, perché la polizza sia efficace il professionista sanitario deve aver completato almeno il 70% dell’obbligo di formazione continua in medicina (ECM) dell’ultimo triennio utile.\n\n"
            . "Nessuna inefficacia della polizza, pertanto, se il professionista completa il credito formativo mancante entro il termine di due mesi dalla data della denuncia del sinistro.\n\n"
            . "Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante (link personale, senza password temporanea).\n\n"
            . "Email di riferimento: {{email}}\n\n"
            . "Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.\n\n"
            . "Grazie per aver scelto i prodotti Golinucci!";

        $generic = "Gentile {{name}},\n\n"
            . "Il {{renewal_date}} scadrà la tua polizza di Responsabilita’ Civile Professionale.\n\n"
            . "Per procedere al rinnovo ed ottenere la nuova polizza online usa il pulsante (link personale, senza password temporanea).\n\n"
            . "Email di riferimento: {{email}}\n\n"
            . "Se hai altre polizze in scadenza (tutela legale, infortuni, responsabilità civile vita privata, ecc..) riceverai un'altra separata email per il loro rinnovo.\n\n"
            . "Grazie per aver scelto i prodotti Golinucci!";

        DB::table('email_templates')->insert([
            [
                'key' => 'renewal_invite_medici',
                'name' => 'Invito rinnovo (Medici)',
                'subject' => 'Q&B Golinucci - Rinnova la tua polizza',
                'body' => $medici,
                'bcc' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'renewal_invite',
                'name' => 'Invito rinnovo',
                'subject' => 'Q&B Golinucci - Rinnova la tua polizza',
                'body' => $generic,
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
            'renewal_invite_medici',
            'renewal_invite',
        ])->delete();
    }
}
