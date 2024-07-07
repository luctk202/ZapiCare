@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Sản phẩm')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.product.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
                    </div>
{{--                    <div class="col-3">--}}
{{--                        <label class="form-label" for="basicInput">SKU</label>--}}
{{--                        <input type="text" class="form-control" name="sku" id="sku" placeholder="Vui lòng nhập"--}}
{{--                               value="{{ request('sku') }}">--}}
{{--                    </div>--}}
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select select2" id="status" name="status">
                            @foreach($status as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="status">Trạng thái xét duyệt</label>
                        <select class="form-select select2" id="approval" name="approval">
                            @foreach($approval as $k => $v)
                                <option value="{{ $k }}"
                                        @if(request('approval', -1) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="category_id">Danh mục</label>
                        <select class="form-select select2" id="category_id" name="category_id">
                            <option value="0" selected>Vui lòng chọn</option>
                            @foreach($categories as $value)
                                <option value="{{ $value->id }}" @if($value->id == request('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="col-3 mt-1">
                        <label class="form-label" for="brand_id">Thương hiệu</label>
                        <select class="form-select select2" id="brand_id" name="brand_id">
                            <option value="0">Vui lòng chọn</option>
                            @foreach($brand as $value)
                                <option value="{{ $value->id }}" @if($value->id == request('brand_id')) selected @endif>{{ $value->name }}</option>
                            @endforeach
                        </select>
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
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.product.create') }}" class="btn btn-success">Thêm sản phẩm</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th width="10%">Mã</th>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Giá bán</th>
                                <th>Danh mục</th>
                                {{--<th>Thương hiệu</th>--}}
                                <th>Số lượng bán</th>
                                <th>Trạng thái</th>
                                <th>Tiêu biểu</th>
                                <th>Xét duyệt</th>
                                <th>Ngày tạo</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $product)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $product->id }}
                                        </b>
                                    </td>
                                    <td>
                                        @if($product->avatar)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->avatar) }}" alt="" width="80" height="80">
                                        @endif
                                    </td>
                                    <td style="min-width: 300px">
                                        {{ $product->name }}
                                    </td>
                                    <td class="text-danger text-end">
                                        {{ number_format($product->price_sell, 0, '.', ',') }}
                                    </td>
                                    <td>
                                        {{ $product->category->name ?? '' }}
                                    </td>
                                    {{--<td>
                                        {{ $product->brand->name ?? '' }}
                                    </td>--}}
                                    <td class="text-center">
                                        {{ $product->total_sell }}
                                    </td>

                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $product->id }}"
                                                   @if($product->status == \App\Repositories\Product\ProductRepository::STATUS_SHOW) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $product->id }}"
                                                   @if($product->typical == \App\Repositories\Product\ProductRepository::TYPICAL_SHOW) checked="true"
                                                   @endif class="form-check-input js_update_typical"/>
                                        </div>
                                    </td>
{{--                                    <td>--}}
{{--                                        <div class="form-check form-check-primary form-switch">--}}
{{--                                            <input type="checkbox" data-id="{{ $product->id }}"--}}
{{--                                                   @if($product->new == \App\Repositories\Product\ProductRepository::NEW_SHOW) checked="true"--}}
{{--                                                   @endif class="form-check-input js_update_new"/>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td>
                                        <span style="font-weight: 400 !important;" class="badge rounded-pill
                                            @if($product->approval == \App\Repositories\Product\ProductRepository::APPROVAL_WAIT) badge-light-warning
                                            @elseif($product->approval == \App\Repositories\Product\ProductRepository::APPROVAL_DONE) badge-light-success
                                            @else badge-light-danger
                                            @endif">
                                            {{ $approval[$product->approval] ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $product->created_at }}
                                    </td>
                                    <td >
                                        <div class="dropdown">
                                            <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                    data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.product.edit',['product' => $product->id]) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                @if($product->approval != \App\Repositories\Product\ProductRepository::APPROVAL_DONE)
                                                    <a class="dropdown-item js_btn_approval" data-id="{{ $product->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="shield" class="me-50"></i>
                                                        <span>Duyệt</span>
                                                    </a>
                                                @endif
                                                @if($product->approval != \App\Repositories\Product\ProductRepository::APPROVAL_CANCEL)
                                                    <a class="dropdown-item js_btn_cancel_approval" data-id="{{ $product->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="alert-triangle" class="me-50"></i>
                                                        <span>Hủy duyệt</span>
                                                    </a>
                                                @endif
                                                {{--<a class="dropdown-item"
                                                   href="{{ route('admin.product.edit-stock',['id' => $product->id]) }}">
                                                    <i data-feather="database" class="me-50"></i>
                                                    <span>Nhập kho</span>
                                                </a>--}}
                                                {{--@if($product->approval != \App\Repositories\Product\ProductRepository::APPROVAL_DONE)
                                                    <a class="dropdown-item js_btn_verified" data-id="{{ $product->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="shield" class="me-50"></i>
                                                        <span>Duyệt</span>
                                                    </a>
                                                @endif
                                                @if($product->approval != \App\Repositories\Product\ProductRepository::APPROVAL_CANCEL)
                                                    <a class="dropdown-item js_btn_verified" data-id="{{ $product->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="alert-triangle" class="me-50"></i>
                                                        <span>Hủy duyệt</span>
                                                    </a>
                                                @endif--}}

                                                <a class="dropdown-item js_btn_delete" data-id="{{ $product->id }}"
                                                   href="javascript:void(0)">
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
            $('.select2').select2({
                minimumResultsForSearch: -1
            });

            $('.js_update_status').on('change', function () {
                let id = $(this).data('id');
                let status = $(this).prop('checked') ? 1 : 0;
                ProSellPage.updateStatus(id, status);
            });

            $('.js_update_typical').on('change', function () {
                let id = $(this).data('id');
                let typical = $(this).prop('checked') ? 1 : 0;
                ProSellPage.updateTypical(id, typical);
            });

            // $('.js_update_new').on('change', function () {
            //     let id = $(this).data('id');
            //     let isNew = $(this).prop('checked') ? 1 : 0;
            //     ProSellPage.updateNew(id, isNew);
            // });

            $('.js_btn_approval').on('click',function (){
                console.log(1)
                let id = $(this).data('id');
                ProSellPage.approval(id)
            });

            $('.js_btn_cancel_approval').on('click',function (){
                let id = $(this).data('id');
                ProSellPage.cancel_approval(id)
            });

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa sản phẩm này?",
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
                    ProSell.loading.show();
                    ProSell.config.ajaxProcess = true;
                    Axios({
                        method: 'post',
                        url: '/admin/product/delete/' + id,
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload();
                        } else {
                            ProSell.loading.hide();
                            ProSell.config.ajaxProcess = false;
                            Swal.fire(res.message);
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide();
                        ProSell.config.ajaxProcess = false;
                    });
                }
            },
            updateStatus: function (id, status) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show();
                    ProSell.config.ajaxProcess = true;
                    Axios({
                        method: 'post',
                        url: '/admin/product/update-status/' + id,
                        data: { status: status }
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload();
                        } else {
                            ProSell.loading.hide();
                            ProSell.config.ajaxProcess = false;
                            Swal.fire(res.message);
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide();
                        ProSell.config.ajaxProcess = false;
                    });
                }
            },
            updateTypical: function (id, typical) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show();
                    ProSell.config.ajaxProcess = true;
                    Axios({
                        method: 'post',
                        url: '/admin/product/update-typical/' + id,
                        data: { typical: typical }
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload();
                        } else {
                            ProSell.loading.hide();
                            ProSell.config.ajaxProcess = false;
                            Swal.fire(res.message);
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide();
                        ProSell.config.ajaxProcess = false;
                    });
                }
            },
            // updateNew: function (id, isNew) {
            //     if (ProSell.config.ajaxProcess === false) {
            //         ProSell.loading.show();
            //         ProSell.config.ajaxProcess = true;
            //         Axios({
            //             method: 'post',
            //             url: '/admin/product/update-new/' + id,
            //             data: { new: isNew }
            //         }).then(function (response) {
            //             let res = response.data;
            //             if (res.result === true) {
            //                 location.reload();
            //             } else {
            //                 ProSell.loading.hide();
            //                 ProSell.config.ajaxProcess = false;
            //                 Swal.fire(res.message);
            //             }
            //         }).catch(function (error) {
            //             ProSell.loading.hide();
            //             ProSell.config.ajaxProcess = false;
            //         });
            //     }
            // },
            approval: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show();
                    ProSell.config.ajaxProcess = true;
                    Axios({
                        method: 'post',
                        url: '/admin/product/approval/' + id,
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload();
                        } else {
                            ProSell.loading.hide();
                            ProSell.config.ajaxProcess = false;
                            Swal.fire(res.message);
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide();
                        ProSell.config.ajaxProcess = false;
                    });
                }
            },
            cancel_approval: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show();
                    ProSell.config.ajaxProcess = true;
                    Axios({
                        method: 'post',
                        url: '/admin/product/cancel-approval/' + id,
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            location.reload();
                        } else {
                            ProSell.loading.hide();
                            ProSell.config.ajaxProcess = false;
                            Swal.fire(res.message);
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide();
                        ProSell.config.ajaxProcess = false;
                    });
                }
            },
        }
    </script>
@endsection
