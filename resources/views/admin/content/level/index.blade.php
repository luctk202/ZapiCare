@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cấp độ')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.level.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tên</label>
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
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.level.create') }}" class="btn btn-success">Thêm cấp độ</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
{{--                                <th width="10%">Mã</th>--}}
                                <th>Tên</th>
                                <th>Ảnh mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $dt)
                                <tr>
{{--                                    <td>--}}
{{--                                        <b>--}}
{{--                                            {{ $dt->id }}--}}
{{--                                        </b>--}}
{{--                                    </td>--}}
                                    <td>
                                        {{ $dt->name }}
                                    </td>
                                    <td>
                                        @if(!empty($dt->image))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($dt->image) }}"
                                                 alt="{{ $dt->name }}" width="80">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $dt->id }}"
                                                   @if($dt->status ==\App\Repositories\Level\LevelRepository::STATUS_ACTIVE) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $dt->created_at }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center col-actions">
                                            <a class="me-2" href="{{ route('admin.level.edit',['level' => $dt->id]) }}"
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

@section('page-style')
    <style>
        .table th {
            white-space: nowrap !important;
        }
    </style>
@endsection
@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.js_update_status').on('change', function () {
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true) {
                    status = 1;
                }
                ProSellPage.updateStatus(id, status)
            })

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa cấp độ này ?",
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
                        ProSellPage.delete(id)
                    }
                });
            })
        });
        var ProSellPage = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/level/delete/' + id,
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
                        url: '/admin/level/update-status/' + id,
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
        }
    </script>
@endsection
