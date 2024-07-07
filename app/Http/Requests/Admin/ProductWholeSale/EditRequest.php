<?php

namespace App\Http\Requests\Admin\ProductWholeSale;

use Illuminate\Foundation\Http\FormRequest;

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
            //'producer_id' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            //'avatar' => 'required|image'
        ];
        if(request('attributes')){
            $attributes = request('attributes');
            foreach ($attributes as $value){
                if(request('attribute_values_'.$value)){
                    $rule['product_attribute_price_sell.*'] = 'required|integer';
                }
            }
        }else{
            $rule['price_sell'] = 'required|integer';
            $rule['price_cost'] = 'required|integer';
            //$rule['num'] = 'required|integer';
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'brand_id.required' => 'Vui lòng chọn thương hiệu',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm',
            'avatar.required' => 'Vui lòng chọn ảnh đại diện',
            'avatar.image' => 'Vui lòng chọn ảnh đại diện',
            'price_sell.required' => 'Vui lòng nhập giá bán',
            'price_sell.integer' => 'Vui lòng nhập giá bán',
            'price_cost.required' => 'Vui lòng nhập giá nhập',
            'price_cost.integer' => 'Vui lòng nhập giá nhập',
            'num.required' => 'Vui lòng nhập giá bán',
            'num.integer' => 'Vui lòng nhập giá bán',
        ];
    }
}
