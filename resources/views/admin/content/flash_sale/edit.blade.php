@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Flash Sale')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.flash-sale.update', ['flash_sale' => $data->id]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="title">Tiêu đề<span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="{{old('title', $data->title)}}">
                        @error('title')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-sm-12 mb-1">
                        <label for="image" class="col-form-label">Ảnh</label>
                        <input class="form-control" type="file" name="image" id="image" accept="image/png, image/gif, image/jpeg"
                               placeholder="Vui lọng chọn file">
                        @error('image')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                        <div class="d-flex overflow-scroll" id="preview_image">
                            <div
                                    style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                <div style="width: 110px;height: 110px;padding:5px">
                                    <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $data->title }}" src="{{ \Illuminate\Support\Facades\Storage::url($data->image  ?? '') }}"/>
                                </div>
                                <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                    <div
                                            style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $data->image }}</div>
                                    <small style="line-height: 20px"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="">Thời gian hiển thị</label>
                        <input
                                type="text"
                                name="time"
                                value="{{ old('time', date('d-m-Y H:i', $data->start_time) . ' to ' .  date('d-m-Y H:i', $data->end_time)) }}"
                                class="form-control flatpickr-range"
                                placeholder="DD-MM-YYYY H:I to DD-MM-YYYY H:I"
                        />
                        @error('time')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1 row">
                        <div class="col-6">
                            <label class="col-form-label" for="title">Khung giờ từ</label>
                            <select name="start_hour" id="" class="form-control">
                                @for($i = 0; $i <= 24; $i++)
                                    <option value="{{ $i }}" @if(old('start_hour', $data->start_hour) == $i) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="col-form-label" for="title">Đến</label>
                            <select name="end_hour" id="" class="form-control">
                                @for($i = 0; $i <= 24; $i++)
                                    <option value="{{ $i }}" @if(old('end_hour', $data->end_hour) == $i) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="product_id">Sản phẩm</label>
                        <div class="mb-1">
                            <select class="form-select" id="product_id" name="product_id">
                                {{--<option ></option>--}}
                            </select>
                        </div>
                        {{--<input type="hidden" name="product_name" id="product_name" value="{{ request('product_name') }}">--}}
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-bordered tbl_product">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center">Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                {{--<th class="text-center">Chiết khấu thành viên</th>--}}
                                <th class="text-center" colspan="2">Chiết khấu</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @if(old('products'))
                                @php($product_name = old('product_name'))
                                @php($discount_value = old('discount_value'))
                                @php($discount_type = old('discount_type'))
                                @php($product_price = old('product_price'))
                                {{--@php($discount_group = old('discount_group'))--}}
                                @foreach(old('products') as $id)
                                    <tr>
                                        <td>
                                            {{ $product_name[$id] ?? '' }}
                                        </td>
                                        <td class="text-danger">{{ !empty($product_price[$id]) ? number_format($product_price[$id], 0, '.', ',') : '' }}</td>
                                        <td>
                                            <input type="text" class="form-control" name="discount_value[{{$id}}]" value="{{ $discount_value[$id] ?? 0 }}">
                                        </td>
                                        <td class="text-center">
                                            <select class="form-control" name="discount_type[{{ $id }}]">
                                                <option value="1" @if($discount_type[$id] == 1) selected @endif>%</option>
                                                <option value="2"  @if($discount_type[$id] == 2) selected @endif>vnd</option>
                                            </select>
                                            <input type="hidden" class="form-control" name="products[{{ $id }}]" value="{{ $id }}">
                                            <input type="hidden" class="form-control" name="product_name[{{ $id }}]" value="{{ $product_name[$id] ?? '' }}">
                                            <input type="hidden" class="form-control" name="product_price[{{ $id }}]" value="{{ $product_price[$id] ?? '' }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light js_remove_product">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($data->products as $product)
                                    <tr>
                                        <td>
                                            {{ $product->product->name ?? '' }}
                                        </td>
                                        <td class="text-danger">{{ !empty($product->product->price_sell) ? number_format($product->product->price_sell, 0, '.', ',') : '' }}</td>
                                        <td>
                                            <input type="text" class="form-control" name="discount_value[{{$product->product_id}}]" value="{{ $product->discount_value ?? 0 }}">
                                        </td>
                                        <td class="text-center">
                                            <select class="form-control" name="discount_type[{{ $product->product_id }}]">
                                                <option value="1" @if($product->discount_type == 1) selected @endif>%</option>
                                                <option value="2"  @if($product->discount_type == 2) selected @endif>vnd</option>
                                            </select>
                                            <input type="hidden" class="form-control" name="products[{{ $product->product_id }}]" value="{{ $product->product_id }}">
                                            <input type="hidden" class="form-control" name="product_name[{{ $product->product_id }}]" value="{{ $product->product->name ?? '' }}">
                                            <input type="hidden" class="form-control" name="product_price[{{ $product->product_id }}]" value="{{ $product->product->price_sell ?? '' }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light js_remove_product">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
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

            $('select').select2({
                minimumResultsForSearch: -1
            });

            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })

            $('.flatpickr-range').flatpickr({
                mode: 'range',
                enableTime: true,
                dateFormat: "d-m-Y H:i",
            });

            
            let selectSaleAjax = $('#product_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                /*data: [
                
                ],*/
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
                    return repo.name ;
                }
            }).on("select2:select", function (e) {
                let id = $(this).find(":selected").val();
                let name = $(this).find(":selected").data("name");
                let price = $(this).find(":selected").data("price");
                console.log(id, name, price)
                $(this).val(null).trigger('change');
                let product = $('input[name="products['+ id +']"').val();
                if(product === undefined){
                    let html = `<tr>
                                <td>`+ name +`</td>
                                <td class="text-danger">`+ price.toLocaleString() +`</td>
                                <td>
                                    <input type="text" class="form-control" name="discount_value[`+ id +`]" value="">
                                </td>
                                <td class="text-center">
                                    <select class="form-control" name="discount_type[`+ id +`]">
                                        <option value="1">%</option>
                                        <option value="2">vnd</option>
                                    </select>
                                    <input type="hidden" class="form-control" name="products[`+ id +`]" value="`+ id +`">
                                    <input type="hidden" class="form-control" name="product_name[`+ id +`]" value="`+ name +`">
                                    <input type="hidden" class="form-control" name="product_price[`+ id +`]" value="`+ price +`">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light js_remove_product">
                                        <i data-feather="trash"></i>
                                    </button>
                                </td>
                            </tr>`
                    $('.tbl_product tbody').append(html)
                    feather.replace();
                }
            });

            $('body').on('click', '.js_remove_product', function (){
                $(this).parents('tr').remove()
            })

        })
    </script>
@endsection

