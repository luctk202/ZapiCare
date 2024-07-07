<?php

namespace App\Http\Requests\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'discount_value' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'discount_value.required' => 'Vui lòng giá trị chiết khấu',
            'discount_value.numeric' => 'Vui lòng giá trị chiết khấu',
            'discount_value.min' => 'Vui lòng giá trị chiết khấu',
        ];
    }
}
