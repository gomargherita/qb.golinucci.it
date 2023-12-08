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
        if(auth()->user()->owner == 1){
            return [
                'first_name' => ['required', 'max:25'],
                'last_name' => ['required', 'max:25'],
                'fiscal_code' => ['nullable', 'max:16'],
                'gender' => ['nullable', 'in:M,F', 'max:1'],
                'dob' => ['nullable', 'date'],
                'phone' => ['nullable', 'max:30'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'province' => ['nullable', 'max:2'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:5'],
                'contact_ref' => ['nullable', 'max:25'],
                'varsion_ref' => ['nullable', 'max:25'],
                'note' => ['nullable'],
                'with_accident' => ['boolean'],
                'flag_prev_2022' => ['boolean'],
            ];
        }
        else{
            return [
                'first_name' => ['required', 'max:25'],
                'last_name' => ['required', 'max:25'],
                'fiscal_code' => ['required', 'max:16'],
                'gender' => ['required', 'in:M,F', 'max:1'],
                'dob' => ['nullable', 'date'],
                'phone' => ['required', 'max:30', 'phone:IT,mobile'],
                'address' => ['required', 'max:150'],
                'city' => ['required', 'max:50'],
                'province' => ['required', 'max:2'],
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
}
