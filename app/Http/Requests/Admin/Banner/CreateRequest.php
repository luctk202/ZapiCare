<?php

namespace App\Http\Requests\Admin\Banner;

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
        return [
            'name' => 'required',
            'position' => 'required',
            'image' => 'required|image',
//            'link' => 'active_url',
            'time' => 'required'
            //'product_number' =>
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'position.required' => 'Vui lòng chọn vị trí',
            'image.required' => 'Vui lòng nhập ảnh',
            'image.image' => 'Vui lòng nhập ảnh',
            'link.active_url' => 'Link không đúng định dạng',
            'time.required' => 'Vui lòng nhập thời gian hiển thị',
        ];
    }
}
