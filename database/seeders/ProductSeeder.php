<?php

namespace Database\Seeders;

use App\Models\ConditionalField;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditionalFields = ConditionalField::all();

        Product::factory()
            ->hasAttached($conditionalFields)
            ->create([
                'name' => 'Polizza Singola Medici e Personale Sanitario',
                'account_id' => '1',
                'product_type_id' => '1',
                'pre_declarations' => 'Io sottoscritto/a in qualità di assicurato della polizza RC colpa Grave e Patrimoniale, in ottemperanza del Regolamento IVASS n. 40 del 02/08/2018 (Modificato ed Integrato dal Provvedimento IVASS N.97 del 04/08/2020), prima di essere vincolato da un contratto offerto mediante tecniche di comunicazione a distanza, dichiaro:',
                'post_declarations' => 'Testo post dichiarazioni',
                'hex_color' => '#6CB8EB',
                'conditions' => '<p>
            La polizza è aggiornata alle disposizioni contenute nella Legge 24/17 (Legge Gelli),
            prevede:
          </p>
          <ol class="list-decimal list-inside">
            <li>
              <b>Apertura del sinistro fin dalla ricezione di comunicazione</b> da parte
              dell’Azienda di appartenenza, riguardante l’inizio delle trattative stragiudiziali
              con il paziente ai sensi dell’<b>art. 13 della Legge Gelli</b>, con maggiore
              tutela dell’assicurato in quanto non deve aspettare anni per dare comunicazione
              dell’eventuale ricezione di un “invito a dedurre” da parte della Corte dei Conti o
              altro tribunale competente.
            </li>
            <li>
              <b>Copertura per i danni di natura patrimoniale</b> (non lesioni a persona ma di
              natura “immateriale”) <b>inclusa la violazione privacy</b>.
            </li>
            <li>
              <b>Continuità assicurativa</b>: l’effettiva retroattività della copertura è di 10
              anni + i precedenti anni di copertura con Golinucci Srl.
            </li>
            <li>
              Previsione di una <b>copertura “postuma” di ultra attività di 10 anni</b>, da
              attivare alla cessazione del rapporto di lavoro dipendente.
            </li>
          </ol>',
                'payments_description' => "<p>Per consentire l'emissione, e la relativa copertura, nella data di pagamento è necessario l’utilizzo di una opzione di pagamento Online mentre, se il metodo scelto è quello Offline, la transazione si concluderà manualmente nel giorno lavorativo successivo a quello di data valuta di pagamento con bonifico bancario. Anche l’invio del certificato avverrà manualmente da parte di Golinucci srl nei giorni successivi alla data di valuta del pagamento.</p>",
                'active' => 1
            ]);

        // Product::factory(5)
        //     ->create();
    }
}
