<?php

namespace App\Http\Requests\Api\AffiliateUser;

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
            'phone' => ['required','regex:/^(03|05|07|08|09)(\d{8})$/'],
            'password' => 'required|min:6',
            'email' => 'sometimes|email',
            'name' => 'required|min:3',
            //'type' => 'required|in:3,4',
            'group_affiliate_id' => 'required'
            //'references_code' => 'required_if:type,4',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại thành viên',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'email.email' => 'Email không đúng định dạng',
            'name.required' => 'Vui lòng nhập tên',
            'name.min' => 'Tên tối thiểu 3 ký tự',
            'group_affiliate_id.required' => 'Vui lòng chọn nhóm thành viên',
            //'references_code.required_if' => 'Vui lòng nhập thông tin người quản lý',
            //'name.min' => 'Tên tối thiểu 3 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 200 );
        throw (new ValidationException($validator, $response))->status(200);
    }
}
