<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
        $rule = [
            'user_id' => 'required',
            'products' => 'required|array'
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Vui lòng nhập thông tin khách hàng',
            'products.required' => 'Vui lòng nhập thông tin sản phẩm',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response($json, 200);
        throw (new ValidationException($validator, $response))->status(200);
    }
}
