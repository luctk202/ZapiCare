<?php

namespace App\Http\Requests\Api\OrderWholesale;

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
            'shop_id' => 'required',
            'payment_method' => 'required|in:2,1',
            'products' => 'required|array',
            'name' => 'required',
            'phone' => ['required'],
            'address' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'shop_id.required' => 'Vui lòng chọn cửa hàng cần đặt',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không đúng',
            'products.required' => 'Vui lòng thêm sản phẩm cần thanh toán',
            'products.array' => 'Vui lòng thêm sản phẩm cần thanh toán',
            'name.required' => 'Vui lòng nhập tên người nhận hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận hàng',
            'phone.regex' => 'Số điện thoại người nhận hàng không đúng định dạng',
            'address.required' => 'Vui lòng nhập địa chỉ nhận hàng',
            'province_id.required' => 'Vui lòng nhập địa chỉ nhận hàng',
            'district_id.required' => 'Vui lòng nhập địa chỉ nhận hàng',
            'ward_id.required' => 'Vui lòng nhập địa chỉ nhận hàng',
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
