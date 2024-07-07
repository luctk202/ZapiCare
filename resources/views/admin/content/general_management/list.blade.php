@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Quản lý chung')

@section('content')
    <!-- users list start -->

    {{--    <div class="card">--}}
    {{--        <form method="GET" action="{{ route('admin.product.index') }}">--}}
    {{--            <div class="card-body">--}}
    {{--                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-3">--}}
    {{--                        <label class="form-label" for="basicInput">Tên sản phẩm</label>--}}
    {{--                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"--}}
    {{--                               value="{{ request('name') }}">--}}
    {{--                    </div>--}}
    {{--                    <div class="col-3">--}}
    {{--                        <label class="form-label" for="basicInput">SKU</label>--}}
    {{--                        <input type="text" class="form-control" name="sku" id="sku" placeholder="Vui lòng nhập"--}}
    {{--                               value="{{ request('sku') }}">--}}
    {{--                    </div>--}}
    {{--                    <div class="col-3">--}}
    {{--                        <label class="form-label" for="status">Trạng thái</label>--}}
    {{--                        <select class="form-select select2" id="status" name="status">--}}
    {{--                            @foreach($status as $k => $v)--}}
    {{--                                <option value="{{ $k }}"--}}
    {{--                                        @if(request('status', -1) == $k) selected @endif>{{ $v }}</option>--}}
    {{--                            @endforeach--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-3">--}}
    {{--                        <label class="form-label" for="status">Trạng thái xét duyệt</label>--}}
    {{--                        <select class="form-select select2" id="approval" name="approval">--}}
    {{--                            @foreach($approval as $k => $v)--}}
    {{--                                <option value="{{ $k }}"--}}
    {{--                                        @if(request('approval', -1) == $k) selected @endif>{{ $v }}</option>--}}
    {{--                            @endforeach--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-3">--}}
    {{--                        <label class="form-label" for="category_id">Danh mục</label>--}}
    {{--                        <select class="form-select select2" id="category_id" name="category_id">--}}
    {{--                            <option value="0" selected>Vui lòng chọn</option>--}}
    {{--                            @foreach($categories as $value)--}}
    {{--                                <option value="{{ $value->id }}" @if($value->id == request('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>--}}
    {{--                            @endforeach--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                    --}}{{--<div class="col-3 mt-1">--}}
    {{--                        <label class="form-label" for="brand_id">Thương hiệu</label>--}}
    {{--                        <select class="form-select select2" id="brand_id" name="brand_id">--}}
    {{--                            <option value="0">Vui lòng chọn</option>--}}
    {{--                            @foreach($brand as $value)--}}
    {{--                                <option value="{{ $value->id }}" @if($value->id == request('brand_id')) selected @endif>{{ $value->name }}</option>--}}
    {{--                            @endforeach--}}
    {{--                        </select>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="card-footer d-flex justify-content-end">--}}
    {{--                <button type="submit" class="btn btn-primary">--}}
    {{--                    <i data-feather='search'></i>--}}
    {{--                    Tìm kiếm--}}
    {{--                </button>--}}
    {{--            </div>--}}
    {{--        </form>--}}
    {{--    </div>--}}
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mt-1">
                                Đang hiển thị từ {{ $dataGeneralManagements->firstItem() }}
                                đến {{ $dataGeneralManagements->lastItem() }}
                                của {{ $dataGeneralManagements->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.generalManagement.create') }}" class="btn btn-success">Thêm quản lí chung</a>
                            </div>
                        </div>
                    </div>
                    @if(session('msg'))
                        <div class="alert alert-success">
                            {{ session('msg') }}
                        </div>
                    @endif
                    <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th width="10%">Id</th>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th>Nội dung</th>
                                <th>Ngày tạo</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($dataGeneralManagements as $generalManagement)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $generalManagement->id }}
                                        </b>
                                    </td>

                                    <td> {{ $generalManagement->title }}</td>
                                    <td> {{ $generalManagement->slug }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($generalManagement->content, 50, $end='...') }}</td>
                                    <td>
                                        {{ $generalManagement->created_at }}
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
                                                   href="{{ route('admin.generalManagement.edit', $generalManagement->id) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                @if($generalManagement->approval != \App\Repositories\Product\ProductRepository::APPROVAL_DONE)
                                                    <a class="dropdown-item js_btn_approval"
                                                       data-id="{{ $generalManagement->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="shield" class="me-50"></i>
                                                        <span>Duyệt</span>
                                                    </a>
                                                @endif
                                                @if($generalManagement->approval != \App\Repositories\Product\ProductRepository::APPROVAL_CANCEL)
                                                    <a class="dropdown-item js_btn_cancel_approval"
                                                       data-id="{{ $generalManagement->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="alert-triangle" class="me-50"></i>
                                                        <span>Hủy duyệt</span>
                                                    </a>
                                                @endif
                                                {{--<a class="dropdown-item"
                                                   href="{{ route('admin.product.edit-stock',['id' => $generalManagement->id]) }}">
                                                    <i data-feather="database" class="me-50"></i>
                                                    <span>Nhập kho</span>
                                                </a>--}}
                                                {{--@if($generalManagement->approval != \App\Repositories\Product\ProductRepository::APPROVAL_DONE)
                                                    <a class="dropdown-item js_btn_verified" data-id="{{ $generalManagement->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="shield" class="me-50"></i>
                                                        <span>Duyệt</span>
                                                    </a>
                                                @endif
                                                @if($generalManagement->approval != \App\Repositories\Product\ProductRepository::APPROVAL_CANCEL)
                                                    <a class="dropdown-item js_btn_verified" data-id="{{ $generalManagement->id }}"
                                                       href="javascript:void(0)">
                                                        <i data-feather="alert-triangle" class="me-50"></i>
                                                        <span>Hủy duyệt</span>
                                                    </a>
                                                @endif--}}


                                                <a class="dropdown-item"
                                                   data-id="{{ $generalManagement->id }}"
                                                   href="">

                                                    <form method="POST"
                                                          action="{{ route('admin.generalManagement.delete', $generalManagement->id) }}"
                                                          class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa không ?')"
                                                                class="delete-button">
                                                            <i class="me-50" data-feather="trash"></i>
                                                            <span>Xóa</span>
                                                        </button>
                                                    </form>

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
                        {{ $dataGeneralManagements->appends(request()->input())->links('admin.panels.paging') }}
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
                let status = 0;
                if ($(this).prop('checked') === true) {
                    status = 1;
                }
                ProSellPage.updateStatus(id, status)
            })

            $('.js_btn_approval').on('click', function () {
                console.log(1)
                let id = $(this).data('id');
                ProSellPage.approval(id)
            })

            $('.js_btn_cancel_approval').on('click', function () {
                let id = $(this).data('id');
                ProSellPage.cancel_approval(id)
            })

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa sản phẩm này ?",
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

    </script>
@endsection

@section('style')
    <style>.delete-button {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
        }

    </style>
@endsection
