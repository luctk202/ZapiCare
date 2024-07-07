<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Models\BannerShop;
use App\Models\Logs;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductDraft;
use App\Models\ProductStock;
use App\Models\ShopCategory;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\FilterAttributeProduct\FilterAttributeProductRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductAttribute\ProductAttributeRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\ProductProvince\ProductProvinceRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Repositories\ProductStockLog\ProductStockLogRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Shop\ShopRepository;
use App\Repositories\ShopCategory\ShopCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Mime\Header\all;


class ProductController extends Controller
{
    public $productRepository;
    public $productStockRepository;
    public $productStockLogRepository;
    public $productCategoryRepository;

    public function __construct(ProductRepository          $productRepository,
                                ProductStockRepository     $productStockRepository,
                                ProductStockLogRepository  $productStockLogRepository,
                                ProductCategoryRepository $productCategoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->productStockRepository = $productStockRepository;
        $this->productStockLogRepository = $productStockLogRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }
    public function index(Request $request){
        $shop = resolve('shop');
        $products = Product::with(['category','stocks'])->where('shop_id',$shop->id);
        if ($request->search){
            $products = $products->where('name','like','%'.$request->search.'%');
        }
        if ($request->category_id){
            $ids = [$request->category_id] + $this->productCategoryRepository->get_child_id($request->category_id);
            $products = $products->where('category_id',$ids);
        }
        $products = $products->paginate($request->limit ?? 10);
        return response([
            'result' => true,
            'data' => $products
        ]);
    }
    public function show($id,Request $request){
        $shop = resolve('shop');
        $product = Product::with(['stocks'])->find($id);
        return response([
            'result' => true,
            'data' => $product
        ]);
    }
    public function store(Request $request)
    {
        $shop = resolve('shop');
        $data = [
            'name'=>$request->name,
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,
            'unit'=>$request->unit,
            'shop_id'=>$shop->id,
            'barcode'=>$request->barcode,
            'sku'=>$request->sku,
            'description'=>$request->description,
            'tax_type'=>$request->tax_type,
            'vat_type'=>$request->vat_type,
            'weight' => (float)$request->weight * 1000,
            'width' => (int)$request->width * 10,
            'length' => (int)$request->length * 10,
            'height' => (int)$request->height * 10,
            'tax_value' => (int)$request->tax_value,
            'vat_value' => (int)$request->vat_value,
        ];
        $avatar = $request->file('avatar');
        if ($avatar) {
            $data['avatar'] = Storage::putFileAs('product', $avatar, $avatar->getClientOriginalName());
        }
        $images = $request->file('images');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
                $data_image[] = Storage::putFileAs('product', $image, $image->getClientOriginalName());
            }
            $data['images'] = $data_image;
        }
        $files = $request->file('files');
        if ($files) {
            $data_file = [];
            foreach ($files as $file) {
                $data_file[] = Storage::putFileAs('product', $file, $file->getClientOriginalName());
            }
            $data['files'] = $data_file;
        }
        $data['status'] = 1;
        $data['approval'] = 1;
        $data_stock = [];
        $data['price_sell'] = 0;
        $data['price_website'] = 0;
        $data['price_cost'] = 0;
        $data['num'] = 0;
        $result = [];
        if ($request->attribute){
            $id = 1;
            foreach ($request->attribute as $item) {
                $result[] = [
                    "id" => $id++,
                    "name" => $item["name"],
                    "value" => json_decode($item["value"])
                ];
            }
        }
        if ($request->attribute && $request->product_attribute_value){
            $data['attributes'] = $result;
            foreach ($request->product_attribute_value as $value) {
                $arr_attribute = json_encode($value['attributes']);
                $data_stock[] = [
                    'attributes' => $value['attributes'],
                    'attributes_name' => $value['attributes_name'],
                    'price_cost' => $value['product_attribute_price_cost'] ?? 0,
                    'price_website' => $value['product_attribute_price_website'] ?? 0,
                    'price_sell' => $value['product_attribute_price_sell'] ?? 0,
                    'num' => $value['product_attribute_num'] ?? 0,
                ];
                if ($data['price_sell'] == 0) {
                    $data['price_sell'] = $value['product_attribute_price_cost'] ?? 0;
                    $data['price_website'] = $value['product_attribute_price_website'] ?? 0;
                    $data['price_cost'] = $value['product_attribute_price_sell'] ?? 0;
                    $data['num'] = $value['product_attribute_num'] ?? 0;
                }
            }
        }else {
            $data['price_sell'] = $request->price_sell ?? 0;
            $data['price_website'] = $request->price_website ?? 0;
            $data['price_cost'] = $request->price_cost ?? 0;
            $data['num'] = $request->num ?? 0;
            $data_stock[] = [
                'attributes' => [],
                'attributes_name' => '',
                'num' => $request->num ?? 0,
                'price_cost' => $request->price_cost ?? 0,
                'price_sell' => $request->price_sell ?? 0,
                'price_website' => $request->price_website ?? 0,
            ];
        }
        $data_filter = [];
        $filters = $request->filters;
        if($filters){
            foreach ($filters as $filter_id){
                $data_filter[] = [
                    'filter_attribute_id' => $filter_id,
                    'category_id' => $data['category_id']
                ];
            }
        }
        $data_province = [];
        DB::transaction(function () use ($data, $data_stock, $data_filter, $data_province,$shop) {
            $product = Product::create($data);
            $product->stocks()->createMany($data_stock);
            if($data_filter){
                $product->filter_attributes()->createMany($data_filter);
            }
            if ($data_province) {
                $product->province()->createMany($data_province);
            }
            $data_category = [
                'shop_id' => $product->shop_id,
                'category_id' => $product->category_id
            ];
            ShopCategory::firstOrCreate($data_category);
            Logs::create([
                'user_id' => $shop->id,
                'source' => 1,
                'action' => 'create_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response([
            'result' => true,
            'message' => 'Thêm sản phẩm thành công',
        ]);
    }
    public function update($id,Request $request){
        $shop = resolve('shop');
        $product = Product::find($id);
        $data = [
            'name'=>$request->name,
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,
            'unit'=>$request->unit,
            'shop_id'=>$shop->id,
            'barcode'=>$request->barcode,
            'sku'=>$request->sku,
            'description'=>$request->description,
            'tax_type'=>$request->tax_type,
            'vat_type'=>$request->vat_type,
            'weight' => (float)$request->weight * 1000,
            'width' => (int)$request->width * 10,
            'length' => (int)$request->length * 10,
            'height' => (int)$request->height * 10,
            'tax_value' => (int)$request->tax_value,
            'vat_value' => (int)$request->vat_value,
        ];
        $avatar = $request->file('avatar');
        if ($avatar) {
            $data['avatar'] = Storage::putFileAs('product', $avatar, $avatar->getClientOriginalName());
        }
        $images = $request->file('images');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
                $data_image[] = Storage::putFileAs('product', $image, $image->getClientOriginalName());
            }
            $data['images'] = $data_image;
        }
        $files = $request->file('files');
        if ($files) {
            $data_file = [];
            foreach ($files as $file) {
                $data_file[] = Storage::putFileAs('product', $file, $file->getClientOriginalName());
            }
            $data['files'] = $data_file;
        }
        $data['status'] = 1;
        $data['approval'] = 1;
        $data_stock = [];
        $data['price_sell'] = 0;
        $data['price_website'] = 0;
        $data['price_cost'] = 0;
        $data['num'] = 0;
        $result = [];
        if ($request->attribute){
            $id = 1;
            foreach ($request->attribute as $item) {
                $result[] = [
                    "id" => $id++,
                    "name" => $item["name"],
                    "value" => json_decode($item["value"])
                ];
            }
        }
        if ($request->attribute && $request->product_attribute_value){
            $data['attributes'] = $result;
            foreach ($request->product_attribute_value as $value) {
                $arr_attribute = json_encode($value['attributes']);
                $data_stock[] = [
                    'attributes' => $value['attributes'],
                    'attributes_name' => $value['attributes_name'],
                    'price_cost' => $value['product_attribute_price_cost'] ?? 0,
                    'price_website' => $value['product_attribute_price_website'] ?? 0,
                    'price_sell' => $value['product_attribute_price_sell'] ?? 0,
                    'num' => $value['product_attribute_num'] ?? 0,
                ];
                if ($data['price_sell'] == 0) {
                    $data['price_sell'] = $value['product_attribute_price_cost'] ?? 0;
                    $data['price_website'] = $value['product_attribute_price_website'] ?? 0;
                    $data['price_cost'] = $value['product_attribute_price_sell'] ?? 0;
                    $data['num'] = $value['product_attribute_num'] ?? 0;
                }
            }
        }else {
            $data['price_sell'] = $request->price_sell ?? 0;
            $data['price_website'] = $request->price_website ?? 0;
            $data['price_cost'] = $request->price_cost ?? 0;
            $data['num'] = $request->num ?? 0;
            $data_stock[] = [
                'attributes' => [],
                'attributes_name' => '',
                'num' => $request->num ?? 0,
                'price_cost' => $request->price_cost ?? 0,
                'price_sell' => $request->price_sell ?? 0,
                'price_website' => $request->price_website ?? 0,
            ];
        }
        $data_filter = [];
        $filters = $request->filters;
        if($filters){
            foreach ($filters as $filter_id){
                $data_filter[] = [
                    'filter_attribute_id' => $filter_id,
                    'category_id' => $data['category_id']
                ];
            }
        }
        $data_province = [];
        DB::transaction(function () use ($product,$data, $data_stock, $data_filter, $data_province,$shop) {
            $product = $this->productRepository->edit($product, $data);
            $old_stock = $product->stocks;
            $old_stock_pluck = $old_stock->pluck(null, 'attributes_name')->toArray();
            $old_stock_pluck_id = $old_stock->pluck('id')->toArray();
            $new_stock_pluck_id = [];
            foreach ($data_stock as $stock) {
                if (isset($old_stock_pluck[$stock['attributes_name']])) {
                    $new_stock_pluck_id[] = $old_stock_pluck[$stock['attributes_name']]['id'];
                    $this->productStockRepository->editWhere(['product_id' => $product->id, 'attributes_name' => $stock['attributes_name']], $stock);
                } else {
                    $stock['product_id'] = $product->id;
                    $this->productStockRepository->create($stock);
                }
            }
            $del_stock_id = array_diff($old_stock_pluck_id, $new_stock_pluck_id);
            if ($del_stock_id) {
                $this->productStockRepository->deleteWhere(['id' => ['id', 'whereIn', $del_stock_id]]);
            }
            $product->filter_attributes()->delete();
            if($data_filter){
                $product->filter_attributes()->createMany($data_filter);
            }
            $product->province()->delete();
            if ($data_province) {
                $product->province()->createMany($data_province);
            }
            Logs::create([
                'user_id' => $shop->id,
                'source' => 1,
                'action' => 'create_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response([
            'result' => true,
            'message' => 'Sửa sản phẩm thành công',
        ]);
    }
    public function update_draft($id , Request $request){
        $shop = resolve('shop');
        $product = Product::find($id);
        $data = [
            'product_id'=>$id,
            'name'=>$request->name,
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,
            'unit'=>$request->unit,
            'shop_id'=>$shop->id,
            'barcode'=>$request->barcode,
            'sku'=>$request->sku,
            'description'=>$request->description,
            'tax_type'=>$request->tax_type,
            'vat_type'=>$request->vat_type,
            'weight' => (float)$request->weight * 1000,
            'width' => (int)$request->width * 10,
            'length' => (int)$request->length * 10,
            'height' => (int)$request->height * 10,
            'tax_value' => (int)$request->tax_value,
            'vat_value' => (int)$request->vat_value,
        ];
        $avatar = $request->file('avatar');
        if ($avatar) {
            $data['avatar'] = Storage::putFileAs('product', $avatar, $avatar->getClientOriginalName());
        }else{
            $data['avatar'] = $product->avatar;
        }
        $images = $request->file('images');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
                $data_image[] = Storage::putFileAs('product', $image, $image->getClientOriginalName());
            }
            $data['images'] = $data_image;
        }else{
            $data['images'] = $product->images;
        }
        $files = $request->file('files');
        if ($files) {
            $data_file = [];
            foreach ($files as $file) {
                $data_file[] = Storage::putFileAs('product', $file, $file->getClientOriginalName());
            }
            $data['files'] = $data_file;
        }else{
            $data['files'] = $product->files;
        }
        $data['status'] = 1;
        $data['approval'] = 1;
        $data_stock = [];
        $data['price_sell'] = 0;
        $data['price_website'] = 0;
        $data['price_cost'] = 0;
        $data['num'] = 0;
        $result = [];
        if ($request->attribute){
            $id_attribute = 1;
            foreach ($request->attribute as $item) {
                $result[] = [
                    "id" => $id_attribute++,
                    "name" => $item["name"],
                    "value" =>json_decode($item["value"])
                ];
            }
        }
        if ($request->attribute && $request->product_attribute_value){
            $data['attributes'] = $result;
            foreach ($request->product_attribute_value as $value) {
                $arr_attribute = json_encode($value['attributes']);
                $data_stock[] = [
                    'attributes' => $value['attributes'],
                    'attributes_name' => $value['attributes_name'],
                    'price_cost' => $value['product_attribute_price_cost'] ?? 0,
                    'price_website' => $value['product_attribute_price_website'] ?? 0,
                    'price_sell' => $value['product_attribute_price_sell'] ?? 0,
                    'num' => $value['product_attribute_num'] ?? 0,
                ];
                if ($data['price_sell'] == 0) {
                    $data['price_sell'] = $value['product_attribute_price_cost'] ?? 0;
                    $data['price_website'] = $value['product_attribute_price_website'] ?? 0;
                    $data['price_cost'] = $value['product_attribute_price_sell'] ?? 0;
                    $data['num'] = $value['product_attribute_num'] ?? 0;
                }
            }
        }else {
            $data['price_sell'] = $request->price_sell ?? 0;
            $data['price_website'] = $request->price_website ?? 0;
            $data['price_cost'] = $request->price_cost ?? 0;
            $data['num'] = $request->num ?? 0;
            $data_stock[] = [
                'attributes' => [],
                'attributes_name' => '',
                'num' => $request->num ?? 0,
                'price_cost' => $request->price_cost ?? 0,
                'price_sell' => $request->price_sell ?? 0,
                'price_website' => $request->price_website ?? 0,
            ];
        }
        $data_filter = [];
        $filters = $request->filters;
        if($filters){
            foreach ($filters as $filter_id){
                $data_filter[] = [
                    'filter_attribute_id' => $filter_id,
                    'category_id' => $data['category_id']
                ];
            }
        }
        $data_province = [];
        DB::transaction(function () use ($product,$id,$data, $data_stock, $data_filter,$shop) {
            // draft == 0 k có yêu cầu chỉnh sửa , = 1 có yêu cầu chỉnh sửa , = 2 hủy
            $draft = [
                'draft'=>1,
            ];
            $this->productRepository->edit($product, $draft);
            $product_draft = ProductDraft::where('product_id',$id)->first();
            if ($product_draft){
                $product_draft->filter_attribute_drafts()->delete();
                $product_draft->stock_drafts()->delete();
                $product_draft->delete();
            }
            $product = ProductDraft::create($data);
            foreach ($data_stock as $stock) {
//                if (isset($old_stock_pluck[$stock['attributes_name']])) {
//                    $new_stock_pluck_id[] = $old_stock_pluck[$stock['attributes_name']]['id'];
//                    $this->productStockRepository->editWhere(['product_id' => $product->id, 'attributes_name' => $stock['attributes_name']], $stock);
//                } else {
//                    $stock['product_id'] = $product->id;
                    $product->stock_drafts()->create($stock);
//                }
            }
            if($data_filter){
                $product->filter_attribute_drafts()->createMany($data_filter);
            }
            Logs::create([
                'user_id' => $shop->id,
                'source' => 1,
                'action' => 'create_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response([
            'result' => true,
            'message' => 'Sửa phẩm thành công',
        ]);
    }
    public function update_status($id,Request $request){
        $shop = resolve('shop');
        $banner_shop = BannerShop::find($id);
        $banner_shop->status = $request->status;
        $banner_shop->save();
        return response([
            'result' => true,
            'message' => 'Update trạng thái thành công',
            'data' => $banner_shop
        ]);
    }
    public function delete($id,Request $request){
        $permission = $this->productRepository->find($id);
        $this->productRepository->delete($permission);
        return response(['result' => true]);
    }
}
