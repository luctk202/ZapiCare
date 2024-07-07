<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TestItem\TestItemRepository;
use App\Repositories\TestSystem\TestSystemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\TestItem;

class TestItemController extends Controller
{
    public $testItemRepository;
    public $testSystemRepository;

    public function __construct(TestItemRepository $testItemRepository, TestSystemRepository $testSystemRepository)
    {
        $this->testItemRepository = $testItemRepository;
        $this->testSystemRepository = $testSystemRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where[] = ['name', 'like', '%' . $request->name . '%'];
        }
        if (!empty($request->test_system_id)) {
            $where[] = ['test_system_id', '=', $request->test_system_id];
        }
        $data = $this->testItemRepository->paginate($where, ['id' => 'ASC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách"]
        ];
        $test_systems = $this->testSystemRepository->all(); // Fetch all test systems
        return view('admin.content.test_item.index')->with(compact('data', 'breadcrumbs', 'test_systems'));
    }

//    public function getTestItems($testSystemId)
//    {
//        // Lấy các mục kiểm tra tương ứng với hệ thống kiểm tra
//        $testItems = TestItem::where('test_system_id', $testSystemId)->get();
//        return response()->json($testItems);
//    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.test_item.index'), 'name' => "Muc kiểm tra"],
            ['name' => 'Tạo mới']
        ];
        $test_systems = $this->testSystemRepository->all();
        return view('admin.content.test_item.create')->with(compact('breadcrumbs', 'test_systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'test_system_id' => 'required|exists:test_systems,id'
        ]);
        $data = $request->all();
//        Cache::forget('test_items');
        $this->testItemRepository->create($data);
        return redirect()->route('admin.test_item.index');
    }

    public function edit($id)
    {
        $data = $this->testItemRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.test_item.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.test_item.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {
        {
            $attr = $this->testItemRepository->find($id);
            $data = $request->all();
//            Cache::forget('test_item');
            $this->testItemRepository->edit($attr, $data);
            return redirect()->route('admin.test_item.index');
        }
    }

    public function delete($id)
    {
        $test_item = $this->testItemRepository->find($id);
        $this->testItemRepository->delete($test_item);
//        Cache::forget('test_item');
        return response(['result' => true]);
    }

    public function update_status($id, Request $request)
    {
        $test_item = $this->testItemRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->testItemRepository->edit($test_item, $data);
//        Cache::forget('test_items');
        return response(['result' => true]);
    }
}
