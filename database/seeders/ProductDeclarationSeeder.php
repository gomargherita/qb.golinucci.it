<?php

namespace Database\Seeders;

use App\Models\ProductDeclaration;
use Illuminate\Database\Seeder;

class ProductDeclarationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * result of 
         * SELECT * FROM declaration d LEFT JOIN declaration2customertype d2 ON (d2.declaration_id = d.declaration_id) WHERE d2.customer_type_id = 6 OR d2.customer_type_id = 8 ORDER BY d2.customer_type_id ASC, d2.sort ASC
         * NOTA IMPORTANTE: dato che saranno legate al prodotto, vado ad estrarre solo una delle 2 tipologie, quella con customer_type_id = 6
         */
        $productDeclarations = array(
            array(
                "declaration_id" => 1,
                "declaration" => "Di essere domiciliato in Italia",
                "notes" => NULL,
                "declaration_id" => 1,
                "customer_type_id" => 6,
                "sort" => 10,
            ),
            array(
                "declaration_id" => 22,
                "declaration" => "Di essere a conoscenza del “periodo di ripensamento” di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue – D.lgs 58/2014), segnalando il mio diritto di recesso tramite email a medmal@golinucci.it o info@pec.golinucci.it",
                "notes" => NULL,
                "declaration_id" => 22,
                "customer_type_id" => 6,
                "sort" => 20,
            ),
            array(
                "declaration_id" => 3,
                "declaration" => "Di aver preso visione dell’allegato 4 ter (www.golinucci.it/gndoc) sugli obblighi di comportamento cui gli intermediari sono tenuto nei confronti del Contraente ed Assicurati e dell’allegato 3 (www.golinucci.it/gndoc) e dell’allegato 4 (www.golinucci.it/gndoc) riguardante gli elementi informativi su Golinucci Srl.",
                "notes" => NULL,
                "declaration_id" => 3,
                "customer_type_id" => 6,
                "sort" => 30,
            ),
            array(
                "declaration_id" => 16,
                "declaration" => "Di aver autorizzato con lettera incarico (www.golinucci.it/gndoc) il broker di Assicurazione GOLINUCCI S.r.l. alla gestione dei contratti assicurativi selezionati online",
                "notes" => NULL,
                "declaration_id" => 16,
                "customer_type_id" => 6,
                "sort" => 31,
            ),
            array(
                "declaration_id" => 4,
                "declaration" => "Di aver presa visione del Set Informativo relativo ai contratto selezionati online",
                "notes" => NULL,
                "declaration_id" => 4,
                "customer_type_id" => 6,
                "sort" => 40,
            ),
            array(
                "declaration_id" => 5,
                "declaration" => "Che la Golinucci Srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte.",
                "notes" => NULL,
                "declaration_id" => 5,
                "customer_type_id" => 6,
                "sort" => 50,
            ),
            array(
                "declaration_id" => 6,
                "declaration" => "Di autorizzare - ai sensi art. 61 del Regolamento IVASS n. 40 del 02/08/2018 (Modificato ed Integrato dal Provvedimento IVASS N.97 del 04/08/2020) – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole con cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato dal cliente",
                "notes" => NULL,
                "declaration_id" => 6,
                "customer_type_id" => 6,
                "sort" => 60,
            ),
            array(
                "declaration_id" => 7,
                "declaration" => "Di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi",
                "notes" => NULL,
                "declaration_id" => 7,
                "customer_type_id" => 6,
                "sort" => 70,
            ),
            array(
                "declaration_id" => 8,
                "declaration" => "Di autorizzare la Golinucci Srl al trattamento dei dati personali e dichiaro di aver preso visione dell’Informativa Privacy (www.golinucci.it/gndoc) fornitami ai sensi dell’Art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR",
                "notes" => NULL,
                "declaration_id" => 8,
                "customer_type_id" => 6,
                "sort" => 80,
            ),
            array(
                "declaration_id" => 9,
                "declaration" => "Di approvare l'<b>informativa privacy</b> (<a href=\"https://www.golinucci.it/privacy-policy\" target=\"_blank\">www.golinucci.it/privacy-policy</a>)",
                "notes" => NULL,
                "declaration_id" => 9,
                "customer_type_id" => 6,
                "sort" => 90,
            ),
            array(
                "declaration_id" => 23,
                "declaration" => "Di essere a conoscenza che la presente proposta è relativa ad una polizza di responsabilità nella forma “Claims Made”. Ciò significa che la polizza copre: Le richieste di risarcimento presentate per la prima volta alle Persone Assicurate durante il periodo di validità della polizza e che potrebbero originare un Reclamo futuro, a condizione che egli Assicuratori siano informati, durante il periodo di validità della polizza, delle Circostanze relative a tali eventi. Dopo la scadenza della polizza, nessun reclamo potrà essere avanzato a termini della polizza stessa, anche se l’evento che ha originato la richiesta danni si sia verificato durante il periodo di validità della polizza",
                "notes" => NULL,
                "declaration_id" => 23,
                "customer_type_id" => 6,
                "sort" => 100,
            ),
            array(
                "declaration_id" => 20,
                "declaration" => "Di essere a conoscenza che ai sensi dell’Art. 1341 e 1342 del Codice Civile, il sottoscritto Contraente dichiara di <b>aver preso atto che questo è un contratto di assicurazione nella forma “CLAIMS MADE” a coprire le Richieste di Risarcimento fatte per la prima volta contro l’Assicurato durante il Periodo di Assicurazione e da lui denunciate agli Assicuratori durante lo stesso periodo</b>",
                "notes" => NULL,
                "declaration_id" => 20,
                "customer_type_id" => 6,
                "sort" => 130,
            ),
            array(
                "declaration_id" => 14,
                "declaration" => "Di approvare il questionario di valutazione del Test di Coerenza del contratto di R.C. Professionale Colpa Grave ai sensi dell’art. 58 del Regolamento IVASS n. 40 del 02/08/2018<p><b>Esigenze Assicurative</b></p><ul><li>Protezione dalla Responsabilità Civile, ai sensi della legge 24/17 (c.d. Legge Gelli), nello svolgimento dell'attività lavorativa, che provocano DANNI MATERIALI a Terzi quali lesioni a persone o danni a cose</li><li>Protezione dalla Responsabilità Civile, ai sensi della legge 24/17 (c.d. Legge Gelli), nello svolgimento dell'attività lavorativa, che provocano DANNI IMMATERIALI a Terzi, come da indicazioni sulla proposta di assicurazione</li></ul>",
                "notes" => NULL,
                "declaration_id" => 14,
                "customer_type_id" => 6,
                "sort" => 140,
            ),
            array(
                "declaration_id" => 14,
                "declaration" => "Di confermare che le dichiarazioni rese sono conformi a verità di non aver omesso alcun elemento influente sulla
                valutazione del rischio",
                "notes" => NULL,
                "declaration_id" => 14,
                "customer_type_id" => 6,
                "sort" => 140,
            ),
        );

        foreach ($productDeclarations as $productDeclaration) {
            ProductDeclaration::factory()->create([
                'product_id' => 1,
                'declaration' => $productDeclaration['declaration'],
                'notes' => $productDeclaration['notes'],
                'sort' => $productDeclaration['sort'],
            ]);
        }
    }
}
