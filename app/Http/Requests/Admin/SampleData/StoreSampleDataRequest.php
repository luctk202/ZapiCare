<?php

namespace App\Http\Requests\Admin\SampleData;

use Illuminate\Foundation\Http\FormRequest;

class StoreSampleDataRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'test_item_id' => 'required|exists:test_items,id',
//            'level_id' => 'required|exists:levels,id',
//            'range_min' => 'required|numeric',
//            'range_max' => 'required|numeric',
            'explanation' => 'nullable|string',
            'symptom' => 'nullable|string',
            'disease' => 'nullable|string',
            'advice' => 'nullable|string',
            'product_ids' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'test_item_id.required' => 'Tên mục kiểm tra là bắt buộc.',
            'test_item_id.exists' => 'Tên mục kiểm tra không hợp lệ.',
//            'level_id.required' => 'Cấp độ là bắt buộc.',
//            'level_id.exists' => 'Cấp độ không hợp lệ.',
//            'range_min.required' => 'Khoảng min là bắt buộc.',
//            'range_min.numeric' => 'Khoảng min phải là một số.',
//            'range_max.required' => 'Khoảng max là bắt buộc.',
//            'range_max.numeric' => 'Khoảng max phải là một số.',
            'explanation.nullable' => 'Lý giải chỉ số có thể để trống.',
            'explanation.string' => 'Lý giải chỉ số phải là chuỗi ký tự.',
            'symptom.nullable' => 'Dấu hiệu thường gặp có thể để trống.',
            'symptom.string' => 'Dấu hiệu thường gặp phải là chuỗi ký tự.',
            'disease.nullable' => 'Bệnh lý thường gặp có thể để trống.',
            'disease.string' => 'Bệnh lý thường gặp phải là chuỗi ký tự.',
            'advice.nullable' => 'Tư vấn có thể để trống.',
            'advice.string' => 'Tư vấn phải là chuỗi ký tự.',
            'product_ids.required' => 'Sản phẩm gợi ý là bắt buộc.',
            'product_ids.array' => 'Sản phẩm gợi ý phải là một mảng.',
            'product_ids.*.exists' => 'Một trong những sản phẩm gợi ý không tồn tại.',
        ];
    }
}
