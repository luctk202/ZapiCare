@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Liên hệ')


@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            {{--                            <div class="mt-1">--}}
                            {{--                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}--}}
                            {{--                                của {{ $data->total() }} bản ghi--}}
                            {{--                            </div>--}}
                            {{--                            <div>--}}
                            {{--                            </div>--}}
                            {{--                            <div>--}}
                            {{--                                <a href="{{ route('admin.level.create') }}" class="btn btn-success">Thêm cấp độ</a>--}}
                            {{--                            </div>--}}
                            <div>
                                <a href="{{ route('admin.contact.create') }}" class="btn btn-success">Thêm thông tin</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $dt)
                                <tr>
                                    <td>
                                        {{ $dt->phone }}
                                    </td>
                                    <td>{{$dt->email}}</td>
                                    <td>{{$dt->address}}</td>
                                    <td>
                                        <div class="d-flex align-items-center col-actions">
                                            <a class="me-2" href="{{ route('admin.contact.edit',['contact' => $dt->id]) }}"
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
                    {{--                    <div class="card-footer d-flex justify-content-end col-12">--}}
                    {{--                        {{ $data->appends(request()->input())->links('admin.panels.paging') }}--}}
                    {{--                    </div>--}}
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

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa thông tin này ?",
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
                        url: '/admin/contact/delete/' + id,
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
