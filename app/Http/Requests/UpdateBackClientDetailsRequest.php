<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBackClientDetailsRequest extends FormRequest
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
            'user_id' => ['required'],
            'first_name' => ['required', 'max:25'],
            'last_name' => ['required', 'max:25'],
            'fiscal_code' => ['nullable', 'max:16'],
            'gender' => ['nullable', 'in:M,F', 'max:1'],
            'dob' => ['nullable'],
            'phone' => ['nullable', 'max:30'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'province' => ['nullable', 'max:3'],
            'country' => ['nullable', 'max:2'],
            'postal_code' => ['nullable', 'max:5'],
            'product_client_details' => ['nullable', 'array'],
            'product_client_details.*.pivot.selected_field_value' => ['nullable'],
            'confirmation' => ['accepted'],
            'confirmation_sinistri' => ['required'],
            'confirmation_gmedici1' => ['required'],
            'confirmation_gmedici2' => ['required'],
            'confirmation_gmedici3' => ['required'],
            'confirmation_gmedici4' => ['required'],
            'confirmation_gmedici5' => ['required']
        ];
    }

}
