@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Danh mục bài viết')

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

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('admin.blog-category.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="phone">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label" for="category_id">Danh mục tin đăng</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="-1">Vui lòng chọn</option>
                            <option value="0">---</option>
                            @foreach($categories as $value)
                                <option value="{{ $value->id }}"
                                        @if($value->id == request('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
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
                            <div>
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.blog-category.create') }}" class="btn btn-success">Thêm danh mục</a>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-bordered" style="margin-bottom: 50px">
                            <thead class="table-light">
                            <tr>
                                <th>Danh mục</th>
                                <th>Danh mục tin</th>
                                <th>Vị trí</th>
                                <th width="10%">Trạng thái</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $category)
                                <tr>
                                    <td>
                                        {{ $category->name }}
                                    </td>
                                    <td>
                                        {{ $category->category->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $category->position }}
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $category->id }}"
                                                   @if($category->status == \App\Repositories\BlogCategory\BlogCategoryRepository::STATUS_ACTIVE) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
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
                                                   href="{{ route('admin.blog-category.edit',['blog_category' => $category->id]) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                {{--<a class="dropdown-item js_btn_delete"
                                                   href="javascript:void(0)" data-id="{{ $category->id }}">
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
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2({
               // minimumResultsForSearch: -1
            });
            $('.js_update_status').on('change',function (){
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true){
                    status = 1;
                }
                ProSellPage.updateStatus(id,status)
            })

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa danh mục này ?",
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
                        url: '/admin/blog-category/delete/' + id,
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
            updateStatus:function (id, status){
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/blog-category/update-status/'+id,
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
