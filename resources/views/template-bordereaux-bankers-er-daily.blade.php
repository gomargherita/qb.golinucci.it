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

            
            <td>{{ $payment->massimale_patrimoniale }}</td>
            <td>{{ $payment->massimale_spese_legali }}</td>
            <td>{{ $payment->combinazione}}</td>
            <td>{{ $payment->massimale_cassieri }}</td>
            <td>{{ $payment->massimale_patrimoniale }}</td>
            <td>{{ $payment->massimale_spese_legali }}</td>
            <td>{{ $payment->rc_eccesso }}</td>
            <td>{{ $payment->spese_legali_eccesso }}</td>
            <td>{{ "2025/076340933" }}</td>
            <td>{{ "2025/076340933" }}</td>
            <td>{{ "ITFINC086553" }}</td>
            <td>{{ "32539JX" }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->netto_italiana_cassa }}</td>
            <td>{{ $payment->imposta_cassieri }}</td>
            <td>{{ "30" }}</td>
            <td>{{ $payment->netto_italiana_patrimoniale }}</td>
            <td>{{ $payment->imposta_patrimoniale }}</td>
            <td>{{ "30" }}</td>
            <td>{{ $payment->netto_chubb_patrimoniale }}</td>
            <td>{{ $payment->imposta_chubb_patrimoniale }}</td>
            <td>{{ "30" }}</td>
            <td>{{ $payment->netto_sara_patrimoniale }}</td>
            <td>{{ $payment->imposta_sara_patrimoniale }}</td>
            <td>{{ "20" }}</td>
            <td>{{ $payment->fee }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->total_cost }}</td>
            <td>{{ "27556400" }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>