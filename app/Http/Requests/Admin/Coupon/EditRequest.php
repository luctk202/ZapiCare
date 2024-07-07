<?php

namespace App\Http\Requests\Admin\Coupon;

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
            'code' => 'required|unique:coupon,code,'.$this->coupon,
            'user_id' => 'required',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên cửa hàng',
            'code.required' => 'Vui lòng nhập mã số thuế',
            'code.unique' => 'Mã số thuế đã tồn tại',
            'user_id.required' => 'Vui lòng nhập quản lý cửa hàng',
        ];
    }
}
