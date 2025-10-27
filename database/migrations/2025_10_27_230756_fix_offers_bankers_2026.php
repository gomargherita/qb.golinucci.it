<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOffersBankers2026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE product_offers SET total_payable = '29.00' WHERE id = 80");
        DB::statement("UPDATE product_offers SET admin_fee = '6' WHERE id = 80");

        DB::statement("UPDATE bordereaux_er_info SET fee = '6' WHERE product_offer_id = 80");
        DB::statement("UPDATE bordereaux_er_info SET total_cost = '29' WHERE product_offer_id = 80");

        DB::statement("UPDATE product_declarations SET declaration = 'accetto di ricevere la documentazione precontrattuale e contrattuale di cui del regolamento Ivass n. 40 del 02/08/2018 su supporto durevole, scaricabile dal sito www.golinucci.it/firster alla sezione “Informative”' WHERE id = 17");
        DB::statement("UPDATE product_declarations SET declaration = 'dichiaro di aver presa visione dell’allegato 3 MUP (Modello Unico Precontrattuale)e dell’informativa precontrattuale, scaricabili dal sito www.golinucci.it/firster, alla sezione “Informative;' WHERE id = 18");
        
        DB::statement("DELETE FROM product_declarations WHERE id = 19");
        DB::statement("DELETE FROM product_declarations WHERE id = 21");

        DB::statement("UPDATE product_declarations SET declaration = 'di approvare l’informativa privacy del sito web disponibile al link: https://golinucci.it/privacy-policy/' WHERE id = 24");
        
        DB::statement("DELETE FROM product_declarations WHERE id = 25");
        DB::statement("DELETE FROM product_declarations WHERE id = 26");
        DB::statement("DELETE FROM product_declarations WHERE id = 27");
        DB::statement("DELETE FROM product_declarations WHERE id = 80");

        DB::statement("UPDATE product_declarations SET declaration = 'autorizzo la GOLINUCCI srl al trattamento dei dati personali e dichiaro di aver preso visione dell’Informativa Privacy ai sensi art. 13 EU 679/2016 GDPR scaricabile dal sito https://golinucci.it/informative/' WHERE id = 23");
        
        DB::statement("INSERT INTO product_declarations (id, product_id, declaration, sort) VALUES ('81', '2', 'dichiaro che le ESIGENZE ASSICURATIVE dell’assicurando sono Protezione dai danni provocati a terzi per AMMANCO DI CASSA nello svolgimento dell’attività lavorativa', 150)");
        DB::statement("INSERT INTO product_declarations (id, product_id, declaration, sort) VALUES ('82', '2', 'dichiaro che le ESIGENZE ASSICURATIVE dell’assicurando sono Protezione dai danni provocati a terzi per RESPONSABILITÀ PATRIMONIALE nello svolgimento dell’attività lavorativa', 160)");
        DB::statement("INSERT INTO product_declarations (id, product_id, declaration, sort) VALUES ('83', '2', 'dichiaro che le ESIGENZE ASSICURATIVE dell’assicurando sono Protezione dai danni subiti in seguito ad infortunio o malattia', 170)");

        DB::statement("INSERT INTO product_declarations (id, product_id, declaration, sort) VALUES ('84', '2', 'dichiaro che sono chiare le caratteristiche del contratto offerto', 180)");
        DB::statement("INSERT INTO product_declarations (id, product_id, declaration, sort) VALUES ('85', '2', 'dichiaro che sono chiare le esclusioni, le limitazioni e le decadenze previste dal contratto', 190)");
        


        
        
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
