@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đánh giá')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.reviews.index') }}">
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
                                Đang hiển thị từ {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }}
                                của {{ $reviews->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Tên sản phẩm</th>
                                <th>Khách hàng</th>
                                <th>Đánh giá</th>
                                <th>Bình luận</th>
                                <th>Phản hồi</th>
                                <th>Số bl đã duyệt</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $review->id }}
                                        </b>
                                    </td>
                                    <td style="white-space: nowrap !important;">
                                        {{ $review->product->name ?? 'Sản phẩm k tồn tại' }}
                                    </td>
                                    <td>
                                        {{ $review->name }}
                                    </td>
                                    <td>
                                        {{ $review->rating}}
                                    </td>
                                    <td>
                                        {{ $review->comment }}
                                    </td>
                                    <td>
                                        {{$review->childrenReviews[0]->comment ?? ''}}
                                    </td>
                                    <td><p class="text-truncate-container text-truncate-2">{{ count($review->childrenReviews->where('status',1)).'/'.count($review->childrenReviews)}}</p></td>
                                    <td style="white-space: nowrap !important;">
                                        {{ $review->created_at }}
                                    </td>

                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $review->id }}"
                                                   @if($review->status ==1) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">
                                            <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                    data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.reviews.edit',['review'=>$review->id])}}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
{{--                                                <a class="dropdown-item js_btn_delete" data-id="{{ $review->id }}"--}}
{{--                                                   href="javascript:void(0)">--}}
{{--                                                    <i class="me-50" data-feather="trash"></i>--}}
{{--                                                    <span>Delete</span>--}}
{{--                                                </a>--}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $reviews->appends(request()->input())->links('admin.panels.paging') }}
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
                        url: '/admin/reviews/delete/' + id,
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
                        url: '/admin/reviews/update-status/' + id,
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
