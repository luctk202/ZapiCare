<?php

namespace App\Exports;

use App\Repositories\WalletLog\WalletLogRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class WalletLogExcel implements FromView
{
    protected $request;
    protected $walletLogRepository;

    function __construct(Request $request, WalletLogRepository $walletLogRepository) {
        $this->request = $request;
        $this->walletLogRepository = $walletLogRepository;
    }

    public function view(): View
    {
        $request = $this->request;
        $where = [

        ];
        if (!empty($request->user_id)) {
            $where['user_id'] = $request->user_id;
        }
        if (!empty($request->type)) {
            $where['type'] = $request->type;
        }
        if (!empty($request->reason_type)) {
            $where['reason_type'] = $request->reason_type;
        }
        if (!empty($request->order_id)) {
            $where['order_id'] = $request->order_id;
        }
        if (!empty($request->created_time)) {
            $aryTime = explode('to', $request->created_time);
            $start_time = !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0;
            $end_time = !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399);
            if (!empty($start_time)) {
                $where['start_time'] = ['created_time', '>=', $start_time];
                if (!empty($end_time)) {
                    $where['end_time'] = ['created_time', '<', $end_time];
                }
            }
        }
        $data = $this->walletLogRepository->get($where, ['created_time' => 'DESC'], [], []);
        $data->load('user');
        return view('admin.content.wallet.list_excel', [
            'data' => $data,
            'type' => $this->walletLogRepository->aryType,
            'reason_type' => $this->walletLogRepository->aryReasonType
        ]);
    }
}
