<?php

namespace App\Http\Requests\Admin\Sale;

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
        $rule = [
            'name' => 'required',
            'email' => 'sometimes|email',
            'password' => 'nullable|min:6',
            //'group_affiliate_id' => 'sometimes|required',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'email.required' => 'Vui lòng nhập email khách hàng',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            //'group_affiliate_id.required' => 'Vui lòng chọn nhóm khách hàng',
        ];
    }
}
