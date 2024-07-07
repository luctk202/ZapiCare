<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagLabel;
use App\Repositories\TagLabel\TagLabelRepository;
use Illuminate\Http\Request;

class TagLabelControlel extends Controller
{
    public $tagLabelRepository;

    public function __construct(TagLabelRepository $tagLabelRepository)
    {
        $this->tagLabelRepository = $tagLabelRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->tagLabelRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách"]
        ];
        return view('admin.content.tag_label.index')->with(compact('data', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.tag-label.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        return view('admin.content.tag_label.create')->with(compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->tagLabelRepository->create($data);
        return redirect()->route('admin.tag-label.index');
    }

    public function edit($id)
    {
        $data = $this->tagLabelRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.tag-label.index'), 'name' => "Danh sách tag"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.tag_label.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update($id, Request $request)
    {
        $attr = $this->tagLabelRepository->find($id);
        $data = $request->only(['name', 'color']);
        $this->tagLabelRepository->edit($attr, $data);
        return redirect()->route('admin.tag-label.index');
    }


    public function delete($id)
    {
        $admin = $this->tagLabelRepository->find($id);
        $this->tagLabelRepository->delete($admin);
        return response(['result' => true]);
    }

}
