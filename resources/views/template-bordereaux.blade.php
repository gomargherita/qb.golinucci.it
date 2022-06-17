<table>
    <thead>
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
            <th>PARTITAIVA</th>
            <th>TIPOATTIVITA</th>
            <th>START DATE</th>
            <th>END DATE</th>
            <th>ACCOUNT EXECUTIVE</th>
            <th>ACCOUNT HANDLER</th>
            <th>POLICY NUMBER</th>
            <th>PREMIUM (EXCL. TAX)</th>
            <th>TAX</th>
            <th>COMMISSION</th>
            <th>ADMIN FEE</th>
            <th>TOTAL PREMIUM + TAX + ADMIN FEE</th>
            <th>INVOICE NUMBER</th>
            <th>INTRODUCER</th>
            <th>INTRODUCER 2</th>
            <th>INSURER</th>
            <th>INSURER ACCOUNT</th>
            <th>POLICY DESCRIPTION</th>
            <th>CONTACT REF</th>
            <th>CONTACT NOTES</th>
            <th>DATA DI NASCITA</th>
            <th>BUSINESS EVENT</th>
            <th>VERSION REF</th>
            <th>REMARKETED</th>
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
            <td>{{ $payment->country }}</td>
            <td>{{ $payment->email }}</td>
            <td>0</td>
            <td>{{ $payment->phone }}</td>
            <td></td>
            <td>
                @switch($payment->tipoattivita)
                @case(1)
                Dirigente Medico
                @break

                @case(2)
                Medico Convenzionato
                @break

                @case(3)
                Medico Specialista in Formazione
                @break

                @case(4)
                Personale sanitario
                @break

                @case(5)
                Dirigente Sanitario non Medico
                @break

                @case(6)
                Dirigente Infermieristico
                @break

                @endswitch
            </td>
            <td>{{ $payment->renewal_date ? date('d/m/Y', strtotime($payment->renewal_date)) : '' }}</td>
            <td>{{ $payment->renewal_date ? date('d/m/Y', strtotime("+12 months", strtotime($payment->renewal_date))) : '' }}</td>
            <td>Paolo Golinucci</td>
            <td>Paolo Golinucci</td>
            <td>{{ $payment->doprint_certificate_no}}</td>
            <td>{{ $payment->net_plus_access_premium }}</td>
            <td>{{ $payment->tax }}</td>
            <td>24,5454</td>
            <td>{{ $payment->admin_fee }}</td>
            <td>{{ $payment->total_payable }}</td>
            <td>{{ $payment->payment_no }}</td>
            <td>50464394</td>
            <td></td>
            <td>Lloyd's</td>
            <td>Carpenter Colpa Grave</td>
            <td>{{ $payment->payment_no }}</td>
            <td>{{ $payment->contact_ref }}</td>
            <td>{{ $payment->contactnotes }}</td>
            <td>{{ $payment->dob ? date('d/m/Y', strtotime($payment->dob)) : '' }}</td>
            <td>New business</td>
            <td>{{ $payment->version_ref }}</td>
            <td>{{ ($payment->contact_ref) ? 'Yes' : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>