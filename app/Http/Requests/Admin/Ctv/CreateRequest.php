<?php

namespace App\Http\Requests\Admin\Ctv;

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
            'phone' => ['required', 'regex:/^(09|03|07|08|05)\d{8}$/'],
            'email' => 'sometimes|email',
            'password' => 'required|min:6',
            'group_affiliate_id' => 'required',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'email.required' => 'Vui lòng nhập email khách hàng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'group_affiliate_id.required' => 'Vui lòng chọn nhóm khách hàng',
        ];
    }
}
