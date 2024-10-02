<table>
    <thead>
        <tr>

        </tr>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Causale</th>
            <th>Total Payment</th>
            <th>Transaction Ref PayPal</th>
            <th>Data di creazione</th>
            <th>Data di pagamento</th>
            <th>Professione</th>
            <th>Codice Fiscale</th>
            <th>Data di nascita</th>
            <th>Telefono</th>
            <th>Indirizzo</th>
            <th>Città</th>
            <th>CAP</th>
            <th>Provincia</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->name }}</td>
            <td>{{ $payment->surname }}</td>
            <td>{{ $payment->email }}</td>
            <td>{{ $payment->causale }}</td>
            <td>{{ $payment->total_payable }}</td>
            <td>{{ $payment->transaction_ref }}</td>
            <td>{{ date('d/m/Y', strtotime($payment->created_at)) }}</td>
            <td>@if ($payment->paid)
                {{ date('d/m/Y', strtotime($payment->updated_at)) }}
                @else
                Non pagato
                @endif
            <td>{{ $payment->profession }}</td>
            <td>{{ $payment->fiscalcode }}</td>
            <td>{{ $payment->dob }}</td>
            <td>{{ $payment->phone }}</td>
            <td>{{ $payment->via }}</td>
            <td>{{ $payment->citta }}</td>
            <td>{{ $payment->cap }}</td>
            <td>{{ $payment->provincia }}</td>





            
        </tr>
        @endforeach
    </tbody>
</table>