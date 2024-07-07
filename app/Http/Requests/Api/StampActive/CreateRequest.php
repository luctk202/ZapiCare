<?php

namespace App\Http\Requests\Api\StampActive;

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
            'phone' => ['required', 'regex:/^(09|03|07|08|05)\d{8}$/'],
            'address' => 'required',
        ];
        /*if(request('attributes')){
            $attributes = request('attributes');
            foreach ($attributes as $value){
                if(request('attribute_values_'.$value)){
                    $rule['product_attribute_num.*'] = 'required|integer';
                }
            }
        }else{
            $rule['attributes'] = 'array';
            $rule['price_sell'] = 'integer';
            $rule['price_cost'] = 'integer';
            //$rule['num'] = 'required|integer';
        }*/
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'address.required' => 'Vui lòng nhập địa chỉ khách hàng',
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
