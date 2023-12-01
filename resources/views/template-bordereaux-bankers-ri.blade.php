<table>
    <thead>
        <tr>

        </tr>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Quote Ref</th>
            <th>Date of Payment</th>
            <th>Total Payment</th>
            <th>Combinazione</th>
            <th>Coverage From</th>
            <th>To</th>
            <th>Total Premium</th>
            <th>Fee</th>
            <th>Invoice No</th>
            <th>Total Cost</th>
            <th>Payment Method</th>
            <th>Codice Fiscale</th>
            <th>ADDRESS</th>
            <th>POST CODE</th>
            <th>CITY</th>
            <th>PROV</th>
            <th>CODICE FISCALE</th>
            <th>EMAIL</th>
            <th>WORK</th>
            <th>MOBILE</th>
            <th>BANK CODE</th>
            <th>BANK NAME</th>
            <th>UNION PROVINCE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->first_name }}</td>
            <td>{{ $payment->last_name }}</td>
            <td></td>
            <td>{{ date('d/m/Y', strtotime($payment->paid_at)) }}</td>
            <td>{{ $payment->total_payable }}</td>
            <td>{{ $payment->combination }}</td>
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
            <td>{{ $payment->gross_premium }}</td>
            <td>{{ $payment->admin_fee }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->total_payable }}</td>
            <td>
                @if ($payment->payment_gateway_id == 1)
                Paypal
                @elseif ($payment->payment_gateway_id == 2)
                BBM
                @elseif ($payment->payment_gateway_id == 3)
                Office Payment
                @endif
            </td>
            <td>{{ $payment->fiscal_code }}</td>
            <td>{{ $payment->address }}</td>
            <td>
                {{ $payment->postal_code }}
            </td>
            <td>{{ $payment->city }}</td>
            <td>{{ $payment->province }}</td>
            

            
            <td>{{ $payment->fiscal_code }}</td>

            <td>{{ $payment->email }}</td>
            <td>{{ $payment->office_phone}}</td>
            <td>{{ $payment->personal_phone }}</td>
            <td>{{ $payment->abi }}</td>
            <td>{{ $payment->bank }}</td>
            <td>
                @if ($payment->regione == 1)
                Abruzzo
                @elseif ($payment->regione == 2)
                Basilicata
                @elseif ($payment->regione == 3)
                Calabria
                @elseif ($payment->regione == 4)
                Campania
                @elseif ($payment->regione == 5)
                Friuli Venezia Giulia
                @elseif ($payment->regione == 6)
                Lazio
                @elseif ($payment->regione == 7)
                Liguria
                @elseif ($payment->regione == 8)
                Lombardia
                @elseif ($payment->regione == 9)
                Marche
                @elseif ($payment->regione == 10)
                Molise
                @elseif ($payment->regione == 11)
                Piemonte
                @elseif ($payment->regione == 12)
                Puglia
                @elseif ($payment->regione == 13)
                Sardegna
                @elseif ($payment->regione == 14)
                Sicilia
                @elseif ($payment->regione == 15)
                Toscana
                @elseif ($payment->regione == 16)
                Trentino Alto Adige
                @elseif ($payment->regione == 17)
                Umbria
                @elseif ($payment->regione == 18)
                Val d'Aosta
                @elseif ($payment->regione == 19)
                Veneto
                @endif    
            </td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>