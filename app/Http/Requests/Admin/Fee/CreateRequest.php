<?php

namespace App\Http\Requests\Admin\Fee;

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
            'weight' => 'required',
            'price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'weight.required' => 'Vui lòng nhập khối lượng lớn nhất',
            'price.required' => 'Vui lòng nhập phí vận chuyển',
        ];
    }
}
