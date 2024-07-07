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
    <form method="POST" id="form_create_product" action="{{ route('admin.product.update', ['product' => $product->id]) }}"
          enctype="multipart/form-data">
        @csrf
        @php
            $stocks = $product->stocks;
            $stock = $stocks[0] ?? [];
            $ids = collect($product->attributes)->pluck('id')->toArray()
        @endphp
        @method('PATCH')
        <div class="row">
            <div class="col-md-12">
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thông tin cơ bản
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label class="col-form-label" for="name">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" name="name"
                                   placeholder="" value="{{ old('name', $product->name) }}">
                            @error('name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="">
                            <label class="col-form-label" for="category_id">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select select2s" name="category_id" id="category_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == old('category_id', $product->category_id)) selected @endif>{{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{--<div class="">
                            <label class="col-form-label" for="brand_id">Thương hiệu</label>
                            <select class="form-select select2s" name="brand_id" id="brand_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($brands as $value)
                                    <option value="{{ $value->id }}" @if($value->id == old('brand_id', $product->brand_id)) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>--}}
                        <div class="">
                            <label class="col-form-label" for="unit">Đơn vị tính</label>
                            <input type="text" id="unit" class="form-control" name="unit"
                                   placeholder="Kg, chiếc ...." value="{{ old('unit', $product->unit) }}">
                            @error('unit')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-lg-3 col-md-6">--}}
{{--                                <label class="col-form-label" for="weight">Cân nặng (Kg)</label>--}}
{{--                                <input type="number" min="0" step="0.1" id="weight" class="form-control" name="weight"--}}
{{--                                       value="{{ old('weight', $product->weight/1000) }}">--}}
{{--                                @error('weight')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-6 ">--}}
{{--                                <label class="col-form-label" for="length">Chiều dài (Cm)</label>--}}
{{--                                <input type="number" min="0" step="1" id="length" class="form-control" name="length"--}}
{{--                                       value="{{ old('length', $product->length/10) }}">--}}
{{--                                @error('length')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-6 ">--}}
{{--                                <label class="col-form-label" for="width">Chiều rộng (Cm)</label>--}}
{{--                                <input type="number" min="0" step="1" id="width" class="form-control" name="width"--}}
{{--                                       value="{{ old('width', $product->width/10) }}">--}}
{{--                                @error('width')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-3 col-md-6 ">--}}
{{--                                <label class="col-form-label" for="height">Chiều cao (Cm)</label>--}}
{{--                                <input type="number" min="0" step="1" id="height" class="form-control" name="height"--}}
{{--                                       value="{{ old('height', $product->height/10) }}">--}}
{{--                                @error('height')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--<div class="">
                            <label class="col-form-label" for="producer_id">Nhà cung cấp<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="producer_id" name="producer_id">
                                <option value="{{ old('producer_id') }}" selected>{{ old('producer_name') }}</option>
                            </select>
                            <input type="hidden" name="producer_name" id="producer_name"
                                   value="{{ old('producer_name') }}">
                            @error('producer_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6 ">--}}
{{--                                <label class="col-form-label" for="sku">SKU</label>--}}
{{--                                <input type="text" id="sku" class="form-control" name="sku"--}}
{{--                                       value="{{ old('sku', $product->sku) }}">--}}
{{--                                @error('sku')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6 ">--}}
{{--                                <label class="col-form-label" for="unit">Barcode</label>--}}
{{--                                <input type="text" id="barcode" class="form-control" name="barcode"--}}
{{--                                       value="{{ old('barcode', $product->barcode) }}">--}}
{{--                                @error('barcode')--}}
{{--                                <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="">--}}
{{--                            <label class="col-form-label" for="unit">Bộ lọc sản phẩm</label>--}}
{{--                            <select class="select2 form-select" id="filters" name="filters[]" multiple>--}}
{{--                                @if(!empty($filters))--}}
{{--                                    @foreach($filters as $filter)--}}
{{--                                        <optgroup label="{{ $filter->name }}">--}}
{{--                                            @if($filter->attributes)--}}
{{--                                                @foreach($filter->attributes as $attribute)--}}
{{--                                                    <option value="{{ $attribute->id }}"--}}
{{--                                                            @if(in_array($attribute->id, old('filters',($product->filter_attributes) ? $product->filter_attributes->pluck('filter_attribute_id')->toArray() : []))) selected @endif>{{ $attribute->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                        </optgroup>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}
                        {{--<div class="">
                            <label class="col-form-label" for="province_ids">Tỉnh thành đăng bán</label>
                            <select class="select2s form-select" id="province_ids" name="province_ids[]" multiple>
                                <option value="0"
                                        @if(in_array(0, old('province_ids',[]))) selected @endif>Toàn quốc</option>
                                @if(!empty($province))
                                    @foreach($province as $value)
                                        <option value="{{ $value->id }}"
                                                @if(in_array($value->id, old('province_ids',($product->province) ? $product->province->pluck('province_id')->toArray() : []))) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>--}}
                        <div class="">
                            <label for="avatar" class="col-form-label">Ảnh đại diện <span
                                        class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="avatar" id="avatar"
                                   placeholder="Vui lọng chọn file" accept="image/*">
                            @error('avatar')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
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
                            <input class="form-control" type="file" name="images[]" id="images"
                                   placeholder="Vui lọng chọn file" multiple>
                            @error('images')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
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

                    </div>
                </div>
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Thuộc tính
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">Danh sách thuộc tính</label>
                            <select class="select2 form-select" id="attributes" name="attributes[]" multiple>
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
                                        @php
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
                                        @endphp
                                        <div class="input-group mt-1">
                                            <span class="input-group-text">{{ $attribute->name }}</span>
                                            <input type="text" class="form-control input-group-sm js_attribute_value"
                                                   placeholder="" id="attribute_values_{{ $attribute->id }}"
                                                   name="attribute_values_{{ $attribute->id }}"
                                                   value="{{ implode('; ', $dt) }}"/>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                {{--<div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Bán buôn
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="wholesale_wrap">
                            @if(old('min_number'))
                                @php($min_number = old('min_number', []))
                                @foreach($min_number as $k => $v)
                                    <div class="mb-1">
                                        <div class="row wholesale-row">
                                            <div class="col-sm-10">
                                                <label class="col-form-label" for="min_number[]">Số lượng mua tối thiểu</label>
                                                <input type="number" min="1" step="1" class="form-control" name="min_number[]" value="{{ $v ?? 1 }}"/>
                                            </div>
                                            --}}{{--<div class="col-md-5">
                                                <label class="col-form-label" for="max_number[]">Số lượng mua tối đa</label>
                                                <input type="number" min="0" step="1" class="form-control" name="max_number[]" value="{{ $max_number[$k] ?? 0 }}"/>
                                            </div>--}}{{--
                                            <div class="col-sm-2">
                                                <div><label class="col-form-label" for="">&nbsp;</label></div>
                                                <button type="button" class="btn btn-outline-danger btn-remove-wholesale">Xóa</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-add-new"><i data-feather="plus" class="me-25"></i><span class="align-middle">Thêm mốc bán buôn</span></button>
                        </div>
                    </div>
                </div>--}}
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Giá sản phẩm
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="product_num_wrap" @if(old('product_attribute_value') || (old('_token') === null && !empty($product->attributes))) style="display: none" @endif>
                            <div class="">
                                <label class="col-form-label" for="name">Giá<span
                                            class="text-danger">*</span></label>
                                <input type="text" id="price_sell" class="form-control" name="price_sell"
                                       placeholder="" value="{{ old('price_sell', $stock->price_sell ?? '') }}">
                                @error('price_sell')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="">
                                <label class="col-form-label" for="name">Số lượng<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="num" class="form-control" name="num"
                                       placeholder="" value="{{ old('num', $stock->num ?? '') }}">
                                @error('num')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="product_attr_num_wrap mt-2">
                            @if(old('product_attribute_value', $product->attributes))
                                @if($errors->has('product_attribute_price_sell.*') || $errors->has('product_attribute_price_cost.*'))
                                    <div class="form-text text-danger text_error_attribute">Vui lòng nhập đủ thông tin giá</div>
                                @endif
                                @if(old('product_attribute_value'))
                                    @php($product_attribute_value = old('product_attribute_value'))
                                    @php($product_attribute_price_sell = old('product_attribute_price_sell'))
                                    @php($product_attribute_num = old('$product_attribute_num'))
                                    <table class="table table-bordered">
                                        <tr>
                                            <th></th>
                                            <th>Giá<span class="text-danger">*</span></th>
                                            <th>Số lượng<span class="text-danger">*</span></th>
                                        </tr>
                                        @foreach($product_attribute_value as $value)
                                            <tr>
                                                <td>{{ $value }}<input type="hidden" class="form-control" name="product_attribute_value[]" value="{{ $value }}"/></td>
                                                <td><input type="text" class="form-control" name="product_attribute_price_sell[{{ $value }}]" value="{{ $product_attribute_price_sell[$value] ?? '' }}"/></td>
                                                <td><input type="text" class="form-control" name="product_attribute_num[{{ $value }}]" value="{{ $product_attribute_num[$value] ?? '' }}"/></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                                @if(old('_token') === null && $product->attributes)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th></th>
                                            <th>Giá<span class="text-danger">*</span></th>
                                            <th>Số lượng<span class="text-danger">*</span></th>
                                        </tr>
                                        @foreach($stocks as $st)
                                            <tr>
                                                <td>{{ $st->attributes_name }}<input type="hidden" class="form-control" name="product_attribute_value[]" value="{{ $st->attributes_name }}"/></td>
                                                <td><input type="text" class="form-control" name="product_attribute_price_sell[{{ $st->attributes_name }}]" value="{{ $st->price_sell }}"/></td>
                                                <td><input type="text" class="form-control" name="product_attribute_num[{{ $st->attributes_name }}]" value="{{ $st->num }}"/></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Mở rộng
                        </div>
                    </div>
                    <div class="card-body">
{{--                        <div class="">--}}
{{--                            <label for="avatar" class="col-form-label">Tài liệu kèm theo</label>--}}
{{--                            <input class="form-control" type="file" name="files[]" id="files"--}}
{{--                                   placeholder="Vui lọng chọn file">--}}
{{--                            @error('files')--}}
{{--                            <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                            @enderror--}}
{{--                            <div class="d-flex overflow-scroll" id="preview_files">--}}
{{--                                @if($product->files)--}}
{{--                                    @foreach($product->files as $file)--}}
{{--                                        <div--}}
{{--                                                style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">--}}
{{--                                            <div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div>--}}
{{--                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">--}}
{{--                                                <div--}}
{{--                                                        style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $file }}</div>--}}
{{--                                                <small style="line-height: 20px"></small>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="">
                            <label class="col-form-label" for="description">Mô tả ngắn</label>
                            <textarea class="form-control" name="short_description" id="short_description">{!! old('short_description', $product->short_description) !!}</textarea>
                            @error('short_description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="">
                            <label class="col-form-label" for="description">Mô tả dài</label>
                            <textarea class="form-control" name="long_description" id="long_description">{!! old('long_description', $product->long_description) !!}</textarea>
                            @error('long_description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="">
                            <label class="col-form-label" for="description">Mô tả sản phẩm</label>
                            <textarea class="form-control" name="description" id="description">{!! old('description', $product->description) !!}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <label for="tag_labels">Tag sản phẩm</label><br>
                        @foreach($tagLabels as $tagLabel)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tag_labels[]" id="tag_label_{{ $tagLabel->id }}" value="{{ $tagLabel->id }}"
                                       @if(in_array($tagLabel->id, $selectedTagLabels)) checked @endif>
                                <label class="form-check-label" for="tag_label_{{ $tagLabel->id }}">{{ $tagLabel->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>





            </div>
{{--            <div class="col-md-4">--}}
{{--                <div class="mb-1 card">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-title">--}}
{{--                            Thuế & GTGT--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="mb-1">--}}
{{--                            <label class="form-label" for="select2-multiple">Thuế</label>--}}
{{--                            <div class="row d-flex">--}}
{{--                                <div class="col-8">--}}
{{--                                    <input type="text" class="form-control" name="tax_value" value="{{ old('tax_value', $product->tax_value) }}"/>--}}
{{--                                </div>--}}
{{--                                <div class="col-4">--}}
{{--                                    <select class="form-control select2" id="" name="tax_type">--}}
{{--                                        @foreach($vat_type as $k => $v)--}}
{{--                                            <option value="{{ $k }}" @if($k == old('tax_type', $product->tax_type)) selected @endif>{{ $v }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="mb-1">--}}
{{--                            <label class="form-label" for="select2-multiple">VAT</label>--}}
{{--                            <div class="row d-flex">--}}
{{--                                <div class="col-8">--}}
{{--                                    <input type="text" class="form-control" name="vat_value" value="{{ old('vat_value', $product->vat_value) }}"/>--}}
{{--                                </div>--}}
{{--                                <div class="col-4">--}}
{{--                                    <select class="form-control select2" id="" name="vat_type">--}}
{{--                                        @foreach($vat_type as $k => $v)--}}
{{--                                            <option value="{{ $k }}" @if($k == old('vat_type', $product->vat_type)) selected @endif>{{ $v }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        <div class="col-sm-12 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận
            </button>
        </div>
    </form>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/byt97a8fkys8hyitumfhzj4med78sgmc6cnj6477lmr095a3/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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
                selector: '#long_description, #description',
                height: 500,
                plugins: 'image',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                automatic_uploads: true,
                images_upload_url: '',
                file_picker_types: 'image',
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function () {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                    };
                    input.click();
                }
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
                let num_old = `@json(old('product_attribute_num'))`
                num_old = JSON.parse(num_old)
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
                    html += '<tr><th></th><th>Giá<span class="text-danger">*</span></th><th>Số lượng<span class="text-danger">*</span></th></tr>'
                    /*<th>Ảnh <span class="text-danger">*</span></th>*/
                    result.forEach(function (item) {
                        item.sort(function (a, b) {
                            return a.localeCompare(b);
                        });
                        var mergedString = item.join('-');
                        html += '<tr><td>' + mergedString + '<input type="hidden" class="form-control" name="product_attribute_value[]" value="' + mergedString + '"/></td><td><input type="text" class="form-control" name="product_attribute_price_sell[' + mergedString + ']" value="' + ((price_sell_old != null) ? price_sell_old[mergedString] : '') + '"/></td><td><input type="text" class="form-control" name="product_attribute_num[' + mergedString + ']" value="' + ((num_old != null) ? num_old[mergedString] : '') + '"/></td></tr>'
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

