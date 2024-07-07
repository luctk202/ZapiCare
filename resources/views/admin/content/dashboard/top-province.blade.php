@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Khu vực bán chạy')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <style>
        .table th {
            white-space: nowrap !important;
        }
    </style>
@endsection

@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a
                    class="nav-link active"
                    id="home-tab"
                    href="#"
            >Tỉnh thành</a
            >
        </li>
        <li class="nav-item">
            <a
                    class="nav-link"
                    id="about-tab"
                    {{--data-bs-toggle="tab"--}}
                    href="{{ route('admin.report.top-district') }}"
                    {{--aria-controls="about"
                    role="tab"--}}
            >Quận huyện</a
            >
        </li>
    </ul>
    <div class="card">
        <form method="GET" action="{{ route('admin.report.top-province') }}">
            <div class="card-body">
                {{--<h4 class="card-title">{{ translate('Bộ lọc') }}</h4>--}}
                <div class="row">
                    <div class="col-xl-4">
                        <label class="form-label" for="title">Thời gian</label>
                        <div class="input-group input-group-merge mb-2" >
                            <span class="input-group-text" id="basic-addon-search2" style="background-color: #fff !important;"><i class="" data-feather="calendar"></i></span>
                            <input type="text" name="time" value="{{ request('time', date('d-m-Y' , time() - 30*86400) . ' to ' . date('d-m-Y' , time())) }}" class="form-control bg-transparent flat-picker" placeholder="YYYY-MM-DD" style="background-color: #ffffff !important;min-width: 220px;padding-left: 15px"/>
                        </div>
                        {{--<input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">--}}
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Tỉnh thành</th>
                                <th>Doanh số</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $key => $value)
                            <tr>
                                <td>
                                    <b>{{ $key + 1 }}</b>
                                </td>
                                <td>
                                    {{ $value->name }}
                                </td>
                                <td>
                                    {{ number_format($value->export_total, 0, '.', ',') }}
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

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2({
                //minimumResultsForSearch: -1
            });

            /*let end = dateToString(new Date(), '-')
            let thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            let start = dateToString(thirtyDaysAgo, '-')*/
            let flatPicker = $('.flat-picker')
            if (flatPicker.length) {
                flatPicker.each(function () {
                    $(this).flatpickr({
                        mode: 'range',
                        dateFormat: 'd-m-Y',
                        //defaultDate: [start, end],
                        /*onChange: function (selectedDates, dateStr, instance) {
                            if (selectedDates.length === 2) {
                                let start = dateToString(selectedDates[0], '-')
                                let end = dateToString(selectedDates[1], '-')
                                ProSellPage.loadRevenue(start, end)
                                ProSellPage.loadSum(start, end)
                                ProSellPage.loadTop(start, end)
                                //console.log(start, end)
                            }

                        }*/
                    });
                });
            }
            
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
