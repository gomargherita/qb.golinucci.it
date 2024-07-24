<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DeclarationsSalutesportSpecifiche extends Migration
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
                'id' => 999,
                'account_id' => 2,
                'product_type_id' => 1,
                'name' => 'Salute e Sport',
                'description' => '',
                'detailed_description' => "",
                'pre_declarations' => 'Io sottoscritto/a in qualità di assicurato della polizza Infortuni, in ottemperanza del Regolamento IVASS n. 40 del 02/08/2018 (Modificato ed Integrato dal Provvedimento IVASS N.97 del 04/08/2020), prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations'=> '',
                'conditions'=> '',
                'client_details_declaration'=> '',
                'payments_description'=> '',
                'hex_color'=> '#32a852',
                'active' => 1
            )
        );

        DB::table('product_declarations')->insert(
            array(
                'id' => '68',
                'product_id' => '999',
                'declaration' => 'di essere domiciliato in Italia',
                'sort' => '10',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '69',
                'product_id' => '999',
                'declaration' => 'di essere a conoscenza del “periodo di ripensamento” di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue – D.lgs. 58/2014), segnalando il mio diritto di recesso tramite email a clienti@golinucci.it o info@pec.golinucci.it.',
                'sort' => '20',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '70',
                'product_id' => '999',
                'declaration' => 'di aver preso visione dell’ <a href="https://drive.google.com/file/d/1A2nKKfRRkP6dt0nWbMBrazQjGX0AFv2b/view" target="_blank">allegato 4 ter</a> sugli obblighi di comportamento cui gli intermediari sono tenuto nei confronti del Contraente ed Assicurati e dell’ <a href="https://drive.google.com/file/d/1uCOUrqKfncFztuSTwX1QpPKN4hFDgPnO/view" target="_blank">allegato 3</a> e dell’ <a href="https://drive.google.com/file/d/1V3xCE9z-EVTRjgkGRfdO08wQCbQ4pvxV/view" target="_blank">allegato 4</a> riguardante gli elementi informativi su Golinucci srl',
                'sort' => '30',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '71',
                'product_id' => '999',
                'declaration' => 'di aver autorizzato con <a href="https://drive.google.com/file/d/12yR9bIMWm6mjcT4aDtmqDEYAK62z_mb_/view" target="_blank">lettera incarico</a> il broker di Assicurazione GOLINUCCI srl alla gestione dei contratti assicurativi selezionati online',
                'sort' => '40',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '72',
                'product_id' => '999',
                'declaration' => 'di aver presa visione del <a href="https://drive.google.com/file/d/1SLfejIP1yyKHzDyQXyhekS33u1bwIcys/view" target="_blank">dip</a> e del <a href="https://drive.google.com/file/d/13QjCImHrf_6XLiqZmZzWpnOjNfCMKMPP/view" target="_blank">set informativo</a> relativo ai contratti selezionati online',
                'sort' => '50',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '73',
                'product_id' => '999',
                'declaration' => 'che la Golinucci srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte',
                'sort' => '60',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '74',
                'product_id' => '999',
                'declaration' => 'di autorizzare – ai sensi art. 61 del Regolamento IVASS n.40 del 02/08/2018 (Modificato e integrato dal Provvedimento IVASS N.97 del 04/08/2020) – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole non cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato dal cliente',
                'sort' => '70',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '75',
                'product_id' => '999',
                'declaration' => 'di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi',
                'sort' => '80',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '76',
                'product_id' => '999',
                'declaration' => 'di autorizzare la Golinucci srl al trattamento dei dati personali e dichiaro di aver preso visione <a href="https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf" target="_blank">dell’Informativa Privacy</a> fornitami ai sensi dell’art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR',
                'sort' => '90',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '77',
                'product_id' => '999',
                'declaration' => 'di approvare <a href="https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf" target="_blank">l’informativa privacy</a>',
                'sort' => '100',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '78',
                'product_id' => '999',
                'declaration' => 'Di approvare il questionario di valutazione del Test di Coerenza del contratto di assicurazione INFORTUNI ai sensi dell’art. 58 del Regolamento IVASS n. 40 del 02/08/2018 definito dalle Esigenze Assicurative: Protezione dagli infortuni Professionali ed extraprofessionali per i casi di morte, invalidita’ permanente di grado superiore al 5%, rimborso spese mediche da infortunio.',
                'sort' => '110',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '79',
                'product_id' => '999',
                'declaration' => 'di confermare che le dichiarazioni rese sono conformi a verità e di non avere omesso alcun elemento influente sulla valutazione del rischio',
                'sort' => '130',
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
