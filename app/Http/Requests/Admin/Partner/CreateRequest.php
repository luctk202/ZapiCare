<?php

namespace App\Http\Requests\Admin\Partner;

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
            'code' => 'required|unique:partners,code',
            'user_id' => 'required',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên cửa hàng',
            'code.required' => 'Vui lòng nhập mã số thuế',
            'code.code' => 'Mã số thuế đã tồn tại',
            'user_id.required' => 'Vui lòng nhập quản lý đối tác',
        ];
    }
}
