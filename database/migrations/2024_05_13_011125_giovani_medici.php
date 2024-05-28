<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ConditionalField;

class GiovaniMedici extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('products')->insert(
            array(
                'id' => 5,
                'account_id' => 1,
                'product_type_id' => 1,
                'name' => 'Giovani Medici',
                'description' => 'Responsabilità Civile del Giovane Medico abilitato con meno di 38 anni: <br>
                 RC professionale del medico con meno 38 anni abilitato e/o in di formazione specialistica oppure corsista in medicina generale.',
                'detailed_description' => "A titolo esemplificativo e non esaustivo rientrano in copertura anche le seguenti prestazioni sanitarie mediche:<br>
 
                    •⁠ tutte le attività formative previste dalle scuole di specializzazione e dal corso di formazione specifica in Medicina Generale;<br>
                    •⁠ sostituzione del Medico di Medicina Generale e del Pediatra di Libera Scelta;<br>
                    •⁠ Servizio di Continuità Assistenziale e Unità Speciali di Continuità Assistenziale (USCA) o quello di Urgenza ed Emergenza Medica 118, inclusi Centri di Assistenza e Urgenza (CAU) istituiti nella Regione Emilia-Romagna;<br>
                    •⁠ Servizio di Continuità Assistenziale stagionale per turisti (ex Guardia Medica Turistica);<br>
                    •⁠ somministrazione di vaccini;<br>
                    •⁠ assistenza medica svolta all'interno di eventi sportivi a manifestazioni;<br>
                    •⁠ attività di consulenza presso e per conto di Strutture sanitarie o enti pubblici e privati (es. case di cura private, palestre, istituti termali, centri benessere);<br>
                    •⁠ attività di volontariato;<br>
                    interventi di primo soccorso eseguiti in qualità di medico per dovere di solidarietà, emergenza o soccorso sanitario al di fuori dell'attività retribuita.<br>
                    <br>
                    Al conseguimento della Specializzazione o del titolo di Medico di medicina generale o, comunque, al raggiungimento dei 38 anni di età, la copertura verrà a cessare e la tutela per i fatti avvenuti in vigenza di polizza potrà essere mantenuta in copertura attraverso una nuova polizza LLOYD’S tramite Golinucci, senza dover acquistare una copertura “postuma”.
                    ",
                'pre_declarations' => 'Io sottoscritto/a in qualità di assicurato della polizza RC professionale Giovane Medico, in ottemperanza del Regolamento IVASS n. 40 del 02/08/2018 (Modificato ed Integrato dal Provvedimento IVASS N.97 del 04/08/2020), prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations'=> '',
                'conditions'=> 'La soluzione assicurativa, aggiornata alle disposizioni contenute nella Legge 24/17 (Legge Gelli-Bianco), prevede inoltre:<br>
 <br>
                1.Apertura del sinistro fin dalla ricezione di Comunicazione ai sensi dell’art. 13 della Legge Gelli-Bianco con cui l’Azienda sanitaria informa il medico dell’avvio di un giudizio o di trattative stragiudiziali con il paziente, con maggiore tutela dell’assicurato in quanto non deve aspettare anni per dare comunicazione dell’eventuale ricezione di un “invito a dedurre” da parte della Corte dei Conti.<br>
                 
                2.Copertura per i danni di natura patrimoniale danni di natura patrimoniale (non conseguenti a lesioni e danni materiali a cose).<br>
                 
                3.Retroattività: a scelta 2 anni  se appena abilitato, altrimenti 10 anni;<br>
                 
                4.Copertura “postuma” senza costi aggiuntivi con la stipula di una nuova polizza LLOYD’S tramite Golinucci al conseguimento del titolo o al raggiungimento dei 38 anni di età.',
                'client_details_declaration'=> '',
                'payments_description'=> 'Per consentire l\'emissione, e la relativa copertura, nella data di pagamento è necessario l’utilizzo di una opzione di pagamento Online mentre, se il metodo scelto è quello Offline, la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.',
                'hex_color'=> '#32a852',
                'active' => 1
            )
        );

        $field = ConditionalField::find(1);

        $field->field_value = "";
        $field->save();
        $field->field_value = '{"1":"Dirigente Medico","2":"Medico Convenzionato","3":"Giovane medico","4":"Personale sanitario","5":"Dirigente Sanitario non Medico","6":"Dirigente Infermieristico"}';

        $field->save();

        DB::table('conditional_fields')->insert(
            array(
                'id' => 14,
                'field_name' => 'piva',
                'field_label' => 'Partita IVA (facoltativo)',
                'field_type' => 'text',
                'field_value' => '""',
                'only_client_details' => 1
            ));

        DB::table('conditional_fields')->insert(
            array(
                'id' => 13,
                'field_name' => 'specialization_gmedici',
                'field_label' => 'Specializzazione (facoltativo)',
                'field_type' => 'text',
                'field_value' => '""',
                'only_client_details' => 1
            ));

        DB::table('conditional_fields')->insert(
            array(
                'id' => 11,
                'field_name' => 'retroattivita',
                'field_label' => 'Retroattività',
                'field_type' => 'select',
                'field_value' => '"{\"1\":\"2 Anni\",\"2\":\"10 Anni\"}"',
                'only_client_details' => 0
            ));

        DB::table('conditional_fields')->insert(
            array(
                'id' => 12,
                'field_name' => 'massimale',
                'field_label' => 'Massimale',
                'field_type' => 'select',
                'field_value' => '"{\"1\":\"1.000.000 €\",\"2\":\"2.000.000 €\"}"',
                'only_client_details' => 0
            ));

        DB::table('conditional_fields')->insert(
            array(
                'id' => 10,
                'field_name' => 'qualifica_giovani_medici',
                'field_label' => "Qualifica dell'assicurato",
                'field_type' => 'select',
                'field_value' => '"{\"1\":\"Medico generico (medico abilitato)\",\"2\":\"Medico in formazione specialistica\",\"3\":\"Medico in formazione specifica in medicina generale\"}"',
                'only_client_details' => 1
            ));


        DB::table('product_conditions')->insert(
            array(
                'product_id' => 5,
                'conditional_field_id' => 11
            ));
    
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 5,
                'conditional_field_id' => 12
            ));

        DB::table('product_conditions')->insert(
            array(
                'product_id' => 5,
                'conditional_field_id' => 10
            ));

        DB::table('product_conditions')->insert(
            array(
                'product_id' => 5,
                'conditional_field_id' => 13
            ));
        
        DB::table('product_conditions')->insert(
            array(
                'product_id' => 5,
                'conditional_field_id' => 14
            ));

        DB::table('product_offer_tables')->insert(
            array(
                'product_id' => 5,
                'table_elements' => '{"1":"MASSIMALE PER DANNI MATERIALI E IMMATERIALI PER LA PROFESSIONE SANITARIA","2":"RETROATTIVITÀ","3":"FRANCHIGIA","4":"COSTO ANNUO", "5":"WORDING"}'
            )
        );
        
        DB::table('contracts')->insert(
            array(
                'id' => '8',
                'contract_filepath' => '/storage/contracts/WORDING_GIOVANE_MEDICO_2024.pdf',
                'dip' => '/storage/contracts/DIP_GIOVANE_MEDICO.pdf',
                'dip_aggiuntivo' => 'DIP_AGGIUNTIVO_GIOVANE_MEDICO.pdf',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '161',
                'product_id' => '5',
                'contract_id' => '8',
                'name' => 'Giovani Medici 2 Anni 1.000.000',
                'sku' => '',
                'total_payable' => '293.95',
                'admin_fee' => '25.0',
                'gross_premium' => '268.95',
                'tax' => '48.95',
                'net_premium' => '200.00',
                'accessory' => '20.00',
                'net_plus_access_premium' => '32.72',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '2',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '162',
                'product_id' => '5',
                'contract_id' => '8',
                'name' => 'Giovani Medici 2 Anni 2.000.000',
                'sku' => '',
                'total_payable' => '361.19',
                'admin_fee' => '25.0',
                'gross_premium' => '336.19',
                'tax' => '61.19',
                'net_premium' => '250.00',
                'accessory' => '25.00',
                'net_plus_access_premium' => '275.00',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '2',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '163',
                'product_id' => '5',
                'contract_id' => '8',
                'name' => 'Giovani Medici 10 Anni 1.000.000',
                'sku' => '',
                'total_payable' => '347.74',
                'admin_fee' => '25.0',
                'gross_premium' => '322.74',
                'tax' => '58.74',
                'net_premium' => '240.00',
                'accessory' => '24.00',
                'net_plus_access_premium' => '264.00',
                'description' => 'vuoto',
                'indemnity_limit' => '1000000',
                'indemnity_limit_patrimonial' => '10',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
            )
        );

        DB::table('product_offers')->insert(
            array(
                'id' => '164',
                'product_id' => '5',
                'contract_id' => '8',
                'name' => 'Giovani Medici 10 Anni 2.000.000',
                'sku' => '',
                'total_payable' => '428.43',
                'admin_fee' => '25.0',
                'gross_premium' => '403.43',
                'tax' => '73.43',
                'net_premium' => '300.00',
                'accessory' => '30.00',
                'net_plus_access_premium' => '330.00',
                'description' => 'vuoto',
                'indemnity_limit' => '2000000',
                'indemnity_limit_patrimonial' => '10',
                'legal_fees' => '0',
                'active' => '1',
                'months' => '12',
                'combination' => '0',
                'hide' => '0',
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 161,
                'conditional_field_id' => 11,
                'selected_field_value' => 1,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 161,
                'conditional_field_id' => 12,
                'selected_field_value' => 1,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 162,
                'conditional_field_id' => 11,
                'selected_field_value' => 1,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 162,
                'conditional_field_id' => 12,
                'selected_field_value' => 2,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 163,
                'conditional_field_id' => 11,
                'selected_field_value' => 2,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 163,
                'conditional_field_id' => 12,
                'selected_field_value' => 1,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 164,
                'conditional_field_id' => 11,
                'selected_field_value' => 2,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_offer_conditions')->insert(
            array(
                'product_offer_id' => 164,
                'conditional_field_id' => 12,
                'selected_field_value' => 2,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '23',
                'product_id' => '5',
                'step_number' => '1',
                'step_name' => 'Anagrafica',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '24',
                'product_id' => '5',
                'step_number' => '2',
                'step_name' => 'Scelta offerta prodotto',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '25',
                'product_id' => '5',
                'step_number' => '3',
                'step_name' => 'Dichiarazioni',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '26',
                'product_id' => '5',
                'step_number' => '4',
                'step_name' => 'Pagamento',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '27',
                'product_id' => '5',
                'step_number' => '5',
                'step_name' => 'Lloyds',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '28',
                'product_id' => '5',
                'step_number' => '6',
                'step_name' => 'Yousign',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_steps')->insert(
            array(
                'id' => '29',
                'product_id' => '5',
                'step_number' => '7',
                'step_name' => 'Completato',
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '54',
                'product_id' => '5',
                'declaration' => 'Di essere domiciliato in Italia',
                'sort' => 10,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '55',
                'product_id' => '5',
                'declaration' => 'Di essere a conoscenza del “periodo di ripensamento” di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue – D.lgs. 58/2014), segnalando il mio diritto di recesso tramite email a clienti@golinucci.it o info@pec.golinucci.it.',
                'sort' => 20,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '56',
                'product_id' => '5',
                'declaration' => 'Di aver preso visione dell’allegato 4 ter (<a href="https://www.golinucci.it/gndoc">www.golinucci.it/gndoc</a>) sugli obblighi di comportamento cui gli intermediari sono tenuto nei confronti del Contraente ed Assicurati e dell’allegato 3 (<a href="https://www.golinucci.it/gndoc">www.golinucci.it/gndoc</a>) e dell’allegato 4 (<a href="https://www.golinucci.it/gndoc">www.golinucci.it/gndoc</a>) riguardante gli elementi informativi su Golinucci Srl.          ',
                'sort' => 30,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '57',
                'product_id' => '5',
                'declaration' => 'Di aver autorizzato con lettera incarico (<a href="https://www.golinucci.it/gndoc">www.golinucci.it/gndoc</a>) il broker di Assicurazione GOLINUCCI S.r.l. alla gestione dei contratti assicurativi selezionati online.',
                'sort' => 40,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '58',
                'product_id' => '5',
                'declaration' => 'Di aver presa visione del Set Informativo (<a href="https://www.golinucci.it/sil">www.golinucci.it/sil</a>) relativo ai contratti selezionati online.              ',
                'sort' => 50,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '59',
                'product_id' => '5',
                'declaration' => 'Che la Golinucci Srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte.',
                'sort' => 60,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '60',
                'product_id' => '5',
                'declaration' => 'Di autorizzare - ai sensi art. 61 del Regolamento IVASS n. 40 del 02/08/2018 (Modificato e Integrato dal Provvedimento IVASS N.97 del 04/08/2020) – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole con cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato dal cliente.',
                'sort' => 70,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '61',
                'product_id' => '5',
                'declaration' => 'Di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi.',
                'sort' => 80,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '62',
                'product_id' => '5',
                'declaration' => 'Di autorizzare la Golinucci Srl al trattamento dei dati personali e dichiaro di aver preso visione dell’Informativa Privacy (<a href="https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf">www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf</a>) fornitami ai sensi dell’Art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR.',
                'sort' => 90,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '63',
                'product_id' => '5',
                'declaration' => 'Di approvare l\'informativa privacy (<a href="https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf">www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf</a>)              ',
                'sort' => 100,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '64',
                'product_id' => '5',
                'declaration' => 'Di essere a conoscenza che la presente proposta è relativa ad una polizza di responsabilità nella forma “Claims Made”. Ciò significa che la polizza copre le richieste di risarcimento presentate per la prima volta alle Persone Assicurate durante il periodo di validità della polizza e che potrebbero originare un reclamo futuro, a condizione che egli Assicuratori siano informati, durante il periodo di validità della polizza, delle Circostanze relative a tali eventi. Dopo la scadenza della polizza, nessun reclamo potrà essere avanzato a termini della polizza stessa, anche se l’evento che ha originato la richiesta danni si sia verificato durante il periodo di validità della polizza.',
                'sort' => 110,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '65',
                'product_id' => '5',
                'declaration' => 'Di essere a conoscenza che ai sensi dell’Art. 1341 e 1342 del Codice Civile, il sottoscritto Contraente dichiara di aver preso atto che questo è un contratto di assicurazione nella forma “CLAIMS MADE” a coprire le Richieste di Risarcimento fatte per la prima volta contro l’Assicurato durante il Periodo di Assicurazione e da lui denunciate agli Assicuratori durante lo stesso periodo.',
                'sort' => 120,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '66',
                'product_id' => '5',
                'declaration' => 'Di approvare il questionario di valutazione del Test di Coerenza del contratto di R.C. Professionale Giovane Medico ai sensi dell’art. 58 del Regolamento IVASS n. 40 del 02/08/2018 definito dalle Esigenze Assicurative: Protezione dalla Responsabilità Civile, ai sensi della legge 24/17 (c.d. Legge Gelli), nello svolgimento dell\'attività lavorativa, che provocano DANNI MATERIALI a Terzi quali lesioni a persone o danni a cose                ',
                'sort' => 130,
                'created_at' => now(),
                'updated_at' => now()
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '67',
                'product_id' => '5',
                'declaration' => 'Di confermare che le dichiarazioni rese sono conformi a verità di non aver omesso alcun elemento influente sulla valutazione del rischio',
                'sort' => 140,
                'created_at' => now(),
                'updated_at' => now()
            )
        );
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
