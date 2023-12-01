<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DettagliProdotti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('products')
            ->where('id', 2)
            ->update([
                'name' => 'Emilia Romagna - R.C. Patrimoniale del Bancario',
                'conditions' => '<tbody>
                <tr>
                <td style="width: 85%;"></td>
                </tr>
                <tr>
                <td>
                <p><br>
                <br>
                </p><p><a href="/wordings/estratto-condizioni-2023.pdf" target="_blank">Informativa Contrattuale</a> messa a disposizione dell’Assicurato da parte di First Cisl Emilia Romagna</p><br>
                </td>
                </tr>
                <tr>
                <td>
                <div class="greyrounded">
                <p><span class="small"><sup>(1)</sup></span> : Compreso carico e scarico bancomat<br>
                <span class="small"><sup>(2)</sup></span> : <b>Retroattività minima 10 anni</b>. Per rinnovi senza interruzioni la garanzia vale dalla data di prima adesione (comunque non anteriore al 01/01/2004), con massimale del primo certificato.
                <br> <b>Per nuove adesioni</b> o in presenza di interruzioni di copertura la retroattività è di 10 anni dall\'ultima adesione.<br>
                <span class="small"><sup>(3)</sup></span> : Comprende <b>copertura di violazione privacy secondo il Nuovo Regolamento GDPR Eu 679/16</b><br>
                <span class="small"><sup>(4)</sup></span> : Spese legali correlate al sinistro R.C. patrimoniale risarcibile a norma di polizza.<br>
                <span class="small"><sup>(5)</sup></span> : e comunque non oltre il 31/12/2023
                </p>
                <p></p>
                </div>
                </td>
                </tr>
                <tr>
                <td>
                <br>
                <h4 class="maroon">FRANCHIGIE E SCOPERTO</h4>
                <b>Polizza Cassieri</b>:<br>
                <ol>
                <li>Azzeramento franchigia sul primo ammanco di cassa;</li>
                <li>Secondo sinistro € 80 fisse;</li>
                <li>Terzo sinistro € 150 fisse;</li>
                </ol>
                <b>Polizza Responsabilità Civile Patrimoniale</b>:<br>
                <b>Franchigia per il Primo Sinistro</b><br>
                <ol>
                <li>Azzerata per sinistri fino a € 20.000;</li>
                <li>Per sinistri oltre € 20.000 la franchigia è pari al 50% del 10% con un minimo di € 100 e un massimo di € 3.000;</li>
                </ol>
                <b>Franchigia dal Secondo Sinistro</b><br>
                <ol>
                <li>10% con un minimo di € 100 e un massimo di € 3.000;</li>
                </ol>
                </td>
                </tr>
                </tbody>
                
                <p>Per coloro che aderiranno online è riservata senza limiti di utilizzo la "CARTA SERVIZI BENESSERE E PREVENZIONE" , un abbonamento annuale per tutta la famiglia per accedere a network di dentisti - fisioterapisti - poliambulatori e&nbsp; non solo -&nbsp; in oltre 3.000 strutture convenzionate in Italia - ed usufruire di tariffe e prestazione agevolate con sconti sul costo in media del 30%. Per saperne di piu\', clicca qui</p>',

                'pre_declarations' => 'Io sottoscritto in qualità di assicurato di un programma assicurativo in forma collettiva e/o individuale, in ottemperanza del Regolamento ISVAP n. 40 del 02/08/2018, prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations' => '',
                'detailed_description' => '',
                'client_details_declaration' => '',
                'payments_description' => 'Per consentire l\'emissione, e la relativa copertura, nella data di pagamento è necessario l’utilizzo di una opzione di pagamento Online mentre, se il metodo scelto è quello Offline, la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.',

                // Add other fields you want to update
                // 'updated_at' => now(), // You can update the 'updated_at' timestamp if needed
        ]);

        DB::table('products')
            ->where('id', 3)
            ->update([
                'name' => 'Sardegna - R.C. Patrimoniale del Bancario',
                'conditions' => '<div class="row">
                <div>
                <p class="text-muted">
                <strong>*</strong>Questi campi sono obbligatori.
                </p>
                </div>
                <div>
                <p><strong>Attenzione:</strong></p>
                <p>La copertura assicurativa ha data d\'inizio non inferiore al 01/01/2023 e scadenza non superiore al 31/12/2023.</p>
                <p>per leggere e scaricare le condizioni di polizza e Fascicolo Informativo&nbsp;<a href="https://www.golinucci.it/images/pdf/golinucci-RCBANCARI-Chubb.pdf" target="_blank">clicca qui</a></p>
                </div>
                <ul>
                <li>Compreso carico e scarico bancomat</li>
                <li><strong>Retroattività minima 5 anni e postuma 3 anni</strong></li>
                <li>Comprende&nbsp;<strong>copertura di violazione privacy e attivita’ assicurativa</strong></li>
                <li>Spese legali correlate al sinistro R.C. patrimoniale risarcibile a norma di polizza.</li>
                <li>il costo è già comprensivo di imposte (22.25%) e di spese per "gestione ed emissione"</li>
                </ul>
                </div>
                
                <p><strong>FRANCHIGIE E SCOPERTO</strong></p>
                
                <p><strong>Polizza Cassieri</strong>: primi due sinistri € 80 fisse; terzo sinistro € 155 fissa <strong>Polizza Responsabilità Civile Professionale</strong>: 10% del sinistro con un minimo di € 100 ed un massimo di € 2.500</p>
                
                <div class="row">
<p>Per coloro che aderiranno online è riservata la "CARTA SERVIZI BENESSERE E PREVENZIONE" , un abbonamento annuale per tutta la famiglia per accedere a network di dentisti - fisioterapisti - poliambulatori e&nbsp; non solo -&nbsp; in oltre 3.000 strutture convenzionate in Italia - ed usufruire di tariffe e prestazione agevolate con sconti sul costo in media del 30% .</p>
</div>',

                'pre_declarations' => 'Io sottoscritto in qualità di assicurato di un programma assicurativo in forma collettiva e/o individuale, in ottemperanza del Regolamento ISVAP n. 40 del 02/08/2018, prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations' => '',
                'detailed_description' => '',
                'client_details_declaration' => '',
                'payments_description' => 'Per consentire l\'emissione, e la relativa copertura, nella data di pagamento è necessario l’utilizzo di una opzione di pagamento Online mentre, se il metodo scelto è quello Offline, la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.',

                // Add other fields you want to update
                // 'updated_at' => now(), // You can update the 'updated_at' timestamp if needed
        ]);


        DB::table('product_declarations')->insert(
            array(
                'id' => '41',
                'product_id' => '3',
                'declaration' => 'di essere domiciliato in Italia',
                'sort' => '10',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '42',
                'product_id' => '3',
                'declaration' => 'di essere a conoscenza del "periodo di ripensamento" di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue - D.Lgs 58/2014), segnalando il mio diritto di recesso tramite email a bancari@golinucci.it o info@pec.golinucci.it',
                'sort' => '20',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '43',
                'product_id' => '3',
                'declaration' => 'di aver preso visione dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EaMkTb6ZqwxGus30WiCW9JEB7BOv73nsG-RmQuRsQcbDvg?e=1Us7f4">allegato 3</a> sugli obblighi di comportamento cui gli intermediari sono tenuti nei confronti del Contraente ed Assicurati e dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/ET4BR7TMiqNAhfFIL8W5ihABx25sf7pvEx9mCLE6U2bKIQ?e=SJ90Ga">allegato 4</a> riguardante gli elementi informativi su Golinucci srl',
                'sort' => '30',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '44',
                'product_id' => '3',
                'declaration' => 'di aver autorizzato con <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EV3YYoZrRNhOqeEhCQptfeMBEI-d2CZB5AjqNpTeEwHphg?e=qbmhhO">lettera incarico</a> il broker di Assicurazione GOLINUCCI srl alla gestione dei contratti assicurativi che ho selezionato online',
                'sort' => '40',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '45',
                'product_id' => '3',
                'declaration' => 'di aver presa visione del Set Informativo relativo ai contratti selezionati online',
                'sort' => '50',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '46',
                'product_id' => '3',
                'declaration' => 'che la Golinucci srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte',
                'sort' => '60',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '47',
                'product_id' => '3',
                'declaration' => 'di autorizzare – ai sensi art. 61 del Regolamento IVASS n.40 del 02/08/2018 – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole non cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato nella pagina “Informazioni su Assicurato”',
                'sort' => '70',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '48',
                'product_id' => '3',
                'declaration' => 'di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi.',
                'sort' => '80',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '49',
                'product_id' => '3',
                'declaration' => 'di autorizzare la Golinucci srl al trattamento dei dati personali e dichiaro di aver preso visione <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EVU8uj5KvMNGhlvuYS2pfsoBfr_-Q8f3F2TOnjtrXYF5VQ?e=JeXpp1">dell’Informativa Privacy</a> fornitami ai sensi dell’art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR',
                'sort' => '90',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '50',
                'product_id' => '3',
                'declaration' => 'di approvare l’informativa privacy del sito web',
                'sort' => '100',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '51',
                'product_id' => '3',
                'declaration' => 'di aver letto l’informativa contrattuale messa a disposizione degli assicurati dal contraente (per polizze con data di distribuzione antecedente il 01/12/2010), nel sito www.golinucci.it/',
                'sort' => '110',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '52',
                'product_id' => '3',
                'declaration' => 'di confermare che la polizza Ammanchi di Cassa - R.C. Patrimoniale con i massimale da me scelti è coerente alle mie esigenze',
                'sort' => '120',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '53',
                'product_id' => '3',
                'declaration' => 'di confermare che le dichiarazioni rese sono conformi a verità e di non avere omesso alcun elemento influente sulla valutazione del rischio',
                'sort' => '130',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );


        DB::table('products')
            ->where('id', 4)
            ->update([
                'name' => 'RI - R.C. Patrimoniale del Bancario',
                'conditions' => '<div class="row"><p><strong>Attenzione:</strong></p>
                <br><br>
                <p>La copertura assicurativa ha data d\'inizio non inferiore al 01/01/2023 e scadenza non superiore al 31/12/2023.</p>
                <p>per leggere e scaricare le condizioni di polizza e Fascicolo Informativo&nbsp;<a href="https://www.golinucci.it/images/pdf/golinucci-RCBANCARI-Chubb.pdf" target="_blank">clicca qui</a></p>
                <ul>
                <li>Compreso carico e scarico bancomat</li>
                <li><strong>Retroattività minima 5 anni e postuma 3 anni</strong></li>
                <li>Comprende&nbsp;<strong>copertura di violazione privacy e attivita’ assicurativa</strong></li>
                <li>Spese legali correlate al sinistro R.C. patrimoniale risarcibile a norma di polizza.</li>
                <li>il costo è già comprensivo di imposte (22.25%) e di spese per "gestione ed emissione"</li>
                </ul>
                <p><strong>FRANCHIGIE E SCOPERTO</strong><br><strong>Polizza Cassieri</strong>: primi due sinistri € 80 fisse; terzo sinistro € 155 fissa <strong>Polizza Responsabilità Civile Professionale</strong>: 10% del sinistro con un minimo di € 100 ed un massimo di € 2.500</p></div>
                
                <div class="row"><p>Per coloro che aderiranno online è riservata la "CARTA SERVIZI BENESSERE E PREVENZIONE" , un abbonamento annuale per tutta la famiglia per accedere a network di dentisti - fisioterapisti - poliambulatori e&nbsp; non solo -&nbsp; in oltre 3.000 strutture convenzionate in Italia - ed usufruire di tariffe e prestazione agevolate con sconti sul costo in media del 30% .</p></div>',

                'pre_declarations' => 'Io sottoscritto in qualità di assicurato di un programma assicurativo in forma collettiva e/o individuale, in ottemperanza del Regolamento ISVAP n. 40 del 02/08/2018, prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations' => '',
                'detailed_description' => '',
                'client_details_declaration' => '',
                'payments_description' => 'Per consentire l\'emissione, e la relativa copertura, nella data di pagamento è necessario l’utilizzo di una opzione di pagamento Online mentre, se il metodo scelto è quello Offline, la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.',

                // Add other fields you want to update
                // 'updated_at' => now(), // You can update the 'updated_at' timestamp if needed
        ]);


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
