@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Flash Sale')


@section('content')
    <!-- users list start -->

    
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
                                <a href="{{ route('admin.flash-sale.create') }}" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th class="text-center">Tiêu đề</th>
                            <th class="text-center">Ảnh</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hiện chủ</th>
                            <th class="text-center">Thời gian chạy</th>
                            {{--<th class="text-center">Khung giờ</th>--}}
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($data as $dt)
                            <tr>
                                <td>
                                    <b>
                                        {{ $dt->title }}
                                    </b>
                                </td>
                                <td>
                                    @if(!empty($dt->image))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($dt->image) }}" alt="{{ $dt->title }}" width="80" >
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="checkbox" data-id="{{ $dt->id }}"
                                               @if($dt->status == \App\Repositories\FlashSale\FlashSaleRepository::STATUS_ACTIVE) checked="true"
                                               @endif class="form-check-input js_update_status"/>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="checkbox" data-id="{{ $dt->id }}"
                                               @if($dt->home == \App\Repositories\FlashSale\FlashSaleRepository::HOME) checked="true"
                                               @endif class="form-check-input js_update_home"/>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ date('d-m-Y H:i', $dt->start_time) . ' to ' .  date('d-m-Y H:i', $dt->end_time) }}
                                </td>
                                {{--<td class="text-center">
                                    {{ ($dt->start_hour != $dt->end_hour) ? ($dt->start_hour . 'h - ' .  $dt->end_hour . 'h') : '' }}
                                </td>--}}
                                <td>
                                    <div class="dropdown">
                                        <button type="button"
                                                class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item"
                                               href="{{ route('admin.flash-sale.edit',['flash_sale' => $dt->id]) }}">
                                                <i class="me-50" data-feather="edit-2"></i>
                                                <span>Sửa</span>
                                            </a>
                                            {{--<a class="dropdown-item js_btn_delete"
                                               href="javascript:void(0)" data-id="{{ $banner->id }}">
                                                <i class="me-50" data-feather="trash"></i>
                                                <span>Xóa</span>
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

            $('.js_update_home').on('change', function () {
                let id = $(this).data('id');
                let home = 0;
                if ($(this).prop('checked') === true) {
                    home = 1;
                }
                ProSellSale.updateHome(id, home)
            })

        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/banner/delete/' + id,
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
                        url: '/admin/flash-sale/update-status/' + id,
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
