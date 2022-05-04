<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['required', 'max:50'],
            'product_type_id' => ['required', 'integer'],
            'description' => ['required'],
            'detailed_description' => ['required'],
            'conditions' => ['required'],
            'hex_color' => ['required'],
            'icon' => ['required', 'image'],
            'active' => ['required', 'boolean'],
        ];
    }
}
