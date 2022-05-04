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
                "declaration" => "di essere domiciliato in Italia",
                "notes" => NULL,
                "declaration_id" => 1,
                "customer_type_id" => 6,
                "sort" => 10,
            ),
            array(
                "declaration_id" => 22,
                "declaration" => "di essere a conoscenza del \"periodo di ripensamento\" di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue - D.Lgs 58/2014), segnalando il mio diritto di recesso tramite email a info@golinucci.it o <a href=\"mailto:info@pec.golinucci.it\">info@pec.golinucci.it</a>",
                "notes" => NULL,
                "declaration_id" => 22,
                "customer_type_id" => 6,
                "sort" => 20,
            ),
            array(
                "declaration_id" => 3,
                "declaration" => "di aver preso visione dell&rsquo; <a href=\"https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EaMkTb6ZqwxGus30WiCW9JEB7BOv73nsG-RmQuRsQcbDvg?e=1Us7f4\">allegato 3</a> sugli obblighi di comportamento cui gli intermediari sono tenuti nei confronti del Contraente ed Assicurati e dell&rsquo; <a href=\"https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/ET4BR7TMiqNAhfFIL8W5ihABx25sf7pvEx9mCLE6U2bKIQ?e=SJ90Ga\">allegato 4</a> riguardante gli elementi informativi su Golinucci srl",
                "notes" => NULL,
                "declaration_id" => 3,
                "customer_type_id" => 6,
                "sort" => 30,
            ),
            array(
                "declaration_id" => 16,
                "declaration" => "di aver autorizzato con <a href=\"https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EV3YYoZrRNhOqeEhCQptfeMBEI-d2CZB5AjqNpTeEwHphg?e=qbmhhO\">lettera incarico</a> il broker di Assicurazione GOLINUCCI srl alla gestione dei contratti assicurativi che ho selezionato online",
                "notes" => NULL,
                "declaration_id" => 16,
                "customer_type_id" => 6,
                "sort" => 31,
            ),
            array(
                "declaration_id" => 4,
                "declaration" => "di aver presa visione del Set Informativo relativo ai contratti selezionati online",
                "notes" => NULL,
                "declaration_id" => 4,
                "customer_type_id" => 6,
                "sort" => 40,
            ),
            array(
                "declaration_id" => 5,
                "declaration" => "che la Golinucci srl mi ha reso noto il costo totale che l&rsquo;Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte",
                "notes" => NULL,
                "declaration_id" => 5,
                "customer_type_id" => 6,
                "sort" => 50,
            ),
            array(
                "declaration_id" => 6,
                "declaration" => "di autorizzare &ndash; ai sensi art. 61 del Regolamento IVASS n.40 del 02/08/2018 &ndash; la ricezione della documentazione precontrattuale e contrattuale su supporto durevole non cartaceo o tramite internet, anche tramite l&rsquo;indirizzo di posta elettronica indicato nella pagina &ldquo;Informazioni su Assicurato&rdquo;",
                "notes" => NULL,
                "declaration_id" => 6,
                "customer_type_id" => 6,
                "sort" => 60,
            ),
            array(
                "declaration_id" => 7,
                "declaration" => "di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualit&agrave; di distributore di prodotti assicurativi.",
                "notes" => NULL,
                "declaration_id" => 7,
                "customer_type_id" => 6,
                "sort" => 70,
            ),
            array(
                "declaration_id" => 8,
                "declaration" => "di autorizzare la Golinucci srl al trattamento dei dati personali e dichiaro di aver preso visione <a href=\"https://golinc-my.sharepoint.com/:b:/g/personal/paolo_golinucci_it/EVU8uj5KvMNGhlvuYS2pfsoBfr_-Q8f3F2TOnjtrXYF5VQ?e=JeXpp1\">dell&rsquo;Informativa Privacy</a> fornitami ai sensi dell&rsquo;art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR",
                "notes" => NULL,
                "declaration_id" => 8,
                "customer_type_id" => 6,
                "sort" => 80,
            ),
            array(
                "declaration_id" => 9,
                "declaration" => "di approvare l&rsquo;informativa <a href=\"https://www.golinucci.it/privacy-policy\" target=\"_blank\">privacy del sito</a>",
                "notes" => NULL,
                "declaration_id" => 9,
                "customer_type_id" => 6,
                "sort" => 90,
            ),
            array(
                "declaration_id" => 23,
                "declaration" => "di aver letto l&rsquo;informativa contrattuale <a href=\"/wordings/Dental_Plan_Convenzione Bancari_Golinucci.pdf\" target=\"_blank\">(vedi)</a>",
                "notes" => NULL,
                "declaration_id" => 23,
                "customer_type_id" => 6,
                "sort" => 100,
            ),
            array(
                "declaration_id" => 20,
                "declaration" => "<strong>Ai sensi degli Artt. 1341 e 1342 C.C., dichiaro di approvare espressamente le disposizioni: </strong>\r\n<strong>Condizioni Generali di Assicurazione: </strong>\r\nArticolo 1 - Valutazione delle richieste ed esigenze dell&rsquo;Aderente e conseguenze per dichiarazioni inesatte o reticenti dell&rsquo;Aderente, Art. 3 Pagamento del premio , Art. 4 Inizio della copertura e diritto di recesso, Art. 5 Durata del Contratto. Modalit&agrave; di disdetta, Art. 6 Altre assicurazioni, Art. 7 Foro competente, Art. 17 Limiti di intervento, Art. 18 Requisiti per l&rsquo;operativit&agrave; delle prestazioni, Art. 19 Patologie preesistenti, Art. 20 Periodo di Carenza, Art. 21 Persone non assicurabili e limiti di et&agrave;, Art. 22 Aggravamento del rischio ,Art. 23 Esclusioni generali, Art. 24 Adempimenti in caso di sinistro",
                "notes" => NULL,
                "declaration_id" => 20,
                "customer_type_id" => 6,
                "sort" => 130,
            ),
            array(
                "declaration_id" => 14,
                "declaration" => "di confermare che le dichiarazioni rese sono conformi a verit&agrave; e di non avere omesso alcun elemento influente sulla valutazione del rischio",
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
