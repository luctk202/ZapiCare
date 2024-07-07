<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SampleData\EditSampleDataRequest;
use App\Http\Requests\Admin\SampleData\StoreSampleDataRequest;
use App\Repositories\Disease\DiseaseRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use App\Repositories\SampleData\SampleDataRepository;
use App\Repositories\TestItem\TestItemRepository;
use App\Repositories\Level\LevelRepository;
use App\Repositories\TestSystem\TestSystemRepository;
use App\Models\TestItem;

class SampleDataController extends Controller
{
    protected $sampleDataRepository;
    protected $testItemRepository;
    protected $levelRepository;
    protected $testSystemRepository;
    protected $productRepository;
    protected $diseaseRepository;

    public function __construct(
        SampleDataRepository $sampleDataRepository,
        TestItemRepository $testItemRepository,
        LevelRepository $levelRepository,
        TestSystemRepository $testSystemRepository,
        ProductRepository $productRepository,
        DiseaseRepository $diseaseRepository

    ) {
        $this->sampleDataRepository = $sampleDataRepository;
        $this->testItemRepository = $testItemRepository;
        $this->levelRepository = $levelRepository;
        $this->testSystemRepository = $testSystemRepository;
        $this->productRepository = $productRepository;
        $this->diseaseRepository = $diseaseRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        // Xử lý lọc theo test_system_id
        if (!empty($request->test_system_id)) {
            // Lấy danh sách các test item ids thuộc test system được chọn
            $testItems = $this->testItemRepository->getTestItemIdsByTestSystemId($request->test_system_id);
            $testItemIds = array_column($testItems, 'id'); // Chỉ lấy các id từ mảng
            // Thay đổi điều kiện thành whereIn
            $where[] = ['test_item_id', 'whereIn', $testItemIds];
        }

        if (!empty($request->test_item_id)) {
            $where[] = ['test_item_id', '=', $request->test_item_id];
        }

//        if (!empty($request->level_id)) {
//            $where[] = ['level_id', '=', $request->level_id];
//        }

        if (!empty($request->explanation)) {
            $where[] = ['explanation', 'like', '%' . $request->explanation . '%'];
        }

        if (!empty($request->symptom)) {
            $where[] = ['symptom', 'like', '%' . $request->symptom . '%'];
        }

//        if (!empty($request->advice)) {
//            $where[] = ['advice', 'like', '%' . $request->advice . '%'];
//        }
        if ($request->get('status', -1) > -1) {
            $where[] = ['status', '=', (int)$request->status];
        }


        $data = $this->sampleDataRepository->paginate($where, ['id' => 'DESC']);
        $data->load('testItem', 'level', 'products', 'diseases');
        $test_items = $this->testItemRepository->all();
        $levels = $this->levelRepository->getActiveLevels();
        $test_systems = $this->testSystemRepository->all();
        $diseases = $this->diseaseRepository->all();
        // Chuẩn bị breadcrumbs cho view
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách "]
        ];

        // Trả về view với dữ liệu đã lọc và breadcrumbs
        return view('admin.content.sample_data.index')->with(compact('data', 'breadcrumbs', 'test_items', 'levels',
            'diseases',
            'test_systems'));
    }


    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.sample-data.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        $test_items = $this->testItemRepository->all();
//        $levels = $this->levelRepository->getActiveLevels();
        $levels = $this->levelRepository->all();

        $test_systems = $this->testSystemRepository->all();
        $products = $this->productRepository->all();
        $diseases = $this->diseaseRepository->all();
        return view('admin.content.sample_data.create')->with(compact('breadcrumbs', 'test_items', 'levels', 'products',
            'diseases',
            'test_systems'));
    }

    public function store(StoreSampleDataRequest $request)
    {
        $data = $request->all();

        // Lấy danh sách các product_ids và disease_ids từ request
        $productIds = $request->input('product_ids', []);
        $diseaseIds = $request->input('disease_ids', []);

        // Tạo mới SampleData
        $sampleData = $this->sampleDataRepository->create($data);

        // Lưu các product_ids và disease_ids vào bảng trung gian
        $sampleData->products()->attach($productIds);
        $sampleData->diseases()->attach($diseaseIds);

        // Trả về hoặc chuyển hướng theo logic của bạn
        return redirect()->route('admin.sample-data.index');
    }

    public function edit($id)
    {
        $data = $this->sampleDataRepository->find($id);
        $test_items = $this->testItemRepository->all();
        $products = $this->productRepository->all();
        $diseases = $this->diseaseRepository->all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.sample-data.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.sample_data.edit')->with(compact('data', 'breadcrumbs', 'test_items','products','diseases'));
    }

    public function update(EditSampleDataRequest $request, $id)
    {

        $sampleData = $this->sampleDataRepository->find($id);
        $data = $request->all();
//        $data['is_normal_range'] = $request->has('is_normal_range');
        // Tách product_ids ra khỏi $data để lưu riêng
        $productIds = $request->input('product_ids', []);
        $diseaseIds = $request->input('disease_ids', []);
        // Cập nhật sample data với dữ liệu mới
        $this->sampleDataRepository->edit($sampleData, $data);

        // Cập nhật bảng trung gian với các product_ids mới
        $sampleData->products()->sync($productIds);
        $sampleData->diseases()->sync($diseaseIds);

        // Chuyển hướng người dùng về trang danh sách sample data
        return redirect()->route('admin.sample-data.index');
    }

    public function destroy($id)
    {
        $admin = $this->sampleDataRepository->find($id);
        $this->sampleDataRepository->delete($admin);
//        Cache::forget('test_system');
        return response(['result' => true]);
    }

    public function getTestItemsByTestSystemId($testSystemId)
    {
        $testItems = $this->testItemRepository->getTestItemIdsByTestSystemId($testSystemId);
        return response()->json($testItems);
    }


}
