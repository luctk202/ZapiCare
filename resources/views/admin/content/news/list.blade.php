@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Bài viết')

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
        <form method="GET" action="{{ route('admin.news.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="title">Tiêu đề</label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
                    </div>
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
                    
                    {{--<div class="col-sm-3">
                        <label class="form-label" for="blog_category_id">Danh mục bài viết</label>
                        <select class="form-select" id="blog_category_id" name="blog_category_id">
                            <option value="-1">Vui lòng chọn</option>
                            <option value="0">---</option>
                            @foreach($blog_categories as $value)
                                <option value="{{ $value->id }}"
                                        @if($value->id == request('blog_category_id')) selected @endif>{{ $value->name }}</option>
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
                                <a href="{{ route('admin.news.create') }}" class="btn btn-success">
                                    <i data-feather="edit-2"></i>
                                    Viết bài
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Video</th>
                                <th>Danh mục bài</th>
                                <th>SL Xem</th>
                                <th>SL Chia sẻ</th>
                                <th>Tình trạng</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $news)
                                <tr>
                                    <td>
                                        @if(!empty($news->image))
                                            @foreach($news->image as $image)
                                                <div style="padding: 5px">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}" alt="" width="80" height="80">
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        {{ $news->title }}
                                    </td>
                                    <td>
                                        @if($news->video)
                                        {{ \Illuminate\Support\Facades\Storage::disk('r2')->url($news->video) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $news->category->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $news->count_view }}
                                    </td>
                                    <td>
                                        {{ $news->count_share }}
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $news->id }}" @if($news->status == \App\Repositories\News\NewsRepository::STATUS_ACTIVE) checked="true" @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                    data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                {{--<a class="dropdown-item"
                                                   href="#">
                                                    <i class="me-50" data-feather="eye"></i>
                                                    <span>Xem bài viết</span>
                                                </a>--}}
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.news.edit',['news' => $news->id]) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
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

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2({
                //minimumResultsForSearch: -1
            });
            
            $('.js_update_status').on('change',function (){
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true){
                    status = 1;
                }
                ProSellPage.updateStatus(id,status)
            })

            $('.js_btn_approval').on('click', function () {
                let id = $(this).data('id');
                let status = parseInt($(this).data('status'));
                if(status === 1){
                    Swal.fire({
                        title: 'Xác nhận duyệt',
                        text: "Bạn chắc chắn muốn duyệt tin rao này ?",
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
                            ProSellPage.approval(id, status)
                        }
                    });
                }
                if(status === 2){
                    Swal.fire({
                        title: 'Xác nhận không duyệt',
                        text: "Bạn chắc chắn không duyệt tin rao này ?",
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
                            ProSellPage.approval(id, status)
                        }
                    });
                }
            })

            let urlParams = new URLSearchParams(window.location.search);
            let data_news_df = null
            if(urlParams.has('news_id') && urlParams.has('news_title')){
                data_news_df = {
                    id:urlParams.get('news_id'),
                    name:urlParams.get('news_title'),
                    selected:true
                }
            }

            
            let data_user_df = null
            if(urlParams.has('user_id') && urlParams.has('user_name')){
                data_user_df = {
                    id:urlParams.get('user_id'),
                    name:urlParams.get('user_name'),
                    selected:true
                }
            }

            let selectNewsAjax = $('#news_id');
            selectNewsAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectNewsAjax.parent(),
                width: '100%',
                data:[
                    data_news_df
                ],
                ajax: {
                    url: '/admin/news/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        console.log(params, 'params')
                        return {
                            search: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        if(data.result === true){
                            console.log(data, 'data')
                            let dt = data.data
                            return {
                                results: dt.map(item => ({
                                    id:item._id,
                                    name:item.title
                                })),
                                pagination: {
                                    more: false//params.page * 50 < dt.total
                                }
                            };
                        }
                    },
                    cache: true
                },
                placeholder: 'Tiêu đề tin',
                allowClear:true,
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo){
                    console.log(repo, 'repo')
                    if (repo.loading) return repo.text;
                    let markup ='<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">'+repo.name+'</span></span> </div>';
                    return markup;
                },
                templateSelection: function (repo){
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name;
                }
            }).on("select2:select", function (e) {
                let news_title = $(this).find(":selected").data("name");
                $('#news_title').val(news_title);
            });
            

            let selectUserAjax = $('#user_id');
            selectUserAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectUserAjax.parent(),
                width: '100%',
                data:[
                    data_user_df
                ],
                ajax: {
                    url: '/admin/users/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        if(data.result === true){
                            let dt = data.data
                            return {
                                results: dt.data,
                                pagination: {
                                    more: params.page * 50 < dt.total
                                }
                            };
                        }
                    },
                    cache: true
                },
                placeholder: 'Tên/Email/Điện thoại',
                allowClear:true,
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo){
                    if (repo.loading) return repo.text;
                    let markup ='<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">'+repo.name+'</span></span><small class="text-muted">'+ repo.phone +'</small> </div>';
                    return markup;
                },
                templateSelection: function (repo){
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let user_name = $(this).find(":selected").data("name");
                $('#user_name').val(user_name);
            });
            
        });
        var ProSellPage = {
            /*approval: function (id, status) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/news/update-approval/' + id,
                        data:{status:status}
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
                        Swal.fire('Lỗi hệ thống không thể cập nhật!')
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            },*/
            updateStatus:function (id, status){
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/news/update-status/'+id,
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
                        Swal.fire('Lỗi hệ thống không thể cập nhật!')
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            }
        }
    </script>
@endsection
