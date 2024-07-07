<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Level\CreateRequest;
use App\Http\Requests\Admin\Level\EditRequest;
use App\Repositories\Level\LevelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $levelRepository;
    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository=$levelRepository;
    }

    public function index(Request $request)
    {
//
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->levelRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.level.index')->with(compact('data', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.level.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.level.create')->with(compact( 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $data=$request->only('name');
        $data['status']=$this->levelRepository::STATUS_ACTIVE;
        $level_image=$request->file('level_image');
        if ($level_image) {
            $data['level_image'] = Storage::putFileAs('level', $level_image, $level_image->getClientOriginalName());;
        }
        Cache::forget('level');
        $this->levelRepository->create($data);
        return redirect()->route('admin.level.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=$this->levelRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.level.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        return view('admin.content.level.edit')->with(compact('data','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditRequest $request, $id)
    {
        {
            $attr = $this->levelRepository->find($id);
            $data = $request->only(['name']);
            $level_image= $request->file('level_image');

            if ($level_image) {
                $data['level_image'] = Storage::putFileAs('level', $level_image, $level_image->getClientOriginalName());;
            }
            Cache::forget('level');
            $this->levelRepository->edit($attr, $data);
            return redirect()->route('admin.level.index');
        }
    }
    public function update_status($id, Request $request)
    {
        $user = $this->levelRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->levelRepository->edit($user, $data);
//        Cache::forget('levels');
        return response(['result' => true]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = $this->levelRepository->find($id);
        $this->levelRepository->delete($admin);
        Cache::forget('level');
        return response(['result' => true]);
    }
}
