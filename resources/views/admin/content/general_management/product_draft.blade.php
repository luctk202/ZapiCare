@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Sản phẩm')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
@endsection

@section('content')
    <form method="POST" id="form_create_product" action="{{ route('admin.product.update-product-draft', ['id' => $product->id]) }}"
          enctype="multipart/form-data">
        @csrf
        @php
            $stocks = $product->stocks;
            $stock = $stocks[0] ?? [];
            $ids = collect($product->attributes)->pluck('id')->toArray();
            $stock_drafts = $product_draft->stock_drafts;
            $stock_draft = $stock_drafts[0] ?? [];
            $id_drafts = collect($product_draft->attributes)->pluck('id')->toArray();
        @endphp
        <div class="row">
            <div class="col-md-5">
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thông tin yêu cầu sửa
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px" >Tên sản phẩm: </p>
                                <p class="invoice-total-amount">{{$product_draft->name }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Danh mục: </p>
                                <p class="invoice-total-amount">{{$product_draft->category ? $product_draft->category->name : '' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Đơn vị tính: </p>
                                <p class="invoice-total-amount">{{$product_draft->unit }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Cân nặng (Kg): </p>
                                <p class="invoice-total-amount">{{$product_draft->weight/1000 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều dài (Cm): </p>
                                <p class="invoice-total-amount">{{$product_draft->length/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều rộng (Cm): </p>
                                <p class="invoice-total-amount">{{$product_draft->width/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều cao (Cm): </p>
                                <p class="invoice-total-amount">{{$product_draft->height/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">SKU: </p>
                                <p class="invoice-total-amount">{{$product_draft->sku }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Barcode: </p>
                                <p class="invoice-total-amount">{{$product_draft->barcode }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1">
                                <label class="form-label" for="select2-multiple">Danh sách thuộc tính</label>
                                <select class="select2 form-select" id="attributes" name="attributes[]" disabled multiple>
                                    @if(!empty($attributes))
                                        @foreach($attributes as $attribute)
                                            <option value="{{ $attribute->id  }}"
                                                    @if(in_array($attribute->id, old('attributes', $id_drafts))) selected @endif>{{ $attribute->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id="attribute_values_wrap">
                                @if(old('attributes', $id_drafts))
                                    @foreach($attributes as $attribute)
                                        @if(in_array($attribute->id, old('attributes', $id_drafts)))
                                            @php
                                                $dt = [];
                                                if($product_draft->attributes){
                                                    foreach ($product_draft->attributes as $item) {
                                                        if ($item['id'] == $attribute->id) {
                                                            $dt = $item['value'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                if(old('attribute_values_' . $attribute->id)){
                                                    $temp = json_decode(old('attribute_values_' . $attribute->id), true);
                                                    foreach ($temp as $value){
                                                    $dt[] = $value['value'];
                                                    }
                                                }
                                            @endphp
                                            <div class="input-group mt-1">
                                                <span class="input-group-text">{{ $attribute->name }}</span>
                                                <input type="text" class="form-control input-group-sm js_attribute_value"
                                                       placeholder="" disabled id="attribute_values_{{ $attribute->id }}"
                                                       name="attribute_values_{{ $attribute->id }}"
                                                       value="{{ implode('; ', $dt) }}"/>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <label class="col-form-label" for="unit">Bộ lọc sản phẩm</label>
                            <select class="select2 form-select" id="filters" name="filters[]" disabled multiple>
                                @if(!empty($filters))
                                    @foreach($filters as $filter)
                                        <optgroup label="{{ $filter->name }}">
                                            @if($filter->attributes)
                                                @foreach($filter->attributes as $attribute)
                                                    <option value="{{ $attribute->id }}"
                                                            @if(in_array($attribute->id, old('filters',($product_draft->filter_attributes) ? $product_draft->filter_attributes->pluck('filter_attribute_id')->toArray() : []))) selected @endif>{{ $attribute->name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="">
                            <label for="avatar" class="col-form-label">Ảnh đại diện <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex overflow-scroll" id="preview_avatar">
                                <div
                                    style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                    <div style="width: 110px;height: 110px;padding:5px">
                                        @if($product_draft->avatar)
                                            <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $product_draft->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($product_draft->avatar) }}"/>
                                        @endif
                                    </div>
                                    <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                        <div
                                            style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $product_draft->avatar }}</div>
                                        <small style="line-height: 20px"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <label for="images" class="col-form-label">Ảnh sản phẩm</label>
                            <div class="d-flex overflow-scroll" id="preview_images">
                                @if($product_draft->images)
                                    @foreach($product_draft->images as $image)
                                        <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px">
                                                <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $product_draft->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"/>
                                            </div>
                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $image }}</div>
                                                <small style="line-height: 20px"></small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="product_num_wrap" @if(old('product_attribute_value') || (old('_token') === null && !empty($product_draft->attributes))) style="display: none" @endif>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá vốn tổng kho: </p>
                                    <p class="invoice-total-amount">{{$stock_draft->price_cost }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá thị trường: </p>
                                    <p class="invoice-total-amount">{{$stock_draft->price_website }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá niêm yết: </p>
                                    <p class="invoice-total-amount">{{$stock_draft->price_sell }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="product_attr_num_wrap mt-2">
                                @if(old('product_attribute_value', $product_draft->attributes))
                                    @if($errors->has('product_attribute_price_sell.*') || $errors->has('product_attribute_price_cost.*'))
                                        <div class="form-text text-danger text_error_attribute">Vui lòng nhập đủ thông tin giá</div>
                                    @endif
                                    @if(old('product_attribute_value'))
                                        @php($product_attribute_value = old('product_attribute_value'))
                                        @php($product_attribute_price_cost = old('product_attribute_price_cost'))
                                        @php($product_attribute_price_sell = old('product_attribute_price_sell'))
                                        @php($product_attribute_price_website = old('product_attribute_price_website'))
                                        <table class="table table-bordered">
                                            <tr>
                                                <th></th>
                                                <th>Giá vốn tổng kho<span class="text-danger">*</span></th>
                                                <th>Giá thị trường</th>
                                                <th>Giá niêm yết<span class="text-danger">*</span></th>
                                            </tr>
                                            @foreach($product_attribute_value as $value)
                                                <tr>
                                                    <td>{{ $value }}<input type="hidden" class="form-control" name="product_attribute_value[]" value="{{ $value }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_cost[{{ $value }}]" value="{{ $product_attribute_price_cost[$value] ?? '' }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_website[{{ $value }}]" value="{{ $product_attribute_price_website[$value] ?? '' }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_sell[{{ $value }}]" value="{{ $product_attribute_price_sell[$value] ?? '' }}"/></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                    @if(old('_token') === null && $product_draft->attributes)
                                        <table class="table table-bordered">
                                            <tr>
                                                <th></th>
                                                <th>Giá vốn tổng kho<span class="text-danger">*</span></th>
                                                <th>Giá thị trường</th>
                                                <th>Giá niêm yết<span class="text-danger">*</span></th>
                                            </tr>
                                            @foreach($stock_drafts as $st)
                                                <tr>
                                                    <td>{{ $st->attributes_name }}<input type="hidden" class="form-control" name="product_attribute_value[]" value="{{ $st->attributes_name }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_cost[{{ $st->attributes_name }}]" value="{{ $st->price_cost }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_website[{{ $st->attributes_name }}]" value="{{ $st->price_website }}"/></td>
                                                    <td><input type="text" disabled class="form-control" name="product_attribute_price_sell[{{ $st->attributes_name }}]" value="{{ $st->price_sell }}"/></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <label for="avatar" class="col-form-label">Tài liệu kèm theo</label>
                            @error('files')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_files">
                                @if($product_draft->files)
                                    @foreach($product_draft->files as $file)
                                        <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div>
                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $file }}</div>
                                                <small style="line-height: 20px"></small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                            <div class="">
                                <p class="invoice-total-title" style="padding-right: 10px">Mô tả sản phẩm: </p>
                                <p class="invoice-total-amount">{!! $product_draft->description !!}</p>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thông tin hiển tại
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px" >Tên sản phẩm: </p>
                                <p class="invoice-total-amount">{{$product->name }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Danh mục: </p>
                                <p class="invoice-total-amount">{{$product->category ? $product->category->name : '' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Đơn vị tính: </p>
                                <p class="invoice-total-amount">{{$product->unit }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Cân nặng (Kg): </p>
                                <p class="invoice-total-amount">{{$product->weight/1000 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều dài (Cm): </p>
                                <p class="invoice-total-amount">{{$product->length/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều rộng (Cm): </p>
                                <p class="invoice-total-amount">{{$product->width/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Chiều cao (Cm): </p>
                                <p class="invoice-total-amount">{{$product->height/10 }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">SKU: </p>
                                <p class="invoice-total-amount">{{$product->sku }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <p class="invoice-total-title" style="padding-right: 10px">Barcode: </p>
                                <p class="invoice-total-amount">{{$product->barcode }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1">
                                <label class="form-label" for="select2-multiple">Danh sách thuộc tính</label>
                                <select class="select2 form-select" id="attributes_" disabled multiple>
                                    @if(!empty($attributes))
                                        @foreach($attributes as $attribute)
                                            <option value="{{ $attribute->id  }}"
                                                    @if(in_array($attribute->id, old('attributes', $ids))) selected @endif>{{ $attribute->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div id="attribute_values_wrap">
                                @if(old('attributes', $ids))
                                    @foreach($attributes as $attribute)
                                        @if(in_array($attribute->id, old('attributes', $ids)))
                                            <?php
                                                $dt = [];
                                                if($product->attributes){
                                                    foreach ($product->attributes as $item) {
                                                        if ($item['id'] == $attribute->id) {
                                                            $dt = $item['value'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                if(old('attribute_values_' . $attribute->id)){
                                                    $temp = json_decode(old('attribute_values_' . $attribute->id), true);
                                                    foreach ($temp as $value){
                                                        $dt[] = $value['value'];
                                                    }
                                                }
                                            ?>
                                                <div class="input-group mt-1">
                                                    <span class="input-group-text">{{ $attribute->name }}</span>
                                                    <input type="text" class="form-control input-group-sm js_attribute_value"
                                                           placeholder="" disabled id="attribute_values_{{ $attribute->id }}"
                                                           value="{{ implode('; ', $dt) }}"/>
                                                </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>


                        </div>
                        <div class="">
                            <label class="col-form-label" for="unit">Bộ lọc sản phẩm</label>
                            <select class="select2 form-select" id="filters_" disabled multiple>
                                @if(!empty($filters))
                                    @foreach($filters as $filter)
                                        <optgroup label="{{ $filter->name }}">
                                            @if($filter->attributes)
                                                @foreach($filter->attributes as $attribute)
                                                    <option value="{{ $attribute->id }}"
                                                            @if(in_array($attribute->id, old('filters',($product->filter_attributes) ? $product->filter_attributes->pluck('filter_attribute_id')->toArray() : []))) selected @endif>{{ $attribute->name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="">
                            <label for="avatar" class="col-form-label">Ảnh đại diện <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex overflow-scroll" id="preview_avatar">
                                <div
                                    style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                    <div style="width: 110px;height: 110px;padding:5px">
                                        @if($product->avatar)
                                            <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $product->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($product->avatar) }}"/>
                                        @endif
                                    </div>
                                    <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                        <div
                                            style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $product->avatar }}</div>
                                        <small style="line-height: 20px"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <label for="images" class="col-form-label">Ảnh sản phẩm</label>
                            <div class="d-flex overflow-scroll" id="preview_images">
                                @if($product->images)
                                    @foreach($product->images as $image)
                                        <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px">
                                                <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $product->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"/>
                                            </div>
                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $image }}</div>
                                                <small style="line-height: 20px"></small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="product_num_wrap" @if(old('product_attribute_value') || (old('_token') === null && !empty($product->attributes))) style="display: none" @endif>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá vốn tổng kho: </p>
                                    <p class="invoice-total-amount">{{$stock->price_cost }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá thị trường: </p>
                                    <p class="invoice-total-amount">{{$stock->price_website }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex">
                                    <p class="invoice-total-title" style="padding-right: 10px">Giá niêm yết: </p>
                                    <p class="invoice-total-amount">{{$stock->price_sell }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="product_attr_num_wrap mt-2">
                                @if(old('product_attribute_value', $product->attributes))
                                    @if($errors->has('product_attribute_price_sell.*') || $errors->has('product_attribute_price_cost.*'))
                                        <div class="form-text text-danger text_error_attribute">Vui lòng nhập đủ thông tin giá</div>
                                    @endif
                                    @if(old('product_attribute_value'))
                                        @php($product_attribute_value = old('product_attribute_value'))
                                        @php($product_attribute_price_cost = old('product_attribute_price_cost'))
                                        @php($product_attribute_price_sell = old('product_attribute_price_sell'))
                                        @php($product_attribute_price_website = old('product_attribute_price_website'))
                                        <table class="table table-bordered">
                                            <tr>
                                                <th></th>
                                                <th>Giá vốn tổng kho<span class="text-danger">*</span></th>
                                                <th>Giá thị trường</th>
                                                <th>Giá niêm yết<span class="text-danger">*</span></th>
                                            </tr>
                                            @foreach($product_attribute_value as $value)
                                                <tr>
                                                    <td>{{ $value }}<input type="hidden" class="form-control" value="{{ $value }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $product_attribute_price_cost[$value] ?? '' }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $product_attribute_price_website[$value] ?? '' }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $product_attribute_price_sell[$value] ?? '' }}"/></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                    @if(old('_token') === null && $product->attributes)
                                        <table class="table table-bordered">
                                            <tr>
                                                <th></th>
                                                <th>Giá vốn tổng kho<span class="text-danger">*</span></th>
                                                <th>Giá thị trường</th>
                                                <th>Giá niêm yết<span class="text-danger">*</span></th>
                                            </tr>
                                            @foreach($stocks as $st)
                                                <tr>
                                                    <td>{{ $st->attributes_name }}<input type="hidden" class="form-control" value="{{ $st->attributes_name }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $st->price_cost }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $st->price_website }}"/></td>
                                                    <td><input type="text" disabled class="form-control" value="{{ $st->price_sell }}"/></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <label for="avatar" class="col-form-label">Tài liệu kèm theo</label>
                            @error('files')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_files">
                                @if($product->files)
                                    @foreach($product->files as $file)
                                        <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div>
                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $file }}</div>
                                                <small style="line-height: 20px"></small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                            <div class="">
                                <p class="invoice-total-title" style="padding-right: 10px">Mô tả sản phẩm: </p>
                                <p class="invoice-total-amount">{!! $product->description !!}</p>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thuế & GTGT
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">Thuế</label>
                            <div class="row d-flex">
                                <div class="col-8">
                                    <input type="text" class="form-control" name="tax_value" disabled value="{{ old('tax_value', $product->tax_value) }}"/>
                                </div>
                                <div class="col-4">
                                    <select class="form-control select2" id="" disabled name="tax_type">
                                        @foreach($vat_type as $k => $v)
                                            <option value="{{ $k }}" @if($k == old('tax_type', $product->tax_type)) selected @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">VAT</label>
                            <div class="row d-flex">
                                <div class="col-8">
                                    <input type="text" class="form-control" name="vat_value" disabled value="{{ old('vat_value', $product->vat_value) }}"/>
                                </div>
                                <div class="col-4">
                                    <select class="form-control select2" id="" disabled name="vat_type">
                                        @foreach($vat_type as $k => $v)
                                            <option value="{{ $k }}" @if($k == old('vat_type', $product->vat_type)) selected @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thuế & GTGT chỉnh sửa
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">Thuế</label>
                            <div class="row d-flex">
                                <div class="col-8">
                                    <input type="text" class="form-control" disabled value="{{ old('tax_value', $product_draft->tax_value) }}"/>
                                </div>
                                <div class="col-4">
                                    <select class="form-control select2" id="" disabled>
                                        @foreach($vat_type as $k => $v)
                                            <option value="{{ $k }}" @if($k == old('tax_type', $product_draft->tax_type)) selected @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">VAT</label>
                            <div class="row d-flex">
                                <div class="col-8">
                                    <input type="text" class="form-control" disabled value="{{ old('vat_value', $product_draft->vat_value) }}"/>
                                </div>
                                <div class="col-4">
                                    <select class="form-control select2" id="" disabled >
                                        @foreach($vat_type as $k => $v)
                                            <option value="{{ $k }}" @if($k == old('vat_type', $product_draft->vat_type)) selected @endif>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 d-flex justify-content-center">
            <button type="submit" name="draft" value="2" class="btn btn-danger me-1 waves-effect waves-float waves-light">Hủy yêu cầu sửa
            </button>
            <button type="submit" name="draft" value="3" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận yêu cầu sửa
            </button>
        </div>
    </form>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/15bnbk3l03aihwl2yzmxwk6lk00c666drmpw0pr7w4rww8l8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name="avatar"]').on('change', function () {
                ProSell.readURL(this, 'avatar')
            })
            $('input[name="images[]"]').on('change', function () {
                ProSell.readURL(this, 'images')
            })
            $('input[name="files[]"]').on('change', function () {
                ProSell.readURL(this, 'files')
            })
            $(document).on('change', 'input[name^="product_attribute_image"]', function () {
                let key = $(this).data('key')
                ProSell.readURL(this, 'attribute_image_' + key)
            })


            $('.select2').select2({
                minimumResultsForSearch: -1
            });

            $('.select2s').select2({
                //minimumResultsForSearch: -1
            });

            tinymce.init({
                selector: '#description',
                height: 500,
                plugins: [
                    'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                    'table emoticons template paste help codesample'
                ],

                image_title: true,
                automatic_uploads: true,
                images_upload_url: '/admin/upload',
                file_picker_types: 'image',
                relative_urls: false, // Sử dụng liên kết tuyệt đối
                remove_script_host: false, // Giữ nguyên host trong đường dẫn
                file_picker_callback: function (cv, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function () {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        render.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {title: file.name});
                        };
                    };
                    input.click();
                },
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullpage | ' +
                    'forecolor backcolor emoticons | help | codesample',
                menu: {
                    favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
                },
                menubar: 'favs file edit view insert format tools table help'
            });

            $('.btn-add-new').on('click', function () {
                let html = '<div class="mb-1">' +
                    '<div class="row wholesale-row">' +
                    '<div class="col-sm-10">' +
                    '<label class="col-form-label" for="min_number[]">Số lượng mua tối thiểu</label>' +
                    '<input type="number" min="1" step="1" class="form-control" name="min_number[]" value="1"/>' +
                    '</div>' +
                    '<div class="col-sm-2">' +
                    '<div><label class="col-form-label" for="">&nbsp;</label></div>' +
                    '<button type="button" class="btn btn-outline-danger btn-remove-wholesale">Xóa</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $('#wholesale_wrap').append(html)
            });

            $(document).on('click', '.btn-remove-wholesale', function () {
                $(this).parents('.wholesale-row').remove();
            })

            $('#attributes').on('change', function () {
                /*$('#attribute_values_wrap').html('');*/
                $("#attributes > option").each(function () {
                    let value = this.value
                    if (this.selected) {
                        if ($('#attribute_values_' + value).length <= 0) {
                            let html = ('<div class="input-group mt-1"><span class="input-group-text">' + this.text + '</span><input type="text" class="form-control input-group-sm js_attribute_value" placeholder="" id="attribute_values_' + value + '" name="attribute_values_' + value + '" /></div>')
                            $('#attribute_values_wrap').append(html)
                            new Tagify(document.querySelector("#attribute_values_" + value), {
                                delimiters: "; "
                            });
                        }
                    } else {
                        if ($('#attribute_values_' + value).length) {
                            $('#attribute_values_' + value).parent().remove()
                        }
                    }
                });
                /*$('input[name="attribute_values"]').tagify();*/
            })
            $('#attributes_').on('change', function () {
                /*$('#attribute_values_wrap').html('');*/
                $("#attributes_ > option").each(function () {
                    let value = this.value
                    if (this.selected) {
                        if ($('#attribute_values_' + value).length <= 0) {
                            let html = ('<div class="input-group mt-1"><span class="input-group-text">' + this.text + '</span><input type="text" class="form-control input-group-sm js_attribute_value" placeholder="" id="attribute_values_' + value + '" name="attribute_values_' + value + '" /></div>')
                            $('#attribute_values_wrap').append(html)
                            new Tagify(document.querySelector("#attribute_values_" + value), {
                                delimiters: "; "
                            });
                        }
                    } else {
                        if ($('#attribute_values_' + value).length) {
                            $('#attribute_values_' + value).parent().remove()
                        }
                    }
                });
                /*$('input[name="attribute_values"]').tagify();*/
            })
            $("#attributes_ > option").each(function () {
                let value = this.value
                if (this.selected) {
                    new Tagify(document.querySelector("#attribute_values_" + value), {
                        delimiters: "; "
                    });
                }
            });

            $("#attributes > option").each(function () {
                let value = this.value
                if (this.selected) {
                    new Tagify(document.querySelector("#attribute_values_" + value), {
                        delimiters: "; "
                    });
                }
            });

            $(document).on('change', '.js_attribute_value, #attributes', function () {
                let attribute = $('#attributes').val()
                let data = []
                let price_cost_old = `@json(old('product_attribute_price_cost'))`
                price_cost_old = JSON.parse(price_cost_old)
                let price_website_old = `@json(old('product_attribute_price_website'))`
                price_website_old = JSON.parse(price_website_old)
                let price_sell_old = `@json(old('product_attribute_price_sell'))`
                price_sell_old = JSON.parse(price_sell_old)
                attribute.forEach(function (item) {
                    let temp = $('#attribute_values_' + item).val();
                    data.push(temp);
                })
                var parsedData = data.map(function (item) {
                    try {
                        return JSON.parse(item);
                    } catch (e) {
                        return [];
                    }
                });
                console.log('parsedData2', parsedData)
                parsedData = parsedData.filter(function (item) {
                    return item.length > 0;
                });
                console.log('parsedData', parsedData)
                var result = [];
                if (parsedData.length > 0) {
                    function generateCombinations(arr, index, current) {
                        if (index === arr.length) {
                            result.push(current);
                            return;
                        }
                        for (var i = 0; i < arr[index].length; i++) {
                            var newArray = current.slice();
                            newArray.push(arr[index][i].value);
                            generateCombinations(arr, index + 1, newArray);
                        }
                    }

                    generateCombinations(parsedData, 0, []);
                }
                console.log(result)
                if (result.length > 0) {
                    let html = ''//'<div class="form-text text-danger text_error_attribute">' + text_error + '</div>'
                    html += '<table class="table table-bordered">'
                    html += '<tr><th></th><th>Giá vốn</th><th>Giá thị trường</th><th>Giá niêm yết<span class="text-danger">*</span></th></tr>'
                    /*<th>Ảnh <span class="text-danger">*</span></th>*/
                    result.forEach(function (item) {
                        item.sort(function (a, b) {
                            return a.localeCompare(b);
                        });
                        var mergedString = item.join('-');
                        html += '<tr><td>' + mergedString + '<input type="hidden" class="form-control" name="product_attribute_value[]" value="' + mergedString + '"/></td><td><input type="text" class="form-control" name="product_attribute_price_cost[' + mergedString + ']" value="' + ((price_cost_old != null) ? price_cost_old[mergedString] : '') + '"/></td><td><input type="text" class="form-control" name="product_attribute_price_website[' + mergedString + ']" value="' + ((price_website_old != null) ? price_website_old[mergedString] : '') + '"/></td><td><input type="text" class="form-control" name="product_attribute_price_sell[' + mergedString + ']" value="' + ((price_sell_old != null) ? price_sell_old[mergedString] : '') + '"/></td></tr>'
                        /*<td><input class="form-control" type="file" name="product_attribute_image[' + item + ']" data-key="' + item + '" accept="image/*"><div class="mt-1 d-flex overflow-scroll" id="preview_attribute_image_' + item + '"> </div></td>*/
                    })
                    html += '</table>'
                    $('.product_num_wrap').hide()
                    $('.product_attr_num_wrap').html(html)
                } else {
                    $('.product_attr_num_wrap').html('')
                    $('.product_num_wrap').show()
                }
            })
        })
    </script>
@endsection


