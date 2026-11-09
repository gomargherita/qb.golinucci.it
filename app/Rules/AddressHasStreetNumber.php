<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * KAN-14: Margherita — civico obbligatorio sull’indirizzo.
 */
class AddressHasStreetNumber implements Rule
{
    public function passes($attribute, $value)
    {
        if ($value === null || trim((string) $value) === '') {
            return true;
        }

        return (bool) preg_match('/\d/u', (string) $value);
    }

    public function message()
    {
        return 'Inserisci anche il numero civico (es. Via Roma 12).';
    }
}
