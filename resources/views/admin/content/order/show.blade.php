@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đơn hàng')


@section('content')
    <!-- users list start -->

    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                {{--<div class="logo-wrapper">
                                    <h3 class="text-primary invoice-logo">Vuexy</h3>
                                </div>--}}
                                {{--<p class="card-text mb-25">Office 149, 450 South Brand Brooklyn</p>
                                <p class="card-text mb-25">San Diego County, CA 91905, USA</p>
                                <p class="card-text mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>--}}
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Mã đơn hàng
                                    <span class="invoice-number">#{{ $data->id }}</span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title" style="width: 9rem;">Ngày tạo đơn:</p>
                                    <p class="invoice-date">{{ date('d-m-Y H:i', $data->created_time) }}</p>
                                </div>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title" style="width: 9rem;">Ngày thanh toán:</p>
                                    <p class="invoice-date">{{ $data->payment_time ? date('d-m-Y H:i', $data->payment_time) : 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title" style="width: 9rem;">Ngày xuất kho:</p>
                                    <p class="invoice-date">{{ $data->export_time ? date('d-m-Y H:i', $data->export_time) : 'Chưa cập nhật' }}</p>
                                </div>
                                @if($data->user)
                                    <div class="invoice-date-wrapper">
                                        <p class="invoice-date-title" style="width: 9rem;">Người đặt đơn:</p>
                                        <p class="invoice-date"><a href="{{ route('admin.customer.index', ['phone' => $data->user->phone]) }}">{{'['. $data->user->phone .']' . $data->user->name }}</a></p>
                                    </div>
                                @endif
                                @if($data->shop)
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title" style="width: 9rem;">Cửa hàng:</p>
                                    <p class="invoice-date"><a href="{{ route('admin.shop.index', ['phone' => $data->shop->phone]) }}">{{'['. $data->shop->phone .']' . $data->shop->name }}</a></p>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- Header ends -->
                    </div>

                    <hr class="invoice-spacing"/>

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                            <div class="col-xl-7 p-0">
                                <h6 class="mb-2"><b>Thông tin giao hàng:</b></h6>
                                <p class="mb-25"><span class="fw-bold">Tên khách hàng:</span> {{ $data->name }}</p>
                                <p class="mb-25"><span class="fw-bold">Số điện thoại:</span> {{ $data->phone }}</p>
                                <p class="mb-25"><span class="fw-bold">Địa chỉ giao hàng:</span> {{ $data->address }}</p>
                            </div>
                            <div class="col-xl-5 p-0 mt-xl-0 mt-2">
                                <h6 class="mb-2"><b>Thông tin thanh toán:</b></h6>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td class="pe-1"><span class="fw-bold">Hình thức thanh toán:</span></td>
                                        <td>{{ $payment_method[$data->payment_method] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-1"><span class="fw-bold">Trạng thái thanh toán:</span></td>
                                        <td class="text-danger"><b>{{ $status_payment[$data->status_payment] ?? '' }}</b></td>
                                    </tr>
                                    <tr>
                                        <td class="pe-1"><span class="fw-bold">Trạng thái đơn hàng:</span></td>
                                        <td class="text-danger"><b>{{ $status[$data->status] ?? '' }}</b></td>
                                    </tr>
                                    @if($data->status == \App\Repositories\Order\OrderRepository::STATUS_CANCEL)
                                        @if($data->cancel_id > 0)
                                            <tr>
                                                <td class="pe-1"><span class="fw-bold">Người hủy:</span></td>
                                                <td><a href="{{ route('admin.ctv.index', ['phone' => $data->cancel->phone]) }}">{{'['. $data->cancel->phone .']' . $data->cancel->name }}</a></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="pe-1"><span class="fw-bold">Lý do hủy</span></td>
                                            <td>{{ $data->cancel_note ?? '' }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Address and Contact ends -->

                    <!-- Invoice Description starts -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="py-1">SKU</th>
                                <th class="py-1">Sản phẩm</th>
                                <th class="py-1 text-end">Đơn giá</th>
                                <th class="py-1 text-center">Số lượng</th>
                                <th class="py-1 text-end">Tiền hàng</th>
                                <th class="py-1 text-end">Chiết khấu</th>
                                <th class="py-1 text-end">Thuế</th>
                                <th class="py-1 text-end">Thành tiền</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data->details as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $detail->product_sku }}
                                    </td>
                                    <td class="py-1" style="min-width: 300px">
                                        <p class="card-text fw-bold mb-25">{{ $detail->product_name }}</p>
                                        <p class="card-text text-nowrap">
                                            {{ $detail->attribute_name }}
                                        </p>
                                    </td>
                                    <td class="py-1 text-end">
                                        <span class="fw-bold">{{ number_format($detail->price, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="py-1 text-center">
                                        <span class="fw-bold">{{ $detail->num }}</span>
                                    </td>
                                    <td class="py-1 text-end">
                                        <span class="fw-bold">{{ number_format($detail->total_product, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="py-1 text-end">
                                        <span class="fw-bold">{{ number_format($detail->total_discount, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="py-1 text-end">
                                        <span class="fw-bold">{{ number_format($detail->total_vat, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="py-1 text-end">
                                        <span class="fw-bold">{{ number_format($detail->total, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="card-body invoice-padding pb-1 p">
                        <div class="col-md-12 order-md-1 order-2 mt-md-0 mt-3 mb-3">
                            <p class="card-text mb-0">
                                <span class="fw-bold">Ghi chú:</span> <span class="ms-75"> {{ $data->note }}</span>
                            </p>
                        </div>

                        <div class="row ">
                            <div class="col-lg-12 d-flex justify-content-end order-md-2 order-1">
                                <div class="invoice-total-wrapper" style="min-width: 20rem">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tổng tiền hàng gốc:</p>
                                        <p class="invoice-total-amount">{{ number_format($data->total_product, 0, '.', ',') }}</p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tổng chiết khấu người mua:</p>
                                        <p class="invoice-total-amount">{{ number_format($data->total_discount, 0, '.', ',') }}</p>
                                    </div>
{{--                                    <div class="invoice-total-item">--}}
{{--                                        <p class="invoice-total-title">Tổng lợi nhuận:</p>--}}
{{--                                        <p class="invoice-total-amount">{{ number_format($data->total_profit, 0, '.', ',') }}</p>--}}
{{--                                    </div>--}}
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tổng thuế:</p>
                                        <p class="invoice-total-amount">{{ number_format($data->total_vat, 0, '.', ',') }}</p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Phí vận chuyển:</p>
                                        <p class="invoice-total-amount">{{ number_format($data->total_fee, 0, '.', ',') }}</p>
                                    </div>
                                    <hr class="my-50"/>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tổng thanh toán:</p>
                                        <p class="invoice-total-amount">{{ number_format($data->total, 0, '.', ',') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Invoice Description ends -->

                    {{--<hr class="invoice-spacing"/>--}}

                    <!-- Invoice Note starts -->
                    {{--<div class="card-body invoice-padding pt-0">
                        <div class="row">
                            <div class="col-12">
                                <span class="fw-bold">Note:</span>
                                <span
                                >It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
                projects. Thank You!</span
                                >
                            </div>
                        </div>
                    </div>--}}
                    <!-- Invoice Note ends -->
                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        @if($data->status == \App\Repositories\Order\OrderRepository::STATUS_NEW)
                            <button class="btn btn-outline-info w-100 js_confirm mb-50" data-id="{{ $data->id }}">Xác nhận đơn hàng</button>
                        @endif
                            @if($data->status == \App\Repositories\Order\OrderRepository::STATUS_CONFIRM)
                                <button class="btn btn-outline-info w-100 js_done_status mb-50" data-id="{{ $data->id }}">Hoàn thành đơn hàng</button>
                            @endif
{{--                        @if($data->status == \App\Repositories\Order\OrderRepository::STATUS_CONFIRM && $data->handle_type == \App\Repositories\Order\OrderRepository::HANDLE_TYPE_ADMIN)--}}
{{--                            <button class="btn btn-outline-info w-100 mb-75 js_export mb-50" data-id="{{ $data->id }}">Xuất kho</button>--}}
{{--                        @endif--}}
                        <a class="btn btn-outline-secondary w-100 mb-75 mb-50" href="{{ route('admin.orders.print', ['id' => $data->id]) }}" target="_blank"> In phiếu xuất </a>
{{--                        @if($data->status == \App\Repositories\Order\OrderRepository::STATUS_DONE)--}}

{{--                        @endif--}}
                        @if($data->status_payment == \App\Repositories\Order\OrderRepository::STATUS_PAYMENT_UNPAID && $data->status != \App\Repositories\Order\OrderRepository::STATUS_CANCEL && $data->status != \App\Repositories\Order\OrderRepository::STATUS_DONE)
{{--                            @if($data->handle_type == \App\Repositories\Order\OrderRepository::HANDLE_TYPE_ADMIN || $data->payment_method == \App\Repositories\Order\OrderRepository::METHOD_TRANSFER)--}}
                                <button class="btn btn-outline-secondary w-100 js_cancel mb-50" data-id="{{ $data->id }}">Hủy đơn hàng</button>
{{--                            @endif--}}
{{--                            @if($data->payment_method == \App\Repositories\Order\OrderRepository::METHOD_TRANSFER)--}}
{{--                                <button class="btn btn-outline-success w-100 js_payment" data-id="{{ $data->id }}">Xác nhận thanh toán</button>--}}
{{--                            @endif--}}
                        @endif
                            @if($data->status_payment == \App\Repositories\Order\OrderRepository::STATUS_PAYMENT_UNPAID && $data->status != \App\Repositories\Order\OrderRepository::STATUS_CANCEL)
                                <button class="btn btn-outline-success w-100 js_payment" data-id="{{ $data->id }}">Xác nhận thanh toán</button>
                            @endif
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </section>
    <!-- users list ends -->
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
@endsection
@section('page-style')
    <style>
        .table th {
            white-space: nowrap !important;
        }
    </style>
@endsection
@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({
                minimumResultsForSearch: -1
            });

            $('.js_payment').on('click', function (){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhận thanh toán',
                    text: "Bạn muốn xác nhận thành toán cho đơn hàng này ?",
                    /*icon: 'warning',*/
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Bỏ qua',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        ProSellSale.updatePayment(id)
                    }
                });
            });

            $('.js_confirm').on('click', function (){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân đơn hàng',
                    text: "Bạn muốn xác nhận đơn hàng này ?",
                    /*icon: 'warning',*/
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Bỏ qua',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        ProSellSale.updateStatus(id, `{{ \App\Repositories\Order\OrderRepository::STATUS_CONFIRM }}`)
                    }
                });
            });
            $('.js_done_status').on('click', function (){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Hoàn thành đơn hàng',
                    text: "Bạn muốn hoàn thành đơn hàng này ?",
                    /*icon: 'warning',*/
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Bỏ qua',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        ProSellSale.updateStatus(id, `{{ \App\Repositories\Order\OrderRepository::STATUS_DONE }}`)
                    }
                });
            });

            $('.js_export').on('click', function (){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xuất hàng',
                    text: "Bạn muốn xuất kho đơn hàng này ?",
                    /*icon: 'warning',*/
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Bỏ qua',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        ProSellSale.updateStatus(id, `{{ \App\Repositories\Order\OrderRepository::STATUS_DONE }}`)
                    }
                });
            });

            $('.js_cancel').on('click', function (){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân hủy đơn hàng',
                    text: "Bạn muốn hủy đơn hàng này ?",
                    /*icon: 'warning',*/
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Bỏ qua',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        ProSellSale.updateStatus(id, `{{ \App\Repositories\Order\OrderRepository::STATUS_CANCEL }}`)
                    }
                });
            });

            let urlParams = new URLSearchParams(window.location.search);

            let data_sale_df = null
            if (urlParams.has('user_id') && urlParams.has('user_name')) {
                data_sale_df = {
                    id: urlParams.get('user_id'),
                    name: urlParams.get('user_name'),
                    selected: true
                }
            }

            let selectSaleAjax = $('#user_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data: [
                    data_sale_df
                ],
                ajax: {
                    url: '/admin/ctv/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        if (data.result === true) {
                            let dt = data.data
                            return {
                                results: dt.data,
                                pagination: {
                                    more: params.page * 50 < dt.total
                                }
                            };
                        }
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: 'Tên/Email/Điện thoại',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup = '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' + repo.name + '</span></span><small class="text-muted">' + repo.phone + '</small> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let user_name = $(this).find(":selected").data("name");
                $('#user_name').val(user_name);
            });
        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/sale/delete/' + id,
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload()
                        } else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            Swal.fire(res.message)
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            },
            updateStatus: function (id, status) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/orders/update-status/' + id,
                        data: {status: status}
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            ProSell.loading.hide()
                            Swal.fire('Cập nhật đơn hàng thành công!')
                            location.reload()
                        } else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            Swal.fire(res.message)
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            },
            updatePayment: function (id, status) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/orders/update-payment/' + id,
                        data: {status: status}
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            ProSell.loading.hide()
                            Swal.fire('Thanh toán thành công!')
                            location.reload()
                        } else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            Swal.fire(res.message)
                        }
                    }).catch(function (error) {
                        Swal.fire('Lỗi cập nhật')
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            }
        }
    </script>
@endsection
