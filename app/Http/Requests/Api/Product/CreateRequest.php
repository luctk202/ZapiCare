<?php

namespace App\Http\Requests\Api\Product;

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
            'category_id' => 'required',
            'avatar' => 'required|image',
            'images' => 'sometimes|required|array',
            'files' => 'sometimes|required|array',
            'stocks' => 'required|json',
            'tax_value' => 'required|numeric',
            'tax_type' => 'required|in:1,2',
            'vat_value' => 'required|numeric',
            'vat_type' => 'required|in:1,2',
            'warranty_value' => 'required|numeric',
            'warranty_type' => 'required|in:1,2,3',
        ];

        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm',
            'avatar.required' => 'Vui lòng chọn ảnh đại diện',
            'avatar.image' => 'Vui lòng chọn ảnh đại diện',
            'images.required' => 'Vui lòng chọn ảnh sản phẩm',
            'files.required' => 'Vui lòng chọn tài liệu kèm theo',
            'stocks.required' => 'Vui lòng nhập thông tin giá',
            'stocks.json' => 'Vui lòng nhập thông tin giá',
            'tax_value.required' => 'Vui lòng nhập thông tin thuế',
            'tax_value.numeric' => 'Vui lòng nhập thông tin thuế',
            'tax_type.required' => 'Vui lòng nhập thông tin thuế',
            'tax_type.in' => 'Vui lòng nhập thông tin thuế',
            'vat_value.required' => 'Vui lòng nhập thông tin GTGT',
            'vat_value.numeric' => 'Vui lòng nhập thông tin GTGT',
            'vat_type.required' => 'Vui lòng nhập thông tin GTGT',
            'vat_type.in' => 'Vui lòng nhập thông tin GTGT',
            'warranty_value.required' => 'Vui lòng nhập thông tin bảo hành',
            'warranty_value.numeric' => 'Vui lòng nhập thông tin bảo hành',
            'warranty_type.required' => 'Vui lòng nhập thông tin bảo hành',
            'warranty_type.in' => 'Vui lòng nhập thông tin bảo hành',
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
