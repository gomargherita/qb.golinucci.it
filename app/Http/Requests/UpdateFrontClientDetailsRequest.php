<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateFrontClientDetailsRequest extends FormRequest
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
            'fiscal_code' => ['required', 'max:16'],
            'gender' => ['required', 'in:M,F', 'max:1'],
            'dob' => ['date'],
            'phone' => ['required', 'max:30', 'phone:IT,mobile'],
            'address' => ['required', 'max:150'],
            'city' => ['required', 'max:50'],
            'province' => ['required', 'max:2'],
            'country' => ['required', 'max:2'],
            'postal_code' => ['required', 'max:5'],
            'product_client_details' => ['required', 'array'],
            'product_client_details.*.pivot.selected_field_value' => ['required'],
            'confirmation' => ['accepted'],
            'confirmation_sinistri' => ['required']
        ];
    }

}
