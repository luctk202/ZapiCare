<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Disease\DiseaseRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DiseaseControlle extends Controller
{
    protected $diseaseRepository;
    protected $productRepository;

    public function __construct(DiseaseRepository $diseaseRepository, ProductRepository $productRepository)
    {
        $this->diseaseRepository = $diseaseRepository;
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {

        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }

        $data = $this->diseaseRepository->paginate($where, ['id' => 'DESC']);
        $data->load('products');

        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách "]
        ];
        return view('admin.content.disease.index')->with(compact('data', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.disease.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        $products = $this->productRepository->all();
        return view('admin.content.disease.create')->with(compact('breadcrumbs', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $productIds = $request->input('product_ids', []);
        $disease = $this->diseaseRepository->create($data);
        $disease->products()->attach($productIds);
        return redirect()->route('admin.disease.index');
    }

    public function edit($id)
    {
        $data = $this->diseaseRepository->find($id);
        $products = $this->productRepository->all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.disease.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.disease.edit')->with(compact('data', 'breadcrumbs','products'));
    }

    public function update(Request $request, $id)
    {
        {
            $disease = $this->diseaseRepository->find($id);
            $data = $request->all();
            $productIds = $request->input('product_ids', []);
            $this->diseaseRepository->edit($disease, $data);
            $disease->products()->sync($productIds);
            return redirect()->route('admin.disease.index');
        }
    }

    public function destroy($id)
    {
        $admin = $this->diseaseRepository->find($id);
        $this->diseaseRepository->delete($admin);
//        Cache::forget('disease');
        return response(['result' => true]);
    }
}
