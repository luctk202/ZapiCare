<?php

namespace App\Http\Requests\Admin\Blogs;

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
            //'category_id' => 'required',
            'title' => 'required',
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
