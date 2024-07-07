@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thêm thuộc tính')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product-attribute.store') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
{{--                        <div class="mb-1 row">--}}
{{--                            <div class="col-sm-3">--}}
{{--                                <label class="form-label" for="user_id">Shop</label>--}}
{{--                            </div>--}}
{{--                            --}}
{{--                            <div class="col-sm-9">--}}
{{--                                <select class="form-select" id="shop_id" name="shop_id">--}}
{{--                                    <option value="{{ old('shop_id') }}" selected>{{ old('shop_name') }}</option>--}}
{{--                                </select>--}}
{{--                                @error('shop_id')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <input type="hidden" name="shop_name" id="shop_name" value="{{ request('shop_name') }}">--}}
{{--                        </div>--}}
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên thuộc tính</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="name" class="form-control" name="name"
                                           placeholder="" value="{{ old('name') }}">
                                </div>
                                @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 d-flex justify-content-center">
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
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
            href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
            rel="stylesheet">
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            let data_dev_df = null
            let shop_id = `{{ old('shop_id') }}`
            let shop_name = `{{ $shop->name ?? '' }}`
            if(shop_id && shop_name){
                data_dev_df = {
                    id:shop_id,
                    name:shop_name,
                    selected:true
                }
            }
            let selectDevAjax = $('#shop_id');
            selectDevAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectDevAjax.parent(),
                width: '100%',
                data:[
                    data_dev_df
                ],
                ajax: {
                    url: '/admin/shop/search',
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
                placeholder: 'Tên shop',
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
            }).on("select2:clear", function (e) {

            });
        })
    </script>
@endsection

