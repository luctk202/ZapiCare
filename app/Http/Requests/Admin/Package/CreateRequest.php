<?php

namespace App\Http\Requests\Admin\Package;

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
            'name' => 'required',
            'time_type' => 'required|in:1,2,3',
            //'product_number' =>
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
        ];
    }
}
