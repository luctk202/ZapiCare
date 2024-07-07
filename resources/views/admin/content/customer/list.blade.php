@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Khách hàng')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.customer.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Vui lòng nhập"
                               value="{{ request('phone') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tên</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
                    </div>
{{--                    <div class="col-3">--}}
{{--                        <label class="form-label" for="basicInput">CCCD</label>--}}
{{--                        <input type="text" class="form-control" name="code" id="code" placeholder="Vui lòng nhập"--}}
{{--                               value="{{ request('code') }}">--}}
{{--                    </div>--}}
                    {{--<div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select select2" id="status" name="status">
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    {{--<div class="col-3">
                        <label class="form-label" for="verified">Xác thực</label>
                        <select class="form-select select2" id="verified" name="verified">
                            @foreach($verified_status as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('verified', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    {{--<div class="col-3 mt-1">
                        <label class="form-label" for="group_id">Nhóm khách hàng</label>
                        <select class="form-select select2" id="group_id" name="group_id">
                            <option value="0"
                                    @if(request('group_id', 0) == 0) selected @endif>Vui lòng chọn</option>
                            @foreach($groups as $k => $v)
                                <option value="{{ $v->id }}"
                                        @if(request('group_id', 0) == $v->id) selected @endif>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    {{--<div class="col-3 mt-1">
                        <label class="form-label" for="parent_id">Giới thiệu bởi</label>
                        <div class="mb-1">
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="{{ request('parent_id') }}" selected>{{ request('parent_name') }}</option>
                            </select>
                        </div>
                        <input type="hidden" name="parent_name" id="parent_name" value="{{ request('parent_name') }}">
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
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mt-1">
                                Đang hiển thị từ {{ $users->firstItem() }} đến {{ $users->lastItem() }}
                                của {{ $users->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.customer.create') }}" class="btn btn-success">Tạo tài khoản</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên</th>
{{--                            <th>CCCD</th>--}}
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            {{--<th>Quản lý bởi</th>
                            <th>Hạng khách hàng</th>--}}
                            <th>Ngày tạo</th>
                            <th>TT Xác thực</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <b>
                                        {{ $user->id }}
                                    </b>
                                </td>
                                <td style="white-space: nowrap !important;">
                                    {{ $user->name }}
                                </td>
{{--                                <td>--}}
{{--                                    {{ $user->code }}--}}
{{--                                </td>--}}
                                <td>
                                    {{ $user->phone }}
                                </td>
                                <td>
                                    {{ $user->email }}
                                </td>
                                {{--<td>
                                    @if($user->parent)
                                        <div><b>{{ $user->parent->name }}</b></div>
                                        <div><small>{{ $user->parent->phone }}</small></div>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->group->name ?? '' }}
                                </td>--}}
                                <td style="white-space: nowrap !important;">
                                    {{ $user->created_at }}
                                </td>
                                <td>
                                    <i class="ti fs-4 ti-shield-check text-success"></i>
                                    <span style="font-weight: 400 !important;"
                                          class="badge rounded-pill @if($user->verified == \App\Repositories\User\UserRepository::VERIFIED) badge-light-success @else badge-light-danger @endif">{{ $verified_status[$user->verified] ?? '' }}</span>
                                </td>

                                <td>
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="checkbox" data-id="{{ $user->id }}"
                                               @if($user->status == \App\Repositories\User\UserRepository::STATUS_ACTIVE) checked="true"
                                               @endif class="form-check-input js_update_status"/>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button"
                                                class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item"
                                               href="{{ route('admin.customer.edit',['customer' => $user->id]) }}">
                                                <i class="me-50" data-feather="edit-2"></i>
                                                <span>Sửa</span>
                                            </a>
                                            @if($user->verified != \App\Repositories\User\UserRepository::VERIFIED)
                                            <a class="dropdown-item js_btn_verified" data-id="{{ $user->id }}"
                                               href="javascript:void(0)">
                                                <i data-feather="check-circle" class="me-50"></i>
                                                <span>Xác thực</span>
                                            </a>
                                            @endif
                                            {{--<a class="dropdown-item js_btn_delete" data-id="{{ $user->id }}"
                                               href="javascript:void(0)">
                                                <i class="me-50" data-feather="trash"></i>
                                                <span>Delete</span>
                                            </a>--}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $users->appends(request()->input())->links('admin.panels.paging') }}
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

        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/customer/delete/' + id,
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
                        url: '/admin/customer/verified/' + id,
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
                        url: '/admin/customer/update-status/' + id,
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
            }
        }
    </script>
@endsection
