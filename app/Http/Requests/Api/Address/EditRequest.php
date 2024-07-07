<?php

namespace App\Http\Requests\Api\Address;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EditRequest extends FormRequest
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
            'phone' => ['required'],
            'address' => 'required',
            'province_id' => 'required|numeric|min:1',
            'district_id' => 'required|numeric|min:1',
            'ward_id' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.required' => 'Vui lòng nhập địa chỉ khách hàng',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành',
            'province_id.numeric' => 'Vui lòng chọn tỉnh/thành',
            'province_id.min' => 'Vui lòng chọn tỉnh/thành',
            'district_id.required' => 'Vui lòng chọn quận/huyện',
            'district_id.numeric' => 'Vui lòng chọn quận/huyện',
            'district_id.min' => 'Vui lòng chọn quận/huyện',
            'ward_id.required' => 'Vui lòng chọn phường/xã',
            'ward_id.numeric' => 'Vui lòng chọn phường/xã',
            'ward_id.min' => 'Vui lòng chọn phường/xã',
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
