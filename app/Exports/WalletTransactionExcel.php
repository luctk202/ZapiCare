<?php

namespace App\Exports;

use App\Repositories\Export\ExportRepository;
use App\Repositories\WalletTransaction\WalletTransactionRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class WalletTransactionExcel implements FromView
{
    protected $request;
    protected $walletTransactionRepository;

    function __construct(Request $request, WalletTransactionRepository $walletTransactionRepository) {
        $this->request = $request;
        $this->walletTransactionRepository = $walletTransactionRepository;
    }

    public function view(): View
    {
        $request = $this->request;
        $where = [

        ];
        if (!empty($request->id)) {
            $where['id'] = $request->id;
        }
        if (!empty($request->user_id)) {
            $where['user_id'] = $request->user_id;
        }
        if (!empty($request->type)) {
            $where['type'] = $request->type;
        }
        if (!empty($request->status)) {
            $where['status'] = $request->status;
        }
        if (!empty($request->status_payment)) {
            $where['status_payment'] = $request->status_payment;
        }
        if (!empty($request->payment_method)) {
            $where['payment_method'] = $request->payment_method;
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
        $data = $this->walletTransactionRepository->get($where, ['created_time' => 'DESC'], [], []);
        $data->load('user');
        return view('admin.content.wallet-transaction.list_excel', [
            'data' => $data,
            'type' => $this->walletTransactionRepository->aryType,
            'status' => $this->walletTransactionRepository->aryStatus,
            'status_payment' => $this->walletTransactionRepository->aryStatusPayment,
        ]);
    }
}
