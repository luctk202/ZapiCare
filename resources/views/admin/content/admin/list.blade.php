@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Tài khoản')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.admin.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Vui lòng nhập" value="{{ request('email') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tên</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập" value="{{ request('name') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="role_id">Nhóm quyền</label>
                        <select class="form-select" id="role_id" name="role_id">
                            @foreach($roles as $key => $role)
                                <option value="{{ $key }}" @if(request('role_id', 0) == $key) selected @endif>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}" @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                Đang hiển thị từ {{ $admins->firstItem() }} đến {{ $admins->lastItem() }}
                                của {{ $admins->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.admin.create') }}" class="btn btn-success">Tạo tài khoản</a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Nhóm quyền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($admins as $admin)
                            <tr>
                                <td>
                                    {{ $admin->name }}
                                </td>
                                <td>
                                    {{ $admin->email }}
                                </td>
                                <td>
                                        <span style="font-weight: 400 !important;"
                                              class="badge rounded-pill @if($admin->status == \App\Repositories\Admin\AdminRepository::STATUS_ACTIVE) badge-light-primary @endif">{{ $roles[$admin->role_id] ?? '' }}</span>
                                </td>
                                <td>
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="checkbox" data-id="{{ $admin->id }}"
                                               @if($admin->status == \App\Repositories\Admin\AdminRepository::STATUS_ACTIVE) checked="true"
                                               @endif class="form-check-input js_update_status"/>
                                    </div>
                                </td>
                                <td>
                                    {{ $admin->created_at }}
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
                                               href="{{ route('admin.admin.edit',['admin' => $admin->id]) }}">
                                                <i data-feather="edit-2"></i>
                                                <span>Edit</span>
                                            </a>
                                            <a class="dropdown-item js_btn_delete" data-id="{{ $admin->id }}"
                                               href="javascript:void(0)">
                                                <i data-feather="trash"></i>
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $admins->appends(request()->input())->links('admin.panels.paging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- users list ends -->
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
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
                        ProSellAdmin.delete(id)
                    }
                });
            })

            $('.js_update_status').on('change',function (){
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true){
                    status = 1;
                }
                ProSellAdmin.updateStatus(id,status)
            })
        });
        var ProSellAdmin = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/admin/delete/'+id,
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload()
                        }else {
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
            updateStatus:function (id, status){
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/admin/update-status/'+id,
                        data:{status:status}
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload()
                        }else {
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
