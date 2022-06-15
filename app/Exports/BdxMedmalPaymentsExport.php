<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function PHPSTORM_META\map;

class BdxMedmalPaymentsExport implements FromQuery, WithHeadingRow
{
    use Exportable;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    public function headings(): array
    {
        return [
            'NOME',
            'COGNOME',
            'CODICE FISCALE',
            'INDIRIZZO',
            'CAP',
            'CITTA\'',
            'PROVINCIA',
            'NAZIONE',
            'EMAIL',
            'TELEFONO UFFICIO', // fisso 0
            'TELEFONO CELLULARE',
            'PARTITAIVA', // vuoto
            'TIPOATTIVITA',
            'START DATE',
            'END DATE',
            'ACCOUNT EXECUTIVE', // fisso Paolo Golinucci
            'ACCOUNT HANDLER', // fisso Paolo Golinucci
            'POLICY NUMBER', // num polizza Lloyds (meglio) oppure  se non riesco quello contabile
            'PREMIUM (EXCL. TAX)', // formattato con virgola, tipo 40,7
            'TAX',
            'COMMISSION',
            'ADMIN FEE',
            'TOTAL PREMIUM + TAX + ADMIN FEE',
            'INVOICE NUMBER',
            'INTRODUCER', // fisso 50464394
            'INTRODUCER 2', // vuoto
            'INSURER', // fisso Lloyd's
            'INSURER ACCOUNT', // fisso Carpenter Colpa Grave
            'POLICY DESCRIPTION', // come INVOICE NUMBER
            'CONTACT REF',
            'CONTACT NOTES', // specializzazione
            'DATA DI NASCITA', // formato giorno/mese/anno
            'BUSINESS EVENT', // fisso New business
            'VERSION REF',
            'REMARKETED' // ?
        ];
    }

    public function query()
    {
        $payments_data = Payment::query();

        if (!is_null($this->date)) {
            $payments_data->whereDate('paid_at', $this->date);
        }
        return $payments_data;
    }
}
