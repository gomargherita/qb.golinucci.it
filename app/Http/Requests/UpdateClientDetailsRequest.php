<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateClientDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'max:25'],
            'last_name' => ['required', 'max:25'],
            'fiscal_code' => ['required', 'max:16'],
            'phone' => ['required', 'max:30'],
            'address' => ['required', 'max:150'],
            'city' => ['required', 'max:50'],
            'region' => ['required', 'max:50'],
            'country' => ['required', 'max:2'],
            'postal_code' => ['required', 'max:5'],
            'contact_ref' => ['nullable', 'max:25'],
            'varsion_ref' => ['nullable', 'max:25'],
            'note' => ['nullable'],
            'with_accident' => ['boolean'],
            'flag_prev_2022' => ['boolean'],
        ];
    }
}
