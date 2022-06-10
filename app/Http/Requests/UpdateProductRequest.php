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
            'name' => ['required', 'max:125'],
            'product_type_id' => ['required', 'integer'],
            'description' => ['required', 'not_regex:/^(<p><\/p>)$/i'],
            'detailed_description' => ['required', 'not_regex:/^(<p><\/p>)$/i'],
            'conditions' => ['required', 'not_regex:/^(<p><\/p>)$/i'],
            'pre_declarations' => ['required', 'not_regex:/^(<p><\/p>)$/i'],
            'post_declarations' => ['not_regex:/^(<p><\/p>)$/i'],
            'payments_description' => ['required', 'not_regex:/^(<p><\/p>)$/i'],
            'hex_color' => ['required'],
            //'image' => ['required', 'image'],
            'active' => ['required', 'boolean'],
        ];
    }
}
