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
            <th>Cassa Uniqa</th>
            <th>Uniqa RC</th>
            <th>Chubb RC</th>
            <th>sara rc</th>
            <th>Total Premium</th>
            <th>Fee</th>
            <th>Invoice No</th>
            <th>Total Cost</th>
            <th>Payment Method</th>
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
            <th>MASSIMALE CASSIERE</th>
            <th>MASSIMALE PATRIMONIALE</th>
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
            <td>{{ $payment->italiana_cassa }}</td>
            <td>{{ $payment->italiana_patrimoniale }}</td>
            <td>{{ $payment->chubb_patrimoniale }}</td>
            <td>{{ $payment->sara_patrimoniale }}</td>
            <td>{{ $payment->total_premium }}</td>
            <td>{{ $payment->fee }}</td>
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
            <td>{{ $payment->first_appartenenza }}</td>
            <td>{{ $payment->massimale_cassieri }}</td>
            <td>{{ $payment->massimale_patrimoniale }}</td>
            
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>