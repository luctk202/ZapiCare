<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralManagementRequest;
use App\Http\Requests\Admin\Producer\CreateRequest;
use App\Http\Requests\Admin\Producer\EditRequest;
use App\Repositories\GeneralManagement\GeneralManagementRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class GeneralManagementController extends Controller
{
    public $generalManagementRepository;

    public function __construct(GeneralManagementRepository $generalManagementRepository)
    {
        $this->generalManagementRepository = $generalManagementRepository;
    }

    public function index(Request $request)
    {
        $dataGeneralManagements = $this->generalManagementRepository->paginate();

        return view('admin.content.general_management.list', compact('dataGeneralManagements'));
    }

    public function create()
    {
        return view('admin.content.general_management.create');
    }

    public function store(GeneralManagementRequest $request)
    {
        $data = $request->except('_token', '_method');
        $this->generalManagementRepository->create($data);
        return redirect()->route('admin.generalManagement.index')->with('msg', "Thêm quản lí chung thành công !");
    }

    public function edit($id)
    {
        $generalManagement = $this->generalManagementRepository->findOrFail($id);
        return view('admin.content.general_management.edit', compact('generalManagement'));
    }

    public function update(GeneralManagementRequest $request, $id)
    {
        $data = $request->except('_token', '_method');
        $modelGeneralManagement = $this->generalManagementRepository->findOrFail($id);
        $this->generalManagementRepository->edit($modelGeneralManagement, $data);

        return redirect()->route('admin.generalManagement.edit', $id);
    }

    public function delete($id)
    {

        $modelGeneralManagement = $this->generalManagementRepository->find($id);
        $this->generalManagementRepository->delete($modelGeneralManagement);
        return redirect()->route('admin.generalManagement.index')->with('msg', "Xóa quản lí chung thành công !");
    }

}
