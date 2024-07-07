<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Pos\Attribute\CreateRequest;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        $shop = resolve('shop');
        $attributes = ProductAttribute::orderBy('created_at','desc')->where('shop_id',$shop->id);
        if ($request->name){
            $attributes = $attributes->where('name',$request->name);
        }
        $attributes = $attributes->paginate($request->limit ?? 50);
        return response([
            'result' => true,
            'data' => $attributes
        ]);
    }
    public function store(CreateRequest $request){
        $shop = resolve('shop');
        $attribute = new ProductAttribute();
        $attribute->shop_id = $shop->id;
        $attribute->name = $request->name; // tên thuộc tính
        $attribute->slug = Str::slug($attribute->name);
        $attribute->save();
        return response([
            'result' => true,
            'message' => 'Tạo thuộc tính thành công',
            'data' => $attribute
        ]);
    }
    public function update($id,Request $request){
        $shop = resolve('shop');
        $attribute = ProductAttribute::find($id);
        $attribute->shop_id = $shop->id;
        $attribute->name = $request->name; // tên thuộc tính
        $attribute->slug = Str::slug($attribute->name);
        $attribute->save();
        return response([
            'result' => true,
            'message' => 'Sửa thuộc tính thành công',
            'data' =>  $attribute,
        ]);
    }
    public function delete($id,Request $request){
        $shop = resolve('shop');
        $attribute = ProductAttribute::find($id);
        $attribute->delete();
        return response([
            'result' => true,
            'message' => 'Xóa thuộc tính thành công',
        ]);
    }
}
