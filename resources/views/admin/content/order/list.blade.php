@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đơn hàng')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="">Mã đơn hàng</label>
                        <input
                                type="text"
                                name="id"
                                value="{{ request('id') }}"
                                class="form-control"
                        />
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="user_id">Khách hàng</label>
                        <div class="mb-1">
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="{{ request('user_id') }}" selected>{{ request('user_name') }}</option>
                            </select>
                        </div>
                        <input type="hidden" name="user_name" id="user_name" value="{{ request('user_name') }}">
                    </div>
{{--                        <div class="col-3">--}}
{{--                            <label class="form-label" for="shop_id">Shop</label>--}}
{{--                            <div class="mb-1">--}}
{{--                                <select class="form-select" id="shop_id" name="shop_id">--}}
{{--                                    <option value="{{ request('shop_id') }}" selected>{{ request('shop_name') }}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <input type="hidden" name="shop_name" id="shop_name" value="{{ request('shop_name') }}">--}}
{{--                        </div>--}}
                    <div class="col-3">
                        <label class="form-label" for="">Ngày tạo đơn</label>
                        <input
                                type="text"
                                name="created_time"
                                value="{{ request('created_time') }}"
                                class="form-control flatpickr-range"
                                placeholder="DD-MM-YYYY to DD-MM-YYYY"
                        />
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select select2" id="status" name="status">
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('status', 0) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="status_payment">Trạng thái thánh toán</label>
                        <select class="form-select select2" id="status_payment" name="status_payment">
                            @foreach($status_payment as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('status_payment', 0) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="payment_method">Phương thức thanh toán</label>
                        <select class="form-select select2" id="payment_method" name="payment_method">
                            @foreach($payment_method as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('payment_method', 0) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="col-3 mt-50">
                        <label class="form-label" for="payment_method">Hình thức thanh toán</label>
                        <select class="form-select select2" id="payment_method" name="payment_method">
                            @foreach($payment_method as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('payment_method', 0) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    {{--<div class="col-3 mt-50">
                        <label class="form-label" for="producer_id">Nhà cung cấp</label>
                        <div class="mb-1">
                            <select class="form-select" id="producer_id" name="producer_id">
                                <option value="{{ request('producer_id') }}" selected>{{ request('producer_name') }}</option>
                            </select>
                        </div>
                        <input type="hidden" name="producer_name" id="producer_name" value="{{ request('producer_name') }}">
                    </div>--}}

                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i data-feather='search'></i>
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>
    <div class="row">

        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Giá bán</h5>
                        <p class="card-text text-danger">{{ number_format($sum_product, 0, '.', ',') }}</p>
                    </div>
                    {{--<div class="avatar bg-light-info p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Giá cost</h5>
                        <p class="card-text text-danger">{{ number_format($sum_cost, 0, '.', ',') }}</p>
                    </div>
                    {{--<div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Chiết khấu</h5>
                        <p class="card-text text-danger">{{ number_format($sum_discount, 0, '.', ',') }}</p>
                    </div>
                    {{--<div class="avatar bg-light-danger p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Thuế</h5>
                        <p class="card-text text-danger">
                            {{ number_format($sum_vat, 0, '.', ',') }}
                        </p>
                    </div>
                    {{--<div class="avatar bg-light-success p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Phí vận chuyển</h5>
                        <p class="card-text text-danger">
                            {{ number_format($sum_fee, 0, '.', ',') }}
                        </p>
                    </div>
                    {{--<div class="avatar bg-light-success p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Thanh toán</h5>
                        <p class="card-text text-danger">
                        {{ number_format($sum_total, 0, '.', ',') }}
                        </p>
                    </div>
                    {{--<div class="avatar bg-light-success p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mt-1">
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                {{--<a href="{{ route('admin.orders.create') }}" class="btn btn-success">Tạo đơn hàng</a>--}}
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Ngày tạo</th>
                                <th>Mã ĐH</th>
{{--                                <th>Cửa hàng</th>--}}
                                <th>Khách hàng</th>
                                <th>Địa chỉ giao hàng</th>
                                {{--<th>Thành viên</th>--}}
                                <th>Giá bán</th>
                                <th>Giá cost</th>
                                <th>Chiết khấu</th>
                                <th>Thuế + phí</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Hình thức thanh toán</th>
                                <th>Trạng thái thanh toán</th>
                                <th>Ngày xuất kho</th>
                                <th>Ngày thanh toán</th>
                                <th class="text-end" style="position: sticky;right: 0;background-color: #f3f2f7">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $dt)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ date('d-m-Y H:i', $dt->created_time) }}
                                    </td>
                                    <td>
                                        <b>
                                            {{ $dt->id }}
                                        </b>
                                    </td>
{{--                                    <td class="text-nowrap">--}}
{{--                                        @if($dt->shop_id > 0)--}}
{{--                                            <p><strong>{{ $dt->shop->name ?? '' }}</strong></p>--}}
{{--                                            <p>{{ $dt->shop->phone ?? '' }}</p>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                    <td class="text-nowrap">
                                        @if($dt->user_id > 0)
                                            <p><strong>{{ $dt->user->name ?? '' }}</strong></p>
                                            <p>{{ $dt->user->phone ?? '' }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $dt->address }}
                                    </td>
                                    <td>
                                        {{ number_format($dt->total_product, 0, '.', ',') }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($dt->total_cost, 0, '.', ',') }}
                                    </td>
                                    <td>
                                        {{ number_format($dt->total_discount, 0, '.', ',') }}
                                    </td>
                                    <td>
                                        {{ number_format($dt->total_vat + $dt->total_fee, 0, '.', ',') }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format($dt->total, 0, '.', ',') }}
                                    </td>
                                    <td>
                                        @switch($dt->status)
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_NEW)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-secondary">{{ $status[$dt->status] ?? '' }}</span>
                                            @break
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_CONFIRM)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-info">{{ $status[$dt->status] ?? '' }}</span>
                                            @break
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_DONE)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-success">{{ $status[$dt->status] ?? '' }}</span>
                                            @break
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_CANCEL)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-danger">{{ $status[$dt->status] ?? '' }}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-primary">{{ $payment_method[$dt->payment_method] ?? '' }}</span>
                                    </td>
                                    <td>
                                        @switch($dt->status_payment)
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_PAYMENT_UNPAID)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-danger">{{ $status_payment[$dt->status_payment] ?? '' }}</span>
                                            @break
                                            @case(\App\Repositories\Order\OrderRepository::STATUS_PAYMENT_PAID)
                                            <span style="font-weight: 400 !important;" class="badge rounded-pill badge-light-success">{{ $status_payment[$dt->status_payment] ?? '' }}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $dt->export_time ? date('d-m-Y H:i', $dt->export_time) : '' }}
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $dt->payment_time ? date('d-m-Y H:i', $dt->payment_time) : '' }}
                                    </td>
                                    <td class="text-end" style="position: sticky;right: 0; background-color: #fff">
                                        <div class="d-flex">
                                            <a class="btn btn-icon btn-icon rounded-circle btn-outline-info waves-effect waves-float waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Chi tiết" href="{{ route('admin.orders.show',['order' => $dt->id]) }}">
                                                <i data-feather="eye"></i>
                                            </a>
                                            {{--@if($dt->status == \App\Repositories\Order\OrderRepository::STATUS_NEW)
                                                <button type="button" class="ms-50 btn btn-icon btn-icon rounded-circle btn-outline-info waves-effect waves-float waves-light js_btn_open_add" data-id="{{ $dt->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Thêm người xử lý">
                                                    <i data-feather="settings"></i>
                                                </button>
                                            @endif--}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $data->appends(request()->input())->links('admin.panels.paging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- users list ends -->
    <div
            class="modal fade text-start"
            id="add_handle"
            tabindex="-1"
            aria-labelledby="myModalLabel33"
            aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Thông tin thành viên</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <label>Vui lòng nhập tên/số điện thoại: </label>
                        <div class="mb-1">
                            <select class="form-select" id="new_handle_id" name="new_handle_id">
                                {{--<option value="{{ request('new_handle_id') }}" selected>{{ request('new_handle_name') }}</option>--}}
                            </select>
                            <input type="hidden" id="order_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary js_create_handle" data-bs-dismiss="modal">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <style>
        .table th {
            white-space: nowrap !important;
        }
    </style>
@endsection
@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({
                minimumResultsForSearch: -1
            });

            $('.flatpickr-range').flatpickr({
                mode: 'range',
                dateFormat: "d-m-Y",
            });

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa tài khoản này ?",
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
                        ProSellSale.delete(id)
                    }
                });
            })

            $('.js_btn_verified').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác thực tài khoản',
                    text: "Bạn chắc chắn muốn xác thực tài khoản này ?",
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
                        ProSellSale.verified(id)
                    }
                });
            })

            $('.js_update_status').on('change', function () {
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true) {
                    status = 1;
                }
                ProSellSale.updateStatus(id, status)
            })

            let urlParams = new URLSearchParams(window.location.search);

            let data_user_df = null
            if (urlParams.has('user_id') && urlParams.has('user_name')) {
                data_user_df = {
                    id: urlParams.get('user_id'),
                    name: urlParams.get('user_name'),
                    selected: true
                }
            }

            let selectUserAjax = $('#user_id');
            selectUserAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectUserAjax.parent(),
                width: '100%',
                data: [
                    data_user_df
                ],
                ajax: {
                    url: '/admin/user/search',
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

            let data_ctv_df = null
            if (urlParams.has('shop_id') && urlParams.has('shop_name')) {
                data_ctv_df = {
                    id: urlParams.get('shop_id'),
                    name: urlParams.get('shop_name'),
                    selected: true
                }
            }

            let selectHandleAjax = $('#shop_id');
            selectHandleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectHandleAjax.parent(),
                width: '100%',
                data: [
                    data_ctv_df
                ],
                ajax: {
                    url: '/admin/shop/search',
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
                placeholder: 'Tên/Điện thoại',
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
                $('#shop_name').val(user_name);
            });


            let selectNewHandleAjax = $('#new_handle_id');
            selectNewHandleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectNewHandleAjax.parent(),
                width: '100%',
                data: [
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
                    $(repo.element).attr('data-phone', repo.phone);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                /*let user_name = $(this).find(":selected").data("name");
                $('#handle_name').val(user_name);*/
            });

            $('.js_btn_open_add').on('click', function (){
                let id = $(this).data('id');
                $('#add_handle').modal('show');
                $('#new_handle_id').val(null).trigger('change');
                $('#order_id').val(id);
            });

            $('.js_create_handle').on('click', function (){
                let order_id = $('#order_id').val();
                let new_handle_id = $('#new_handle_id').val();
                ProSellSale.addNewHandle(order_id, new_handle_id);
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
            verified: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/sale/verified/' + id,
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
                        url: '/admin/sale/update-status/' + id,
                        data: {status: status}
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
            addNewHandle: function (order_id, user_id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/orders/add-handle',
                        data: {order_id, user_id}
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
        }
    </script>
@endsection
