@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Nhà phân phối')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sale.update', ['sale' => $user->id]) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="name">Tên nhà phân phối <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="name" class="form-control" name="name"
                                       placeholder="" value="{{ old('name', $user->name) }}">
                            </div>
                            @error('name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="email">Email</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="email" class="form-control" name="email"
                                       placeholder="" value="{{ old('email', $user->email) }}">
                            </div>
                            @error('email')
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
                                <input type="text" id="phone" class="form-control" readonly
                                       placeholder="" value="{{ old('phone', $user->phone) }}">
                            </div>
                            @error('phone')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="password">Mật khẩu <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                       autocomplete="false" >
                            </div>
                            @error('password')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="name">Nhóm</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select type="text" id="group_id" class="form-control select2" name="group_id">
                                    <option value="">Vui lòng chọn</option>
                                    @foreach($groups as $k => $v)
                                        <option value="{{ $k }}" @if($k === old('group_id', $user->group_id)) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('group_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="referred_by">Giới thiệu bởi</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select" id="referred_by" name="referred_by">
                                <option value="{{ old('referred_by', $user->referred_by) }}" selected>{{ old('referred_name', $ctv->name) }}</option>
                            </select>
                            <input type="hidden" name="referred_name" id="referred_name" value="{{ old('referred_name', $ctv->name) }}">
                        </div>
                    </div>
                </div>
        </div>

        <div class="col-sm-12 d-flex justify-content-center mb-2">
            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận
            </button>
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

            let data_ctv_df = null
            let referred_by = `{{ old('referred_by', $user->referred_by) }}`
            let referred_name = `{{ old('referred_name', $ctv->name) }}`
            if(referred_by && referred_name){
                data_ctv_df = {
                    id:referred_by,
                    name:referred_name,
                    selected:true
                }
            }
            let selectSaleAjax = $('#referred_by');
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
                $('#referred_name').val(sale_name);
            });
        })
    </script>
@endsection

