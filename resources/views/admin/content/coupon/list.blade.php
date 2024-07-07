@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Flash Sale')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.coupon.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Mã giảm giá</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="Vui lòng nhập"
                               value="{{ request('code') }}">
                    </div>
{{--                    <div class="col-3">--}}
{{--                        <label class="form-label" for="shop_id">Shop</label>--}}
{{--                        <div class="mb-1">--}}
{{--                            <select class="form-select" id="shop_id" name="shop_id">--}}
{{--                                <option value="{{ request('shop_id') }}" selected>{{ request('shop_name') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <input type="hidden" name="shop_name" id="shop_name" value="{{ request('shop_name') }}">--}}
{{--                    </div>--}}
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
                                <a href="{{ route('admin.coupon.create') }}" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th class="text-center">Mã code</th>
{{--                            <th class="text-center">Nguồn</th>--}}
                            <th class="text-center">SL</th>
                            <th class="text-center">SL đã dùng</th>
                            <th class="text-center">Thời gian</th>
                            <th class="text-center">Trạng thái</th>
{{--                            <th class="text-center">Cấu hình</th>--}}
                            <th class="text-center">Mô tả</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($data as $dt)
                            <tr>
                                <td>
                                    <b>
                                        {{ $dt->code }}
                                    </b>
                                </td>

                                <td>
                                    {{ $dt->num ?? '' }}
                                </td>
                                <td>
                                    {{ $dt->num_used ?? 0 }}
                                </td>
                                <td>
                                    @if($dt->start_time && $dt->end_time)
                                    {{ date('d-m-Y H:i', $dt->start_time) . ' to ' .  date('d-m-Y H:i', $dt->end_time) }}
                                    @endif
                                </td>
                                <td>
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="checkbox" data-id="{{ $dt->id }}"
                                               @if($dt->status == \App\Repositories\Coupon\CouponRepository::STATUS_ACTIVE) checked="true"
                                               @endif class="form-check-input js_update_status"/>
                                    </div>
                                </td>
{{--                                <td>--}}

{{--                                </td>--}}
                                <td>
                                    {{  $dt->description }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center col-actions">
                                        <a class="me-2" href="{{ route('admin.coupon.edit',['coupon' => $dt->id]) }}"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Sửa"
                                           aria-label="Sửa">
                                            <i data-feather="edit-2" class="text-body"></i>
                                        </a>
                                        <a class="me-25 js_btn_delete" data-id="{{ $dt->id }}"
                                           href="javascript:void(0)"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                           data-bs-original-title="Xóa" aria-label="Xóa">
                                            <i data-feather="trash" class="text-body"></i>
                                        </a>
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
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
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
            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa mã giảm giá này ?",
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

            $('.js_update_status').on('change', function () {
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true) {
                    status = 1;
                }
                ProSellSale.updateStatus(id, status)
            })

            $('.js_update_home').on('change', function () {
                let id = $(this).data('id');
                let home = 0;
                if ($(this).prop('checked') === true) {
                    home = 1;
                }
                ProSellSale.updateHome(id, home)
            })

            let urlParams = new URLSearchParams(window.location.search);
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

        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/coupon/delete/' + id,
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
                        url: '/admin/coupon/update-status/' + id,
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
            updateHome: function (id, home) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/flash-sale/update-home/' + id,
                        data: {home: home}
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
            }
        }
    </script>
@endsection
