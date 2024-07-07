<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\FilterAttribute\FilterAttributeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    public $filterRepository;
    public $filterAttributeRepository;

    public function __construct(FilterRepository $filterRepository, FilterAttributeRepository $filterAttributeRepository){
        $this->filterRepository = $filterRepository;
        $this->filterAttributeRepository = $filterAttributeRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->filterRepository->paginate($where, ['id' => 'DESC']);
        $data->load('attributes');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.filter.list')->with(compact('data', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.filter.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.filter.create')->with(compact( 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['name']);
        Cache::forget('filters');
        $this->filterRepository->create($data);
        return redirect()->route('admin.filter.index');
    }

    public function edit($id)
    {
        $data = $this->filterRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.filter.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        return view('admin.content.filter.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update($id, Request $request)
    {
        $attr = $this->filterRepository->find($id);
        $data = $request->only(['name']);
        Cache::forget('filters');
        $this->filterRepository->edit($attr, $data);
        return redirect()->route('admin.filter.index');
    }

    public function create_attribute($id)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.filter.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.filter.create_attribute')->with(compact( 'breadcrumbs', 'id'));
    }

    public function store_attribute($id, Request $request)
    {
        $data = $request->only(['name']);
        $data['filter_id'] = $id;
        Cache::forget('filters');
        $this->filterAttributeRepository->create($data);
        return redirect()->route('admin.filter.index');
    }

    public function edit_attribute($id)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.filter.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $data = $this->filterAttributeRepository->find($id);
        return view('admin.content.filter.edit_attribute')->with(compact('breadcrumbs', 'data'));
    }

    public function update_attribute($id, Request $request)
    {
        $attr = $this->filterAttributeRepository->find($id);
        $data = $request->only(['name']);
        Cache::forget('filters');
        $this->filterAttributeRepository->edit($attr, $data);
        return redirect()->route('admin.filter.index');
    }

    /*public function update_status($id, Request $request)
    {
        $user = $this->userRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->userRepository->edit($user, $data);
        return response(['result' => true]);
    }*/


    public function delete($id){
        $data = $this->filterRepository->find($id);
        DB::transaction(function () use ($data){
            $data->attributes()->delete();
            $this->filterRepository->delete($data);
        });
        return response(['result' => true]);
    }

    public function delete_attribute($id){
        $data = $this->filterAttributeRepository->find($id);
        $this->filterAttributeRepository->delete($data);
        return response(['result' => true]);
    }
}
