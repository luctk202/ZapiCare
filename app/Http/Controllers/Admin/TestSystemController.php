<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TestSystem\TestSystemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TestSystemController extends Controller
{
    public $testSytemRepository;

    public function __construct(TestSystemRepository $testSytemRepository)
    {
        $this->testSytemRepository = $testSytemRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->test_item)) {
            $where['test_system'] = ['test_system', 'like', $request->test_item];
        }
        $data = $this->testSytemRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách"]
        ];
        return view('admin.content.test_system.index')->with(compact('data', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.test_system.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        return view('admin.content.test_system.create')->with(compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->only('test_item');
//        Cache::forget('test_system');
        $this->testSytemRepository->create($data);
        return redirect()->route('admin.test_system.index');
    }

    public function edit($id)
    {
        $data = $this->testSytemRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.test_system.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.test_system.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {
        {
            $attr = $this->testSytemRepository->find($id);
            $data = $request->only(['test_item']);
//            Cache::forget('test_system');
            $this->testSytemRepository->edit($attr, $data);
            return redirect()->route('admin.test_system.index');
        }
    }

    public function delete($id)
    {
        $admin = $this->testSytemRepository->find($id);
        $this->testSytemRepository->delete($admin);
//        Cache::forget('test_system');
        return response(['result' => true]);
    }

    public function update_status($id, Request $request)
    {
        $test_system = $this->testSytemRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->testSytemRepository->edit($test_system, $data);
//        Cache::forget('levels');
        return response(['result' => true]);
    }
}
