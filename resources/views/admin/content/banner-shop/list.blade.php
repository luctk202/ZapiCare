@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Banner Shop')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.banner-shop.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Vui lòng chọn</option>
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}" @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="col-3">
                        <label class="form-label" for="category_id">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="0" @if(request('category_id', 0) == 0) selected @endif>Vui lòng chọn</option>
                            @foreach($categories as  $value)
                                <option value="{{ $value->id }}"
                                        @if(request('category_id', 0) == $value->id) selected @endif>{{ $value->prefix . $value->name }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    <div class="col-3">
                        <label class="form-label" for="position">Vị trí hiển thị</label>
                        <select class="form-select" id="position" name="position">
                            <option value="">Vui lòng chọn</option>
                            @foreach($positions as $k => $value)
                                <option value="{{ $k }}" @if(request('position') == $k) selected @endif>{{ $value }}</option>
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
                                <a href="{{ route('admin.banner-shop.create') }}" class="btn btn-success">Tạo banner</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Tên</th>
                                <th>Ảnh</th>
                                <th>Vị trí</th>
                                <th>Cửa hàng</th>
                                <th>Trạng thái</th>
                                <th>Thời gian chạy</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $banner)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $banner->name }}
                                        </b>
                                    </td>
                                    <td>
                                        @if(!empty($banner->image))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image) }}" alt="{{ $banner->name }}" width="80" >
                                        @endif
                                    </td>
                                    <td>
                                        {{ $positions[$banner->position] ?? '' }}
                                    </td>
                                    <td>
                                        {{ $banner->shop->name?? '' }}
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $banner->id }}"
                                                   @if($banner->status == 1) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $banner->start_time . ' to ' .  $banner->end_time }}
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
                                                   href="{{ route('admin.banner-shop.edit',['banner_shop' => $banner->id]) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                <a class="dropdown-item js_btn_delete"
                                                   href="javascript:void(0)" data-id="{{ $banner->id }}">
                                                    <i class="me-50" data-feather="trash"></i>
                                                    <span>Xóa</span>
                                                </a>
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
                    text: "Bạn chắc chắn muốn xóa banner này ?",
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
                        url: '/admin/banner-shop/delete/' + id,
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
                        url: '/admin/banner-shop/update-status/' + id,
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
