@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cửa hàng')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.shop.update', ['shop' => $zone->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="name">Tên cửa hàng <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="name" class="form-control" name="name"
                                       placeholder="" value="{{ old('name', $zone->name) }}">
                            </div>
                            @error('name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="code">Mã số thuế <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="code" class="form-control" name="code"
                                       placeholder="" value="{{ old('code', $zone->code) }}">
                            </div>
                            @error('code')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="user_id">Quản lý cửa hàng<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="mb-1">
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="{{ old('user_id', $zone->user_id) }}" selected>{{ old('user_name', $zone->user->name) }}</option>
                                </select>
                            </div>
                            <input type="hidden" name="user_name" id="user_name" value="{{ old('user_name', $zone->user->name) }}">
                            @error('user_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="partner_id">Đối tác quản lý<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="mb-1">
                                <select class="form-select" id="partner_id" name="partner_id">
                                    <option value="{{ old('partner_id',$zone->partner_id) }}" selected>{{ old('partner_name',$zone->partner ? $zone->partner->name : '') }}</option>
                                </select>
                            </div>
                            <input type="hidden" name="partner_name" id="partner_name" value="{{ old('partner_name',$zone->partner ? $zone->partner->name : '') }}">
                            @error('partner_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-3">
                            <label for="logo" class="col-form-label">Logo</label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="logo" id="logo" accept="image/png, image/gif, image/jpeg"
                                   placeholder="Vui lọng chọn file">
                            @error('logo')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_logo">
                                @if($zone->logo)
                                    <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                        <div style="width: 110px;height: 110px;padding:5px">
                                            <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $zone->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($zone->logo  ?? '') }}"/>
                                        </div>
                                        <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                            <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $zone->logo }}</div>
                                            <small style="line-height: 20px"></small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="province_id">Tỉnh thành</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select select2s" id="province_id" name="province_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($province as $v)
                                    <option value="{{ $v->id }}" @if(old('province_id', $zone->province_id) == $v->id) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('province_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="district_id">Quận huyện</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select select2s" id="district_id" name="district_id">
                                <option value="0">Vui lòng chọn</option>
                                @if($district)
                                    @foreach($district as $v)
                                        <option value="{{ $v->id }}" @if(old('district_id', $zone->district_id) == $v->id) selected @endif>{{ $v->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('district_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="ward_id">Phường xã</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select select2s" id="ward_id" name="ward_id">
                                <option value="0">Vui lòng chọn</option>
                                @if($ward)
                                    @foreach($ward as $v)
                                        <option value="{{ $v->id }}" @if(old('ward_id', $zone->ward_id) == $v->id) selected @endif>{{ $v->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ward_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="phone">Số điện thoại <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="phone" class="form-control" name="phone"
                                       placeholder="" value="{{ old('phone', $zone->phone) }}">
                            </div>
                            @error('phone')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="address">Địa chỉ <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="address" class="form-control" name="address"
                                       placeholder="" value="{{ old('address', $zone->address) }}">
                            </div>
                            @error('address')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <label class="col-form-label" for="">Giới thiệu</label>
                        <textarea class="form-control" name="description" id="description" >{!! old('description',$zone->description) !!}</textarea>
                        @error('description')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                        {{--<div id="editor" style="min-height: 300px">
                            {!! old('content') !!}
                        </div>--}}
                    </div>
                </div>
                <div class="col-sm-12 d-flex justify-content-center mt-2">
                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
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

            $('input[name="logo"]').on('change', function () {
                ProSell.readURL(this, 'logo')
            })

            let data_ctv_df = null
            let data_partner = null
            let partner_id = `{{ old('partner_id',$zone->partner_id) }}`
            let partner_name = `{{ old('partner_name',$zone->partner->name ?? '') }}`
            let user_id = `{{ old('user_id', $zone->user_id) }}`
            let user_name = `{{ old('user_name', $zone->user->name ?? '') }}`
            if (user_id && user_name) {
                data_ctv_df = {
                    id: user_id,
                    name: user_name,
                    selected: true
                }
            }
            if(partner_id && partner_name){
                data_partner = {
                    id:partner_id,
                    name:partner_name,
                    selected:true
                }
            }
            let selectSaleAjax = $('#user_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data: [
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
                        if (data.result === true) {
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
                allowClear: true,
                placeholder: 'Tên/Email/Điện thoại',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup = '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' + repo.name + '</span></span><small class="text-muted">' + repo.phone + '</small> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let sale_name = $(this).find(":selected").data("name");
                $('#user_name').val(sale_name);
            });
            let selectPartnerAjax = $('#partner_id');
            selectPartnerAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectPartnerAjax.parent(),
                width: '100%',
                data:[
                    data_partner
                ],
                ajax: {
                    url: '/admin/partner/search',
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
                let pt_name = $(this).find(":selected").data("name");
                $('#partner_name').val(pt_name);
            });

            let data_dev_df = null
            let user_dev_id = `{{ old('user_develop_id', $zone->user_develop_id) }}`
            let user_dev_name = `{{ old('user_develop_name', $zone->user_develop->name ?? '') }}`
            if (user_dev_id && user_dev_name) {
                data_dev_df = {
                    id: user_dev_id,
                    name: user_dev_name,
                    selected: true
                }
            }
            let selectDevAjax = $('#user_develop_id');
            selectDevAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectDevAjax.parent(),
                width: '100%',
                data: [
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
                        if (data.result === true) {
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
                allowClear: true,
                placeholder: 'Tên/Email/Điện thoại',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup = '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' + repo.name + '</span></span><small class="text-muted">' + repo.phone + '</small> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let sale_name = $(this).find(":selected").data("name");
                $('#user_develop_name').val(sale_name);
            });

            $('#province_id').on('change', function () {
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
        });


        var ProSellPage = {
            loadDistrict: function (province_id) {
                if (ProSell.config.ajaxProcess === false) {
                    //ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'get',
                        url: '/admin/address/district',
                        params: {
                            province_id: province_id
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
                        } else {
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
                        params: {
                            district_id: district_id
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
                        } else {
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
