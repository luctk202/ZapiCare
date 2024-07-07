<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralManagementRequest;
use App\Repositories\GeneralManagement\GeneralManagementRepository;
use Illuminate\Http\Request;

class GeneralManagementController extends Controller
{
    public $generalManagementRepository;

    public function __construct(GeneralManagementRepository $generalManagementRepository)
    {
        $this->generalManagementRepository = $generalManagementRepository;
    }

    public function index()
    {
        try {
            $dataGeneralManagements = $this->generalManagementRepository->paginate();
            return response()->json(['data' => $dataGeneralManagements], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi lấy danh sách quản lý chung', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(GeneralManagementRequest $request)
    {
        try {
            $data = $request->except('_token', '_method');
            $this->generalManagementRepository->create($data);
            return response()->json(['message' => 'Thêm quản lý chung thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi thêm quản lý chung', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($slug)
    {
        try {
            $generalManagement = $this->generalManagementRepository->whereOne('slug', $slug);

            return response()->json(['data' => $generalManagement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi lấy thông tin quản lý chung', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(GeneralManagementRequest $request, $id)
    {
        try {
            $data = $request->except('_token', '_method');
            $modelGeneralManagement = $this->generalManagementRepository->findOrFail($id);
            $this->generalManagementRepository->edit($modelGeneralManagement, $data);
            return response()->json(['message' => 'Cập nhật quản lý chung thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi cập nhật quản lý chung', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $modelGeneralManagement = $this->generalManagementRepository->find($id);
            if ($modelGeneralManagement) {
                $this->generalManagementRepository->delete($modelGeneralManagement);
                return response()->json(['message' => 'Xóa quản lý chung thành công!'], 200);
            } else {
                return response()->json(['error' => 'Không tìm thấy quản lý chung'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi xóa quản lý chung', 'message' => $e->getMessage()], 500);
        }
    }
}
