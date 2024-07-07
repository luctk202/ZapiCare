@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Mã giảm giá')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.coupon.update',['coupon' => $data->id]) }}"
          enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="code">Mã giảm giá<span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{old('code', $data->code)}}">
                        @error('code')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
{{--                    <div class="col-sm-12 mb-1">--}}
{{--                        <label class="form-label" for="source_id">Nguồn</label>--}}
{{--                        <div class="">--}}
{{--                            <select class="form-select" id="source_id" name="source_id">--}}
{{--                                <option value="{{ old('source_id', $data->source_id) }}" selected>{{ old('source_name', $data->source->name ?? '') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <input type="hidden" name="source_name" id="source_name" value="{{ old('source_name', $data->source->name ?? '') }}">--}}
{{--                    </div>--}}
                    <div class="col-sm-12 mb-1">
                        <div class="">
                            <label class="form-label" for="select2-multiple">Giá trị giảm</label>
                            <div class="row d-flex">
                                <div class="col-8">
                                    <input type="text" class="form-control" name="discount_value" value="{{ old('discount_value', $data->discount_value) }}"/>
                                </div>
                                <div class="col-4">
                                    <select class="form-control select2" id="" name="discount_type">
                                        @foreach($aryDiscountType as $k => $v)
                                            <option value="{{ $k }}" @if($k == old('discount_type', $data->discount_type)) selected @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @error('discount_value')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="discount_max_value">Giá trị giảm tối đa</label>
                        <input type="text" class="form-control" name="discount_max_value" value="{{old('discount_max_value', $data->discount_max_value)}}">
                        @error('discount_max_value')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="num">Số lượng mã giảm giá</label>
                        <input type="text" class="form-control" name="num" value="{{old('num', $data->num)}}">
                        @error('num')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="max_per_person">SL áp dụng/Khách hàng</label>
                        <input type="text" class="form-control" name="max_per_person" value="{{old('max_per_person', $data->max_per_person)}}">
                        @error('max_per_person')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="min_total_order">Giá trị đơn hàng tối thiểu</label>
                        <input type="text" class="form-control" name="min_total_order" value="{{old('min_total_order', $data->min_total_order)}}">
                        @error('min_total_order')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="">Thời gian áp dụng</label>
                        <input
                                type="text"
                                name="time"
                                value="{{ old('time', $data->start_time ? date('d-m-Y H:i', $data->start_time) . ' to ' .  date('d-m-Y H:i', $data->end_time) : '') }}"
                                class="form-control flatpickr-range"
                                placeholder="DD-MM-YYYY H:I to DD-MM-YYYY H:I"
                        />
                        @error('time')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="category_id">Áp dụng đồng thời</label>
                        <div class="">
                            <select class="form-select select2" name="concurrency" id="concurrency">
                                <option value="0" @if(0 == old('concurrency', $data->concurrency)) selected @endif>Không</option>
                                <option value="1" @if(1 == old('concurrency', $data->concurrency)) selected @endif>Có</option>
                            </select>
                        </div>
                        @error('category_id')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
{{--                    <div class="col-sm-12 mb-1">--}}
{{--                        <label class="form-label" for="shop_id">Cửa hàng áp dụng</label>--}}
{{--                        <div class="">--}}
{{--                            <select class="form-select" id="shop_id" name="shop_id">--}}
{{--                                <option value="{{ old('shop_id', $data->shop_id ?? 0) }}" selected>{{ old('shop_name', $data->shop->name ?? '') }}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <input type="hidden" name="shop_name" id="shop_name" value="{{ old('shop_name', $data->shop->name ?? '') }}">--}}
{{--                    </div>--}}
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="category_id">Danh mục áp dụng</label>
                        <div class="">
                            <select class="form-select select2s" name="category_id" id="category_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == old('category_id', $data->category_id)) selected @endif>{{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="product_id">Sản phẩm áp dụng</label>
                        <div class="">
                            <select class="form-select" id="product_id" name="product_id">
                                <option value="{{ old('product_id', $data->product_id) }}" selected>{{ old('product_name', $data->product->name ?? '') }}</option>
                            </select>
                        </div>
                        <input type="hidden" name="product_name" id="product_name" value="{{ old('product_name', $data->product->name ?? '') }}">
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="description">Mô tả</label>
                        <textarea class="form-control" name="description">{{ old('description', $data->description) }}</textarea>
                        @error('description')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-success me-1 waves-effect waves-float waves-light">Xác nhận</button>
        </div>
    </form>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js'))}}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

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

            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })

            $('.flatpickr-range').flatpickr({
                mode: 'range',
                enableTime: true,
                dateFormat: "d-m-Y H:i",
            });


            //discount_groups = JSON.parse(discount_groups)
            let data_product_df = null
            let product_id = `{{ old('product_id', $data->product_id) }}`
            let product_name = `{{ old('product_name', $data->product->name ?? '') }}`
            if(product_id && product_name){
                data_product_df = {
                    id:product_id,
                    name:product_name,
                    selected:true
                }
            }
            let selectProductAjax = $('#product_id');
            selectProductAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectProductAjax.parent(),
                width: '100%',
                data:[
                    data_product_df
                ],
                ajax: {
                    url: '/admin/product/search',
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
                placeholder: 'ID/Tên/Barcode',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup = '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' + repo.name + '</span></span> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    $(repo.element).attr('data-price', repo.price_sell);
                    return repo.name;
                }
            }).on("select2:select", function (e) {
                let name = $(this).find(":selected").data("name");
                $('#product_name').val(name);
            });

            let data_shop_df = null
            let shop_id = `{{ old('shop_id', $data->shop_id) }}`
            let shop_name = `{{ old('shop_name', $data->shop->name ?? '') }}`
            if(shop_id && shop_name){
                data_shop_df = {
                    id:shop_id,
                    name:shop_name,
                    selected:true
                }
            }
            let selectShopAjax = $('#shop_id');
            selectShopAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectShopAjax.parent(),
                width: '100%',
                data: [
                    data_shop_df
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
                placeholder: 'Tên/Số điện thoại',
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
                    $(repo.element).attr('data-price', repo.price_sell);
                    return repo.name;
                }
            }).on("select2:select", function (e) {
                let name = $(this).find(":selected").data("name");
                $('#shop_name').val(name);
            });

            let data_source_df = null
            let source_id = `{{ old('source_id', $data->source_id) }}`
            let source_name = `{{ old('source_name', $data->source->name ?? '') }}`
            if(source_id && source_name){
                data_source_df = {
                    id:source_id,
                    name:source_name,
                    selected:true
                }
            }
            let selectSourceAjax = $('#source_id');
            selectSourceAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSourceAjax.parent(),
                width: '100%',
                data: [
                    data_source_df
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
                placeholder: 'Tên/Số điện thoại',
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
                    $(repo.element).attr('data-price', repo.price_sell);
                    return repo.name;
                }
            }).on("select2:select", function (e) {
                let name = $(this).find(":selected").data("name");
                $('#source_name').val(name);
            });

            $('body').on('click', '.js_remove_product', function () {
                $(this).parents('tr').remove()
            })

        })
    </script>
@endsection

