<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBank;
use App\Models\Logs;
use App\Repositories\Bank\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBankController extends Controller
{
    public $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function edit(){
        $data = AdminBank::find(1);
        $banks = [0 => 'Vui lòng chọn'] + $this->bankRepository->pluck('name')->toArray();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Sửa"]
        ];
        return view('admin.content.admin_bank.edit')->with(compact('breadcrumbs', 'data', 'banks'));
    }

    public function update(Request $request){
        $data = AdminBank::find(1);
        $data->bank_name = $request->bank_name;
        $data->bank_username = $request->bank_username;
        $data->bank_number = $request->bank_number;
        $data->bank_content = $request->bank_content;
        DB::transaction(function () use ($data) {
            $data->save();
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_bank',
                'item_id' => $data->id,
                'data' => $data->toArray()
            ]);
        });
        return redirect()->route('admin.admin-bank.edit')->with('success', 'Cấu hình thành công');
    }
}
