<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

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
            'product_id' => 'required_if:type,1',
            'title' => 'required',
            'type' => 'required',
            //'description' => 'required',
            //'buyer' => 'required',
            //'address' => 'sometimes|required'
        ];
        return $rule;
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'type.required' => 'Vui lòng loại bài viết',
            'product_id.required_if' => 'Vui lòng chọn sản phẩm',
        ];
    }

    public function withValidator($validator)
    {
        /*$validator->errors()->add('field', 'Something is wrong with this field!');
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });*/
    }
}
