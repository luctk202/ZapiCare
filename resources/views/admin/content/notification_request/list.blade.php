@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thông báo')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.notification.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="-1">Vui lòng chọn</option>
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>
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
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.notification.create') }}" class="btn btn-success">Gửi thông
                                    báo</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Biểu tưởng</th>
                                <th>Loại thông báo</th>
                                <th>Trạng thái</th>
                                <th>Thời gian gửi</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $notification)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $notification->title }}
                                        </b>
                                    </td>
                                    <td>
                                        {{ $notification->text }}
                                    </td>
                                    <td>
                                        @if(!empty($notification->icon))
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($notification->icon) }}"
                                                alt="{{ $notification->title }}" width="80">
                                        @endif
                                    </td>
                                    <td>
                                        {{ $type[$notification->type] ?? '' }}
                                    </td>
                                    <td>
                                    <span style="font-weight: 400 !important;"
                                          class="badge rounded-pill @if($notification->status == \App\Repositories\NotificationRequest\NotificationRequestRepository::STATUS_SENT) badge-light-success @else badge-light-secondary @endif">{{ $status[$notification->status] ?? '' }}</span>
                                    </td>
                                    <td>
                                        {{ date('d-m-Y H:i', $notification->time_send) }}
                                    </td>
                                    <td>
                                        {{ $notification->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                    data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if($notification->status == \App\Repositories\NotificationRequest\NotificationRequestRepository::STATUS_REQUEST)
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.notification.edit',['notification' => $notification->id]) }}">
                                                        <i class="me-50" data-feather="edit-2"></i>
                                                        <span>Sửa</span>
                                                    </a>
                                                    <a class="dropdown-item js_btn_delete"
                                                       href="javascript:void(0)" data-id="{{ $notification->id }}">
                                                        <i class="me-50" data-feather="trash"></i>
                                                        <span>Xóa</span>
                                                    </a>
                                                @endif
{{--                                                    @if($notification->status == \App\Repositories\NotificationRequest\NotificationRequestRepository::STATUS_REQUEST)--}}


{{--                                                @endif--}}
                                            </div>
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
            $('select').select2({
                minimumResultsForSearch: -1
            });
            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa thông báo này ?",
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

        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/notification/delete/' + id,
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
                        url: '/admin/banner/update-status/' + id,
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
