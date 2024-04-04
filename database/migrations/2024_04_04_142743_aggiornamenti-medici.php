<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AggiornamentiMedici extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE products SET conditions = '<p>            La polizza è aggiornata alle disposizioni contenute nella Legge 24/17 (Legge Gelli),             prevede:          </p>         <ol class=\"list-decimal list-inside\">           <li>             <span style = \"font-weight:bold;\">1. Apertura del sinistro fin dalla ricezione di Comunicazione ai sensi dell’art. 13 della Legge Gelli-Bianco</span>con cui l’Azienda sanitaria informa il medico dell’avvio di un giudizio o di trattative stragiudiziali con il paziente, con maggiore tutela dell’assicurato in quanto non deve aspettare anni per dare comunicazione dell’eventuale ricezione di un “invito a dedurre” da parte della Corte dei Conti.</br></li>           <li>             <span style = \"font-weight:bold;\"><br>2. Copertura per i danni di natura patrimoniale</span>(non conseguenti a lesioni e danni materiali a cose)<span style = \"font-weight:bold;\">inclusa la violazione privacy</span>.</br>           </li>           <li>             <span style = \"font-weight:bold;\"><br>3. Continuità assicurativa</span>: l’effettiva retroattività della copertura è di 10 anni.</li><li><br>4. Previsione di una<span style = \"font-weight:bold;\">copertura “postuma” di ultra attività di 10 anni</span>, da               attivare alla cessazione del rapporto di lavoro dipendente.            </li>         </ol>' WHERE id = 1");
        DB::statement("UPDATE product_declarations SET declaration = 'Di essere a conoscenza del “periodo di ripensamento” di 14 giorni dalla data di acquisto, per cancellare il contratto di assicurazione a distanza, con rimborso totale del premio (Direttiva 2011/83/Ue – D.lgs. 58/2014), segnalando il mio diritto di recesso tramite email a clienti@golinucci.it o info@pec.golinucci.it.' WHERE id = 2");
        
        DB::statement("UPDATE product_declarations SET declaration = 'Di aver preso visione dell’allegato 4 ter (<a href=\"https://www.golinucci.it/gndoc\">www.golinucci.it/gndoc</a>) sugli obblighi di comportamento cui gli intermediari sono tenuto nei confronti del Contraente ed Assicurati e dell’allegato 3 (<a href=\"https://www.golinucci.it/gndoc\">www.golinucci.it/gndoc</a>) e dell’allegato 4 (<a href=\"https://www.golinucci.it/gndoc\">www.golinucci.it/gndoc</a>) riguardante gli elementi informativi su Golinucci Srl.

        ' WHERE id = 3");
        DB::statement("UPDATE product_declarations SET declaration = 'Di aver autorizzato con lettera incarico (<a href=\"https://www.golinucci.it/gndoc\">www.golinucci.it/gndoc</a>) il broker di Assicurazione GOLINUCCI S.r.l. alla gestione dei contratti assicurativi selezionati online.' WHERE id = 4");
        DB::statement("UPDATE product_declarations SET declaration = 'Di aver presa visione del Set Informativo (<a href=\"https://www.golinucci.it/sil\">www.golinucci.it/sil</a>) relativo ai contratti selezionati online.

            ' WHERE id = 5");
        DB::statement("UPDATE product_declarations SET declaration = 'Che la Golinucci Srl mi ha reso noto il costo totale che l’Assicurato deve corrispondere, compresi i relativi oneri, spese ed imposte.' WHERE id = 6");
        DB::statement("UPDATE product_declarations SET declaration = 'Di autorizzare - ai sensi art. 61 del Regolamento IVASS n. 40 del 02/08/2018 (Modificato e Integrato dal Provvedimento IVASS N.97 del 04/08/2020) – la ricezione della documentazione precontrattuale e contrattuale su supporto durevole con cartaceo o tramite internet, anche tramite l’indirizzo di posta elettronica indicato dal cliente.' WHERE id = 7");
        DB::statement("UPDATE product_declarations SET declaration = 'Di essere a conoscenza che la Golinucci Srl opera attraverso il sito in qualità di distributore di prodotti assicurativi.' WHERE id = 8");
        DB::statement("UPDATE product_declarations SET declaration = 'Di autorizzare la Golinucci Srl al trattamento dei dati personali e dichiaro di aver preso visione dell’Informativa Privacy (<a href=\"https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf\">www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf</a>) fornitami ai sensi dell’Art. 13 EU 679/2016 Regolamento Europeo sul Trattamento dei Dati Personali GDPR.' WHERE id = 9");
        DB::statement("UPDATE product_declarations SET declaration = 'Di approvare l\'informativa privacy (<a href=\"https://www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf\">www.golinucci.it/wp-content/uploads/2023/09/informativa-clienti.pdf</a>)

            ' WHERE id = 10");
        DB::statement("UPDATE product_declarations SET declaration = 'Di essere a conoscenza che la presente proposta è relativa ad una polizza di responsabilità nella forma “Claims Made”. Ciò significa che la polizza copre le richieste di risarcimento presentate per la prima volta alle Persone Assicurate durante il periodo di validità della polizza e che potrebbero originare un reclamo futuro, a condizione che egli Assicuratori siano informati, durante il periodo di validità della polizza, delle Circostanze relative a tali eventi. Dopo la scadenza della polizza, nessun reclamo potrà essere avanzato a termini della polizza stessa, anche se l’evento che ha originato la richiesta danni si sia verificato durante il periodo di validità della polizza.' WHERE id = 11");
        DB::statement("UPDATE product_declarations SET declaration = 'Di essere a conoscenza che ai sensi dell’Art. 1341 e 1342 del Codice Civile, il sottoscritto Contraente dichiara di aver preso atto che questo è un contratto di assicurazione nella forma “CLAIMS MADE” a coprire le Richieste di Risarcimento fatte per la prima volta contro l’Assicurato durante il Periodo di Assicurazione e da lui denunciate agli Assicuratori durante lo stesso periodo.' WHERE id = 12");
        DB::statement("UPDATE product_declarations SET declaration = 'Di approvare il questionario di valutazione del Test di Coerenza del contratto di R.C. Professionale Colpa Grave ai sensi dell’art. 58 del Regolamento IVASS n. 40 del 02/08/2018 definito dalle Esigenze Assicurative:
        Protezione dalla Responsabilità Civile, ai sensi della legge 24/17 (c.d. Legge Gelli), nello svolgimento dell\'attività lavorativa, che provocano DANNI MATERIALI a Terzi quali lesioni a persone o danni a cose<br>•	Protezione dalla Responsabilità Civile, ai sensi della legge 24/17 (c.d. Legge Gelli), nello svolgimento dell\'attività lavorativa, che provocano DANNI IMMATERIALI a Terzi, come da indicazioni sulla proposta di assicurazione
        ' WHERE id = 13");
        DB::statement("UPDATE product_declarations SET declaration = 'Di confermare che le dichiarazioni rese sono conformi a verità di non aver omesso alcun elemento influente sulla valutazione del rischio' WHERE id = 14");

        DB::statement("UPDATE products SET payments_description = 'Per procedere con l\'emissione del contratto e la relativa copertura, è necessario effettuare il pagamento con una delle seguenti modalità: Online (PayPal - Carta di Credito) oppure<span style = \"font-weight:bold;\">Offline</span>(Bonifico Bancario – pagamento in ufficio).
        In caso di pagamento<span style = \"font-weight:bold;\">Offline</span>la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.
        ' WHERE id = 1");


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
