@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Nhóm quyền')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.role.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <label class="form-label" for="basicInput">Tên nhóm</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
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
                                Đang hiển thị từ {{ $roles->firstItem() }} đến {{ $roles->lastItem() }}
                                của {{ $roles->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.role.create') }}" class="btn btn-success">Tạo nhóm quyền</a>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead class="table-light">
                        <tr>
                            <th>Tên nhóm</th>
                            <th>Số lượng tài khoản</th>
                            {{--<th>Danh sách quyền</th>--}}
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    {{ $role->name }}
                                </td>
                                <td>
                                    {{ $role->admins_count }}
                                </td>
                                {{--<td>
                                    @if($role->permissions)
                                        <ul>
                                            @foreach($role->permissions as $permission)
                                                <li>{{ $permission->display_name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>--}}
                                <td>
                                    {{ $role->created_at }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center col-actions">
                                        <a class="me-2" href="{{ route('admin.role.edit',['role' => $role->id]) }}"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          title=""
                                          data-bs-original-title="Sửa"
                                          aria-label="Sửa">
                                            <i data-feather="edit-2" class="text-body"></i>
                                        </a>
                                        <a class="me-25 js_btn_delete" data-id="{{ $role->id }}"
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
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $roles->appends(request()->input())->links('admin.panels.paging') }}
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
                    text: "Bạn chắc chắn muốn xóa nhóm quyền này ?",
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
                        ProSellRole.delete(id)
                    }
                });
            })
        });
        var ProSellRole = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/role/delete/' + id,
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
