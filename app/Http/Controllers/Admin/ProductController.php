<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\CreateRequest;
use App\Http\Requests\Admin\Product\EditRequest;
use App\Models\Logs;
use App\Models\Product;
use App\Models\ProductDraft;
use App\Models\ProductStock;
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
use App\Repositories\TagLabel\TagLabelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\TagLabel;
class ProductController extends Controller
{
    public $productRepository;
    public $categoryRepository;
    public $attributeRepository;
    public $brandRepository;
    public $shopRepository;
    public $discountGroupRepository;
    public $productStockRepository;
    public $productStockLogRepository;
    public $filterRepository;
    public $filterAttributeProductRepository;
    public $productCategoryRepository;
    public $provinceRepository;
    public $productProvinceRepository;
    public $shopCategoryRepository;
    public $tagLabelRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductCategoryRepository $categoryRepository,
        ProductAttributeRepository $attributeRepository,
        BrandRepository $brandRepository,
        ShopRepository $shopRepository,
        ShopCategoryRepository $shopCategoryRepository,
        ProductStockRepository $productStockRepository,
        FilterRepository $filterRepository,
        FilterAttributeProductRepository $filterAttributeProductRepository,
        ProductCategoryRepository $productCategoryRepository,
        ProvinceRepository $provinceRepository,
        ProductProvinceRepository $productProvinceRepository,
        ProductStockLogRepository $productStockLogRepository,
        TagLabelRepository $tagLabelRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attributeRepository;
        $this->brandRepository = $brandRepository;
        $this->shopRepository = $shopRepository;
        $this->productStockRepository = $productStockRepository;
        $this->productStockLogRepository = $productStockLogRepository;
        $this->filterRepository = $filterRepository;
        $this->filterAttributeProductRepository = $filterAttributeProductRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->provinceRepository = $provinceRepository;
        $this->productProvinceRepository = $productProvinceRepository;
        $this->shopCategoryRepository = $shopCategoryRepository;
        $this->tagLabelRepository=$tagLabelRepository;
    }

    public function index(Request $request)
    {
        $where = [
        ];
        if (!empty($request->category_id)) {
            $ids = [$request->category_id] + $this->productCategoryRepository->get_child_id($request->category_id);
            $where['category_id'] = ['category_id', 'whereIn', $ids];
        }
        if (!empty($request->brand_id)) {
            $where['brand_id'] = $request->brand_id;
        }
        if (!empty($request->sku)) {
            $where['sku'] = $request->sku;
        }
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->productRepository->paginate($where, ['id' => 'DESC']);

        $data->load('category', 'brand', 'shop', 'province');
        $status = ['-1' => 'Vui lòng chọn'] + $this->productRepository->status;
        $approval = $this->productRepository->approval;
        $categories = $this->productCategoryRepository->tree();
        $brand = $this->brandRepository->active_all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách sản phẩm"]
        ];
        return view('admin.content.product.list')->with(compact('data', 'breadcrumbs', 'status', 'approval',
            'categories', 'brand'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.product.index'), 'name' => "Danh sách sản phẩm"],
            ['name' => 'Tạo mới']
        ];
        $categories = $this->categoryRepository->tree();
        $attributes = $this->attributeRepository->cache_all();
        $brands = $this->brandRepository->active_all();
        $vat_type = $this->productRepository->aryVatType;
        //$warranty_type = $this->productRepository->warranty_type;
        $filters = $this->filterRepository->active_all();
        $tagLabels=$this->tagLabelRepository->all();
        $province = $this->provinceRepository->all();

        return view('admin.content.product.create')->with(compact('breadcrumbs', 'categories', 'attributes', 'vat_type',
            'brands', 'filters', 'province','tagLabels'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only([
            'name',
            'category_id',
            'unit',
            'barcode',
            'sku',
            'description',
            'short_description',
            'long_description',
        ]);
        $data['new'] = $request->has('new') ? 1 : 0;
        $data['typical'] = $request->has('typical') ? 1 : 0;
        $data['weight'] = (float)$request->weight * 1000;
        $data['width'] = (int)$request->width * 10;
        $data['length'] = (int)$request->length * 10;
        $data['height'] = (int)$request->height * 10;

        $avatar = $request->file('avatar');
        if ($avatar) {
//            $data['avatar'] = Storage::disk('public')->putFileAs('product', $avatar, $avatar->getClientOriginalName());
                        $data['avatar'] = Storage::putFileAs('product', $avatar, $avatar->getClientOriginalName());

        }

        $images = $request->file('images');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
//                $data_image[] = Storage::disk('public')->putFileAs('product', $image, $image->getClientOriginalName());
                $data_image[] = Storage::putFileAs('product', $image, $image->getClientOriginalName());

            }
            $data['images'] = $data_image;
        }

        $files = $request->file('files');
        if ($files) {
            $data_file = [];
            foreach ($files as $file) {
//                $data_file[] = Storage::disk('public')->putFileAs('product', $file, $file->getClientOriginalName());
                $data_file[] = Storage::putFileAs('product', $file, $file->getClientOriginalName());
            }
            $data['files'] = $data_file;
        }

        $data['status'] = $this->productRepository::STATUS_SHOW;
        $data['approval'] = $this->productRepository::APPROVAL_DONE;

        $data_stock = [];
        if (\request('attributes') && $request->product_attribute_value) {
            $data_attributes = [];
            $attributes = $this->attributeRepository->cache_all();
            foreach ($attributes as $attribute) {
                if (in_array($attribute->id, \request('attributes'))) {
                    $value = \request('attribute_values_' . $attribute->id);
                    $value = json_decode($value, true);
                    $data_value = [];
                    foreach ($value as $val) {
                        $data_value[] = $val['value'];
                    }
                    $data_attributes[] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value' => $data_value
                    ];
                }
            }
            $data['attributes'] = $data_attributes;
            $data['price_sell'] = 0;
            $data['num'] = 0;
            foreach ($request->product_attribute_value as $value) {
                $arr_attribute = explode('-', $value);
                $data_stock[] = [
                    'attributes' => $arr_attribute,
                    'attributes_name' => $value,
                    'num' => $request->product_attribute_num[$value] ?? 0,
                    'price_sell' => $request->product_attribute_price_sell[$value] ?? 0,
                ];
                if ($data['price_sell'] == 0) {
                    $data['price_sell'] = $request->product_attribute_price_sell[$value] ?? 0;
                    $data['num'] = $request->product_attribute_num[$value] ?? 0;
                } elseif ($data['price_sell'] > ($request->product_attribute_price_sell[$value] ?? 0)) {
                    $data['price_sell'] = $request->product_attribute_price_sell[$value] ?? 0;
                    $data['num'] = $request->product_attribute_num[$value] ?? 0;
                }
            }
        } else {
            $data['price_sell'] = $request->price_sell ?? 0;
            $data['num'] = $request->num ?? 0;
            $data_stock[] = [
                'attributes' => [],
                'attributes_name' => '',
                'num' => $request->num ?? 0,
                'price_sell' => $request->price_sell ?? 0,
            ];
        }

        $data_filter = [];
        $filters = $request->filters;
        if ($filters) {
            foreach ($filters as $filter_id) {
                $data_filter[] = [
                    'filter_attribute_id' => $filter_id,
                    'category_id' => $data['category_id']
                ];
            }
        }

        $data_province = [];
        /*$province_ids = $request->province_ids;
        if($province_ids){
            foreach ($province_ids as $province_id){
                $data_province[] = [
                    'province_id' => $province_id,
                ];
            }
        }*/

        // Lưu thông tin tag-labels
        $tagLabels = $request->input('tag_labels', []);
        $tagLabelData = TagLabel::whereIn('id', $tagLabels)->get(['id', 'name','color'])->toArray();
        $data['tag_label_ids'] = json_encode($tagLabelData);

        DB::transaction(function () use ($data, $data_stock, $data_filter, $data_province) {
            $product = $this->productRepository->create($data);
            $product->stocks()->createMany($data_stock);
            if ($data_filter) {
                $product->filter_attributes()->createMany($data_filter);
            }
            if ($data_province) {
                $product->province()->createMany($data_province);
            }
            $data_category = [
                'category_id' => $product->category_id
            ];
            $this->shopCategoryRepository->firstOrCreate($data_category);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });

        return redirect()->route('admin.product.index');
    }


    public function edit($id)
    {
        $product = $this->productRepository->first([
            'id' => $id,
            //'type' => $this->productRepository::TYPE_RETAIL
        ]);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.product.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        $categories = $this->categoryRepository->tree();
        $attributes = $this->attributeRepository->cache_all();
        $brands = $this->brandRepository->active_all();
        //$discount_groups = $this->discountGroupRepository->all();
        $vat_type = $this->productRepository->aryVatType;
        //$discount_type = $this->productDiscountRepository->aryDiscountType;
        //$warranty_type = $this->productRepository->warranty_type;
        $filters = $this->filterRepository->active_all();
        $province = $this->provinceRepository->all();
        $tagLabels = $this->tagLabelRepository->all();
        $selectedTagLabels = [];
        if ($product->tag_label_ids) {
            $selectedTagLabels = array_column(json_decode($product->tag_label_ids, true), 'id');
        }
        return view('admin.content.product.edit')->with(compact('product', 'breadcrumbs', 'categories', 'attributes',
            'brands', 'vat_type', 'filters', 'province','selectedTagLabels','tagLabels'));
    }

    public function update($id, EditRequest $request)
    {
        $product = $this->productRepository->first([
            'id' => $id,
            //'type' => $this->productRepository::TYPE_RETAIL
        ]);
        $data = $request->only([
            'name',
            'category_id',
            //'brand_id',
            'unit',
            'barcode',
            'sku',
            //'pack_number',
            'description',
            'short_description',
            'long_description',
            //'warranty_type',
            //'point_value',
            //'group_discount_id'
            'typical',
//            'new'
        ]);
        $data['typical'] = $request->has('typical') ? 1 : 0;
//        $data['new'] = $request->has('new') ? 1 : 0;
        $data['weight'] = (float)$request->weight * 1000;
        $data['width'] = (int)$request->width * 10;
        $data['length'] = (int)$request->length * 10;
        $data['height'] = (int)$request->height * 10;
        //$data['warranty_time'] = (int)$request->warranty_time;
        $avatar = $request->file('avatar');
        if ($avatar) {
//            $data['avatar'] = Storage::disk('public')->putFileAs('product', $avatar, $avatar->getClientOriginalName());
            $data['avatar'] = Storage::putFileAs('product', $avatar, $avatar->getClientOriginalName());
        }
        $images = $request->file('images');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
//                $data_image[] = Storage::disk('public')->putFileAs('product', $image, $image->getClientOriginalName());
                $data_image[] = Storage::putFileAs('product', $image, $image->getClientOriginalName());
            }
            $data['images'] = $data_image;
        }
        $files = $request->file('files');
        if ($files) {
            $data_file = [];
            foreach ($files as $file) {
//                $data_file[] = Storage::disk('public')->putFileAs('product', $file, $file->getClientOriginalName());
                $data_file[] = Storage::putFileAs('product', $file, $file->getClientOriginalName());
            }
            $data['files'] = $data_file;
        }

        $data_stock = [];
        if (\request('attributes') && $request->product_attribute_value) {
            $data_attributes = [];
            $attributes = $this->attributeRepository->cache_all();
            foreach ($attributes as $attribute) {
                if (in_array($attribute->id, \request('attributes'))) {
                    $value = \request('attribute_values_' . $attribute->id);
                    $value = json_decode($value, true);
                    $data_value = [];
                    if (!empty($value)) {
                        foreach ($value as $val) {
                            $data_value[] = $val['value'];
                        }
                        $data_attributes[] = [
                            'id' => $attribute->id,
                            'name' => $attribute->name,
                            'value' => $data_value
                        ];
                    }
                }
            }
            $data['attributes'] = $data_attributes;
            $data['price_sell'] = 0;
            $data['num'] = 0;
            foreach ($request->product_attribute_value as $value) {
                $arr_attribute = explode('-', $value);
                $data_stock[] = [
                    'attributes' => $arr_attribute,
                    'attributes_name' => $value,
                    'price_sell' => $request->product_attribute_price_sell[$value] ?? 0,
                    'num' => $request->product_attribute_num[$value] ?? 0,
                    //'num' => 0
                    //'image' => !empty($request->product_attribute_image[$value]) ? Storage::disk('public')->putFileAs('product/' . $data['producer_id'], $request->product_attribute_image[$value], $request->product_attribute_image[$value]->getClientOriginalName()) : ''
                ];
                if ($data['price_sell'] == 0) {
                    $data['price_sell'] = $request->product_attribute_price_sell[$value] ?? 0;
                    $data['num'] = $request->product_attribute_num[$value] ?? 0;
                } elseif ($data['price_sell'] > ($request->product_attribute_price_sell[$value] ?? 0)) {
                    $data['price_sell'] = $request->product_attribute_price_sell[$value] ?? 0;
                    $data['num'] = $request->product_attribute_num[$value] ?? 0;
                }
            }
        } else {
            $data['attributes'] = [];
            $data['price_sell'] = $request->price_sell ?? 0;
            $data['num'] = $request->num ?? 0;
            $data_stock[] = [
                'attributes' => [],
                'attributes_name' => '',
                'price_sell' => $request->price_sell ?? 0,
                'num' => $request->num ?? 0,
            ];
        }
        $data_filter = [];
        $filters = $request->filters;
        if ($filters) {
            foreach ($filters as $filter_id) {
                $data_filter[] = [
                    'filter_attribute_id' => $filter_id,
                    'category_id' => $data['category_id']
                ];
            }
        }
        $data_province = [];
        /*$province_ids = $request->province_ids;
        if($province_ids){
            foreach ($province_ids as $province_id){
                $data_province[] = [
                    'province_id' => $province_id,
                ];
            }
        }*/

        $tagLabels = $request->input('tag_labels', []);
        $tagLabelData = TagLabel::whereIn('id', $tagLabels)->get(['id', 'name', 'color'])->toArray();
        $data['tag_label_ids'] = json_encode($tagLabelData);
        DB::transaction(function () use ($product, $data, $data_stock, $data_filter, $data_province) {
            $product = $this->productRepository->edit($product, $data);
            $old_stock = $product->stocks;
            $old_stock_pluck = $old_stock->pluck(null, 'attributes_name')->toArray();
            $old_stock_pluck_id = $old_stock->pluck('id')->toArray();
            $new_stock_pluck_id = [];
            foreach ($data_stock as $stock) {
                if (isset($old_stock_pluck[$stock['attributes_name']])) {
                    $new_stock_pluck_id[] = $old_stock_pluck[$stock['attributes_name']]['id'];
                    $this->productStockRepository->editWhere([
                        'product_id' => $product->id,
                        'attributes_name' => $stock['attributes_name']
                    ], $stock);
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
            if ($data_filter) {
                $product->filter_attributes()->createMany($data_filter);
            }
            $product->province()->delete();
            if ($data_province) {
                $product->province()->createMany($data_province);
            }
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return redirect()->route('admin.product.index');
    }

    public function update_status($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        DB::transaction(function () use ($product, $data) {
            $product = $this->productRepository->edit($product, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'update_status_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response(['result' => true]);
    }

    public function update_typical($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        $data = [
            'typical' => (int)$request->typical
        ];
        DB::transaction(function () use ($product, $data) {
            $product = $this->productRepository->edit($product, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'update_typical_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response(['result' => true]);
    }

//    public function update_new($id, Request $request)
//    {
//        $product = $this->productRepository->find($id);
//        $data = [
//            'new' => (int)$request->new
//        ];
//        DB::transaction(function () use ($product, $data) {
//            $product = $this->productRepository->edit($product, $data);
//            Logs::create([
//                'user_id' => auth('admin')->id(),
//                'source' => 1,
//                'action' => 'update_new_product',
//                'item_id' => $product->id,
//                'data' => $product->toArray()
//            ]);
//            return $product;
//        });
//        return response(['result' => true]);
//    }

    public function approval($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        $data = [
            'approval' => $this->productRepository::APPROVAL_DONE
        ];
        DB::transaction(function () use ($product, $data) {
            $product = $this->productRepository->edit($product, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'approval_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response(['result' => true]);
    }

    public function cancel_approval($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        $data = [
            'approval' => $this->productRepository::APPROVAL_CANCEL
        ];
        DB::transaction(function () use ($product, $data) {
            $product = $this->productRepository->edit($product, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'cancel_approval_product',
                'item_id' => $product->id,
                'data' => $product->toArray()
            ]);
            return $product;
        });
        return response(['result' => true]);
    }

    /*public function update_status_website($id, Request $request)
    {
        $user = $this->productRepository->find($id);
        $data = [
            'status_website' => (int)$request->status
        ];
        $this->productRepository->edit($user, $data);
        return response(['result' => true]);
    }*/

    /*public function update_status_group($id, Request $request)
{
    $user = $this->productRepository->find($id);
    $data = [
        'status_group' => (int)$request->status
    ];
    if ($data['status_group'] == $this->productRepository::STATUS_GROUP_SHOW) {
        $data['status_website'] = $this->productRepository::STATUS_WEB_HIDE;
    }
    $this->productRepository->edit($user, $data);
    return response(['result' => true]);
}*/


    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        $this->productRepository->delete($product);
        return response(['result' => true]);
    }

    public function upload(Request $request)
    {
        $image = $request->file('file');
        $imgurl = '';
        if ($image) {
            $imgpath = Storage::putFileAs('editors', $image, $image->getClientOriginalName());
            $imgurl = Storage::url($imgpath);
        }
        return response()->json(['location' => $imgurl]);
    }


    public function edit_stock($id)
    {
        $product = $this->productRepository->first([
            'id' => $id,
            //'type' => $this->productRepository::TYPE_WHOLESALE
        ]);
        $stocks = $this->productStockRepository->get(['product_id' => $id]);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.product.index'), 'name' => "Danh sách"],
            ['name' => 'Nhập kho']
        ];
        return view('admin.content.product.stock')->with(compact('product', 'breadcrumbs', 'stocks'));
    }

    public function update_stock($id, Request $request)
    {
        $stocks = $request->stocks;
        DB::transaction(function () use ($stocks) {
            foreach ($stocks as $stock_id => $num) {
                $num = (int)$num;
                if ($num > 0) {
                    $stock = $this->productStockRepository->find($stock_id);
                    $this->productStockRepository->increment(['id' => $stock_id], 'num', $num);
                    $this->productStockLogRepository->create([
                        'product_id' => $stock->product_id,
                        'product_stock_id' => $stock->id,
                        'price_cost' => $stock->price_cost,
                        'number' => $num,
                        'created_time' => time(),
                        'admin_id' => auth('admin')->id(),
                    ]);
                }
            }
        });
        return redirect()->route('admin.product.edit-stock', ['id' => $id])->with('success', 'Nhập kho thành công');
    }

    public function search(Request $request)
    {
        $where = [
            //'type' => $this->productRepository::TYPE_RETAIL,
            'status' => $this->productRepository::STATUS_SHOW
        ];
        if (!empty($request->category_id)) {
            $ids = [$request->category_id] + $this->categoryRepository->get_child_id($request->category_id);
            $where['category_id'] = ['category_id', 'whereIn', $ids];
        }
        if (!empty($request->brand_id)) {
            $where['brand_id'] = (int)$request->brand_id;
        }
        if (!empty($request->search)) {
            $where['name'] = ['name', 'like', $request->search];
        }
        $data = $this->productRepository->paginate($where, ['id' => 'DESC'], [], [], $request->limit ?? 50);
        //dd($data);
        $data->load('stocks');
        return response([
            'result' => true,
            'data' => $data
        ]);
    }
}
