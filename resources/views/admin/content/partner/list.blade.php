@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đối tác')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.partner.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tên</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Vui lòng nhập"
                               value="{{ request('phone') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Mã số thuế</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="Vui lòng nhập"
                               value="{{ request('code') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="user_id">Quản lý đối tác</label>
                        <div class="mb-1">
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="{{ request('user_id') }}" selected>{{ request('user_name') }}</option>
                            </select>
                        </div>
                        <input type="hidden" name="user_name" id="user_name" value="{{ request('user_name') }}">
                    </div>
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
                        <label class="form-label" for="district_id">Tỉnh/Thành</label>
                        <div class="mb-1">
                            <select class="form-select select2s" id="province_id" name="province_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($province as $k => $v)
                                    <option value="{{ $v->id }}" @if($v->id == request('province_id')) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="district_id">Quận/Huyện</label>
                        <div class="mb-1">
                            <select class="form-select select2" id="district_id" name="district_id">
                                <option value="0">Vui lòng chọn</option>
                                @if($district)
                                    @foreach($district as $k => $v)
                                        <option value="{{ $v->id }}" @if($v->id == request('district_id')) selected @endif>{{ $v->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="ward_id">Phường/Xã</label>
                        <div class="mb-1">
                            <select class="form-select select2" id="ward_id" name="ward_id">
                                <option value="0">Vui lòng chọn</option>
                                @if($ward)
                                    @foreach($ward as $k => $v)
                                        <option value="{{ $v->id }}" @if($v->id == request('ward_id')) selected @endif>{{ $v->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
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
                                Đang hiển thị từ {{ $zones->firstItem() }} đến {{ $zones->lastItem() }}
                                của {{ $zones->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.partner.create') }}" class="btn btn-success">Thêm đối tác</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Logo</th>
                                <th>Mã số thuế</th>
                                <th>Quản lý cửa hàng</th>
                                <th>Trạng thại</th>
                                <th>Liên hệ</th>
                                <th>Địa chỉ</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($zones as $zone)
                                <tr>
                                    <td>
                                        <b>
                                            {{ $zone->id }}
                                        </b>
                                    </td>
                                    <td>
                                        @if(!empty($zone->logo))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($zone->logo) }}" alt="{{ $zone->name }}" width="80" >
                                        @endif
                                    </td>
                                    <td>
                                        {{ $zone->code }}
                                    </td>
                                    <td style="white-space: nowrap !important;">
                                        <b>{{ $zone->user->name }}</b>
                                        <p>{{ $zone->user->phone }}</p>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-primary form-switch">
                                            <input type="checkbox" data-id="{{ $zone->id }}"
                                                   @if($zone->status == \App\Repositories\Partner\PartnerRepository::STATUS_SHOW) checked="true"
                                                   @endif class="form-check-input js_update_status"/>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap !important;">
                                        {{ $zone->phone }}
                                    </td>
                                    <td style="white-space: nowrap !important;">
                                        <p>{{ $zone->address }}</p>
                                        <p>Tỉnh/thành : {{ $zone->province_name }}</p>
                                        <p>Quận/huyện : {{ $zone->district_name }}</p>
                                        <p>Phường/xã : {{ $zone->ward_name }}</p>
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
                                                   href="{{ route('admin.partner.edit',['partner' => $zone->id]) }}">
                                                    <i class="me-50" data-feather="edit-2"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.partner.setting',['id' => $zone->id]) }}">
                                                    <i class="me-50" data-feather="settings"></i>
                                                    <span>Cài đặt</span>
                                                </a>
                                                {{--@if($user->verified != \App\Repositories\User\UserRepository::VERIFIED)
                                                <a class="dropdown-item js_btn_verified" data-id="{{ $user->id }}"
                                                   href="javascript:void(0)">
                                                    <i data-feather="check-circle" class="me-50"></i>
                                                    <span>Xác thực</span>
                                                </a>
                                                @endif--}}
                                                {{--<a class="dropdown-item js_btn_delete" data-id="{{ $zone->id }}"
                                                   href="javascript:void(0)">
                                                    <i class="me-50" data-feather="trash"></i>
                                                    <span>Delete</span>
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
                        {{ $zones->appends(request()->input())->links('admin.panels.paging') }}
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
            $('.select2s').select2({
                //minimumResultsForSearch: -1
            });
            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa cửa hàng này ?",
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

            let urlParams = new URLSearchParams(window.location.search);
            let data_ctv_df = null
            if(urlParams.has('user_id') && urlParams.has('user_name')){
                data_ctv_df = {
                    id:urlParams.get('user_id'),
                    name:urlParams.get('user_name'),
                    selected:true
                }
            }
            let selectSaleAjax = $('#user_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data:[
                    data_ctv_df
                ],
                ajax: {
                    url: '/admin/ctv/search',
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
                allowClear:true,
                placeholder: 'Tên/Email/Điện thoại',
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
                let sale_name = $(this).find(":selected").data("name");
                $('#user_name').val(sale_name);
            });

            let data_dev_df = null
            if(urlParams.has('user_develop_id') && urlParams.has('user_develop_name')){
                data_dev_df = {
                    id:urlParams.get('user_develop_id'),
                    name:urlParams.get('user_develop_name'),
                    selected:true
                }
            }
            let selectDevAjax = $('#user_develop_id');
            selectDevAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectDevAjax.parent(),
                width: '100%',
                data:[
                    data_dev_df
                ],
                ajax: {
                    url: '/admin/ctv/search',
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
                allowClear:true,
                placeholder: 'Tên/Email/Điện thoại',
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
                let sale_name = $(this).find(":selected").data("name");
                $('#user_develop_name').val(sale_name);
            });

            $('#province_id').on('change', function (){
                $(this).attr('disabled', true)
                $('#district_id').html(
                    '<option value="0">Vui lòng chọn</option>'
                )
                $('#ward_id').html(
                    '<option value="0">Vui lòng chọn</option>'
                )
                ProSellPage.loadDistrict($(this).val())
            })

            $('#district_id').on('change', function (event) {
                $(this).attr('disabled', true)
                $('#ward_id').html(
                    '<option value="0">Vui lòng chọn</option>'
                )
                ProSellPage.loadWard($(this).val())
            })

            $('.js_update_status').on('change',function (){
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true){
                    status = 1;
                }
                ProSellPage.updateStatus(id,status)
            })
        });


        var ProSellSale = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/zone/delete/' + id,
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

        var ProSellPage = {
            updateStatus:function (id, status){
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/partner/update-status/'+id,
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
            },
            loadDistrict: function (province_id) {
                if (ProSell.config.ajaxProcess === false) {
                    //ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'get',
                        url: '/admin/address/district',
                        params:{
                            province_id:province_id
                        }
                    }).then(function (response) {
                        $('#province_id').attr('disabled', false)
                        let res = response.data;
                        if (res.result === true) {
                            let data = res.data
                            data.forEach(function (item) {
                                $('#district_id').append('<option value="' + item.id + '">' + item.name + '</option>')
                            })
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                        }else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                        }

                    }).catch(function (error) {
                        //ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            },
            loadWard: function (district_id) {
                if (ProSell.config.ajaxProcess === false) {
                    //ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'get',
                        url: '/admin/address/ward',
                        params:{
                            district_id:district_id
                        }
                    }).then(function (response) {
                        $('#district_id').attr('disabled', false)
                        let res = response.data;
                        if (res.result === true) {
                            let data = res.data
                            data.forEach(function (item) {
                                $('#ward_id').append('<option value="' + item.id + '">' + item.name + '</option>')
                            })
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                        }else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                        }

                    }).catch(function (error) {
                        //ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                    });
                }
            }
        }
    </script>
@endsection
