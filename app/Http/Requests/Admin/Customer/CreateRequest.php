<?php

namespace App\Http\Requests\Admin\Customer;

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
            'name' => 'required',
//            'code' => 'required|unique:users,code',
            'phone' => ['required', 'regex:/^(09|03|07|08|05)\d{8}$/', 'unique:users,phone'],
            'email' => 'sometimes|email',
            'password' => 'required|min:6',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
//            'code.required' => 'Vui lòng nhập căn cước công dân',
//            'code.unique' => 'Tài khoản đã tồn tại',
            'phone.unique' => 'Tài khoản đã tồn tại',
            'phone.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'email.required' => 'Vui lòng nhập email khách hàng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
        ];
    }
}
