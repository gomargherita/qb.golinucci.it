<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductDeclarationsBankers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('product_declarations')->insert(
            array(
                'id' => '15',
                'product_id' => '2',
                'declaration' => 'di essere domiciliato in Italia',
                'sort' => '10',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '16',
                'product_id' => '2',
                'declaration' => 'di essere a conoscenza del "periodo di ripensamento" di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue - D.Lgs 58/2014), segnalando il mio diritto di recesso tramite email a bancari@golinucci.it o info@pec.golinucci.it',
                'sort' => '20',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '17',
                'product_id' => '2',
                'declaration' => 'di aver preso visione dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EaMkTb6ZqwxGus30WiCW9JEB7BOv73nsG-RmQuRsQcbDvg?e=1Us7f4">allegato 3</a> sugli obblighi di comportamento cui gli intermediari sono tenuti nei confronti del Contraente ed Assicurati e dell’ <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/ET4BR7TMiqNAhfFIL8W5ihABx25sf7pvEx9mCLE6U2bKIQ?e=SJ90Ga">allegato 4</a> riguardante gli elementi informativi su Golinucci srl',
                'sort' => '30',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '18',
                'product_id' => '2',
                'declaration' => 'di aver autorizzato con <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EV3YYoZrRNhOqeEhCQptfeMBEI-d2CZB5AjqNpTeEwHphg?e=qbmhhO">lettera incarico</a> il broker di Assicurazione GOLINUCCI srl alla gestione dei contratti assicurativi che ho selezionato online',
                'sort' => '40',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '19',
                'product_id' => '2',
                'declaration' => 'di aver presa visione del Set Informativo relativo ai contratti selezionati online',
                'sort' => '50',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '20',
                'product_id' => '2',
                'declaration' => 'che la Golinucci srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte',
                'sort' => '60',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '21',
                'product_id' => '2',
                'declaration' => 'di autorizzare – ai sensi art. 61 del Regolamento IVASS n.40 del 02/08/2018 – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole non cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato nella pagina “Informazioni su Assicurato”',
                'sort' => '70',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '22',
                'product_id' => '2',
                'declaration' => 'di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi.',
                'sort' => '80',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '23',
                'product_id' => '2',
                'declaration' => 'di autorizzare la Golinucci srl al trattamento dei dati personali e dichiaro di aver preso visione <a href="https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EVU8uj5KvMNGhlvuYS2pfsoBfr_-Q8f3F2TOnjtrXYF5VQ?e=JeXpp1">dell’Informativa Privacy</a> fornitami ai sensi dell’art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR',
                'sort' => '90',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '24',
                'product_id' => '2',
                'declaration' => 'di approvare l’informativa privacy del sito web',
                'sort' => '100',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '25',
                'product_id' => '2',
                'declaration' => 'di aver letto l’informativa contrattuale messa a disposizione degli assicurati dal contraente (per polizze con data di distribuzione antecedente il 01/12/2010), nel sito www.golinucci.it/',
                'sort' => '110',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '26',
                'product_id' => '2',
                'declaration' => 'di confermare che la polizza Ammanchi di Cassa - R.C. Patrimoniale con i massimale da me scelti è coerente alle mie esigenze',
                'sort' => '120',
                //'created_at' => now(),
                //'updated_at' => now()
            )
        );
        DB::table('product_declarations')->insert(
            array(
                'id' => '27',
                'product_id' => '2',
                'declaration' => 'di confermare che le dichiarazioni rese sono conformi a verità e di non avere omesso alcun elemento influente sulla valutazione del rischio',
                'sort' => '130',
                //'created_at' => now(),
                //'updated_at' => now()
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
