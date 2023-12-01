<table>
    <thead>
        <tr>

        </tr>
        <tr>
            <th>NOME</th>
            <th>COGNOME</th>
            <th>CODICE FISCALE</th>
            <th>INDIRIZZO</th>
            <th>CAP</th>
            <th>CITTA'</th>
            <th>PROVINCIA</th>
            <th>NAZIONE</th>
            <th>EMAIL</th>
            <th>TELEFONO UFFICIO</th>
            <th>TELEFONO CELLULARE</th>
            <th>FIRST/CISL TERRITORIALE DI APPARTENENZA</th>
            <th>BANCA</th>
            <th>CODICE ABI</th>
            <th>START DATE</th>
            <th>END DATE</th>
            <th>Massimale Totale</th>
            <th>Totale Massimale Spese Legali</th>
            <th>Combinazione Scelta</th>
            <th>Cassieri Massimale per SINISTRO e per ANNO</th>
            <th>R.C Patrimoniale Massimale per SINISTRO e per ANNO</th>
            <th>Spese Legali Massimale per SINISTRO e per ANNO</th>
            <th>R.C. Patrimoniale Massimale per SINISTRO e per ANNO (eccesso)</th>
            <th>Spese Legali Massimale per SINISTRO e per ANNO (eccesso)</th>
            <th>Cashier N Certificato</th>
            <th>Financial N Certificato</th>
            <th>Fibaer N Certificato</th>
            <th>SARA N Certificato</th>
            <th>Policy Number</th>
            <th>Cashier Premium (excl. tax)</th>
            <th>Cashier Tax</th>
            <th>Cashier Commission</th>
            <th>Financial Premium (excl. tax)</th>
            <th>Financial Tax</th>
            <th>Financial Commission</th>
            <th>Fibaer Premium (excl. tax)</th>
            <th>Fibaer Tax</th>
            <th>Fibaer Commission</th>
            <th>Fibaer SARA Premium (excl. tax)</th>
            <th>Fibaer SARA Tax</th>
            <th>COMMISSION</th>
            <th>Admin Fee</th>
            <th>Invoice No</th>
            <th>TOTAL PREMIUM + TAX + ADMIN FEE</th>
            <th>Introducer</th>
            <th>Introducer 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->first_name }}</td>
            <td>{{ $payment->last_name }}</td>
            <td>{{ $payment->fiscal_code }}</td>
            <td>{{ $payment->address }}</td>
            <td>{{ $payment->postal_code }}</td>
            <td>{{ $payment->city }}</td>
            <td>{{ $payment->province }}</td>
            <td></td>
            <td>{{ $payment->email }}</td>
            <td>{{ $payment->office_phone }}</td>
            <td>{{ $payment->personal_phone }}</td>
            <td>
                {{ $payment->first_appartenenza }}
            </td>
            <td>{{ $payment->bank }}</td>
            <td>{{ $payment->abi }}</td>
            <td>
                @if ($payment->renewal_date)
                {{ date('d/m/Y', strtotime($payment->renewal_date)) }}
                @elseif ($payment->paid_at)
                {{ date('d/m/Y', strtotime($payment->paid_at)) }}
                @else
                {{ '' }}
                @endif
            </td>
            <td>
                {{ date('d/m/Y', strtotime($payment->end_validity_date)) }}
            </td>

            
            <td>{{ $payment->Q }}</td>
            <td>{{ $payment->R }}</td>
            <td>{{ $payment->S}}</td>
            <td>{{ $payment->T }}</td>
            <td>{{ $payment->U }}</td>
            <td>{{ $payment->V }}</td>
            <td>{{ $payment->W }}</td>
            <td>{{ $payment->X }}</td>
            <td>{{ $payment->Y }}</td>
            <td>{{ $payment->Z }}</td>
            <td>{{ $payment->AA }}</td>
            <td>{{ $payment->AB }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->AD }}</td>
            <td>{{ $payment->AE }}</td>
            <td>{{ $payment->AF }}</td>
            <td>{{ $payment->AG }}</td>
            <td>{{ $payment->AH }}</td>
            <td>{{ $payment->AI }}</td>
            <td>{{ $payment->AJ }}</td>
            <td>{{ $payment->AK }}</td>
            <td>{{ $payment->AL }}</td>
            <td>{{ $payment->AM }}</td>
            <td>{{ $payment->AN }}</td>
            <td>{{ $payment->AO }}</td>
            <td>{{ $payment->AP }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->AR }}</td>
            <td>{{ $payment->AS }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>