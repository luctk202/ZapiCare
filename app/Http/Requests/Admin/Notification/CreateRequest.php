<?php

namespace App\Http\Requests\Admin\Notification;

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
            'title' => 'required',
            'text' => 'required',
            //icon' => 'required|image',
            'time_send' => 'required',
            //'user_type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề thông báo',
            'text.required' => 'Vui lòng nhập nội dung thông báo',
            'user_type.required' => 'Vui lòng chọn đối tượng nhận thông báo',
            //'icon.required' => 'Vui lòng nhập icon',
            //'icon.image' => 'Vui lòng nhập icon',
            'time_send.required' => 'Vui lòng nhập thời gian gửi thông báo',
        ];
    }
}
