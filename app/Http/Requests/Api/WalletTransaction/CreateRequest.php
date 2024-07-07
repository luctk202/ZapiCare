<?php

namespace App\Http\Requests\Api\WalletTransaction;

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
        return [
            'type' => 'required|in:1,2',
            //'user_id' => 'required',
            'payment_method' => 'required|in:1,2,3',
            'amount' => 'required|numeric|min:10000'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Vui lòng chọn nạp/rút ví',
            'type.in' => 'Vui lòng chọn nạp/rút ví',
            'user_id.required' => 'Vui lòng chọn thành viên cần nạp',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Vui lòng chọn phương thức thanh toán',
            'amount.required' => 'Vui lòng nhập số tiền cần nap/rút',
            'amount.numeric' => 'Số tiền cần nap/rút tối thiểu 10.000 VND',
            'amount.min' => 'Số tiền cần nap/rút tối thiểu 10.000 VND',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'status' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 200 );
        throw (new ValidationException($validator, $response))->status(200);
    }
}
