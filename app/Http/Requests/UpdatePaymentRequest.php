<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'cart_id' => ['required'],
            'payment_gateway_id' => ['required'],
            'paid' => ['boolean'],
            'transaction_ref' => ['nullable', 'max:125'],
            'paid_at' => ['nullable', 'required_unless:paid,false', 'date'],
            'payment_no' => ['nullable', 'max:125'],
        ];
    }
}
