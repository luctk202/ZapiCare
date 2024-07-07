@extends('admin/layouts/fullLayoutMaster')

@section('title', 'Đơn hàng')
@section('page-style')
    <style>
        body{
            font-size: 11px;
            background-color: white;
        }
        table td{
            /*white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;*/
        }
    </style>
@endsection
@section('content')
    <div class="card" id="order_content" style="box-shadow:none">
        <div class="card-body" style="padding: 0;padding-top: 3px">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                {{--<img class="ps-2" src="{{ asset(mix('images/logo/logo_duc_new.png')) }}"
                                     style="width:70%;max-width: 280px" alt="">--}}
                            </div>
                            <div class="row ms-1" style="margin-top: 30px">
                                <div style="margin: 0;" class="d-flex"><div style="width: 85px;margin-right: 3px" class="text-end">KHÁCH HÀNG:</div> <span>{{ $data->name }}</span></div>
                                <div style="margin: 0;" class="d-flex"><div style="width: 85px;margin-right: 3px" class="text-end">TEL:</div>{{ $data->phone }}</span></div>
                            </div>
                        </div>
                        <div class="col-6" style="font-style: italic">
                            @if(!empty($data->export_time))
                            <div class="row d-flex justify-content-center" style="margin-top: 25px">
                                Hà Nội, Ngày {{ date('d', $data->export_time) }}
                                Tháng {{ date('m', $data->export_time) }} Năm {{ date('Y', $data->export_time) }}
                            </div>
                            @endif
                            <div class="row d-flex justify-content-center">
                                Mã đơn hàng: {{ $data->id }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-1">
                        <table class="table table-bordered" style="border-color: #000000 !important;">
                            <tr style="height: 25px">
                                <td class="p-0 text-center text-uppercase">Sản phẩm</td>
                                <td class="text-center p-0 text-uppercase">Số lượng</td>
                                <td class="text-center p-0 pe-25 text-uppercase">Đơn giá</td>
                                <td class="text-center p-0 pe-25 text-uppercase">Giảm giá</td>
                                <td class="text-center p-0 pe-25 text-uppercase">Thuế</td>
                                <td class="text-center p-0 pe-25 text-uppercase">Thành tiền</td>
                            </tr>
                            @php($details = $data->details)
                            @foreach($details as $detail)
                                <tr style="height: 25px">
                                    <td class="p-0 text-center text-uppercase">
                                        {{ ($detail->product_name ?? '') . ' ' . ($detail->attribute_name ?? '') }}
                                    </td>
                                    <td class="text-center p-0">{{ $detail->num ?? 0 }}</td>
                                    <td class="text-end p-0 pe-25">{{ number_format($detail->price, 0, '.', ',') }}</td>
                                    <td class="text-end p-0 pe-25">{{ number_format($detail->total_discount, 0, '.', ',') }}</td>
                                    <td class="text-end p-0 pe-25">{{ number_format($detail->total_vat, 0, '.', ',') }}</td>
                                    <td class="text-end p-0 pe-25">{{ number_format($detail->total, 0, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="d-flex justify-content-between text-uppercase">
                        <div class="">
                        </div>
                        <div>
                        </div>
                        <div class="pe-1 position-relative" style="width: 210px">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        Tổng tiền hàng
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($data->total_product, 0, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Tổng giảm giá
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($data->total_discount, 0, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Tổng thuế
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($data->total_vat, 0, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Tổng thanh toán
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($data->total, 0, '.', ',') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            window.print({
                layout: "landscape",
                pageSize: "A5",
                //scale:0.71
            });
        });
    </script>

@endsection

