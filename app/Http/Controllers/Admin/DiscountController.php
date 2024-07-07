<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\CreateRequest;
use App\Http\Requests\Admin\Discount\EditRequest;
use App\Repositories\Discount\DiscountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    public $discountRepository;

    public function __construct(DiscountRepository $discountRepository){
        $this->discountRepository = $discountRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        /*if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }*/
        $data = $this->discountRepository->paginate($where, ['discount_total' => 'ASC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $type = $this->discountRepository->aryDiscountType;
        return view('admin.content.discount.list')->with(compact('data', 'breadcrumbs', 'type'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.discount.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $type = $this->discountRepository->aryDiscountType;
        return view('admin.content.discount.create')->with(compact( 'breadcrumbs', 'type'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['discount_total', 'discount_value', 'discount_type']);
        $data['status'] = $this->discountRepository::STATUS_SHOW;
        $this->discountRepository->create($data);
        return redirect()->route('admin.discount.index');
    }

    public function edit($id)
    {
        $data = $this->discountRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.discount.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $type = $this->discountRepository->aryDiscountType;
        return view('admin.content.discount.edit')->with(compact('data', 'breadcrumbs', 'type'));
    }

    public function update($id, EditRequest $request)
    {
        $attr = $this->discountRepository->find($id);
        $data = $request->only(['discount_total', 'discount_value', 'discount_type']);
        $this->discountRepository->edit($attr, $data);
        return redirect()->route('admin.discount.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->discountRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->discountRepository->edit($user, $data);
        return response(['result' => true]);
    }


    public function delete($id){
        $admin = $this->discountRepository->find($id);
        $this->discountRepository->delete($admin);
        return response(['result' => true]);
    }
}
