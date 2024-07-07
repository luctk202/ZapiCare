<?php

namespace App\Http\Requests\Api\StampProducer;

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
            'num_request' => 'required|integer|min:1',
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
            'num_request.required' => 'Vui lòng nhập số lượng cần in',
            'num_request.integer' => 'Vui lòng nhập số lượng cần in',
            'num_request.min' => 'Vui lòng nhập số lượng cần in',
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
