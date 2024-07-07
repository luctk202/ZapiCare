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
    <form method="POST" id="form_create_product" action="{{ route('admin.product.store') }}"
          enctype="multipart/form-data">
        @csrf
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
                            <input type="text" id="name" class="form-control" name="name" placeholder=""
                                   value="{{ old('name') }}">
                            @error('name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="">
                            <label class="col-form-label" for="category_id">Danh mục <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select2s" name="category_id" id="category_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach ($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if ($value->id == old('category_id')) selected @endif>
                                        {{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="">
                            <label class="col-form-label" for="brand_id">Thương hiệu</label>
                            <select class="form-select select2s" name="brand_id" id="brand_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach ($brands as $value)
                                    <option value="{{ $value->id }}" @if ($value->id == old('brand_id')) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="">
                            <label class="col-form-label" for="unit">Đơn vị tính</label>
                            <input type="text" id="unit" class="form-control" name="unit"
                                   placeholder="Kg, chiếc ...." value="{{ old('unit') }}">
                            @error('unit')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-lg-3 col-md-6">--}}
                        {{--                                <label class="col-form-label" for="weight">Cân nặng (Kg)</label>--}}
                        {{--                                <input type="number" min="0" step="0.1" id="weight" class="form-control"--}}
                        {{--                                    name="weight" value="{{ old('weight') }}">--}}
                        {{--                                @error('weight')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-lg-3 col-md-6 ">--}}
                        {{--                                <label class="col-form-label" for="length">Chiều dài (Cm)</label>--}}
                        {{--                                <input type="number" min="0" step="1" id="length" class="form-control"--}}
                        {{--                                    name="length" value="{{ old('length') }}">--}}
                        {{--                                @error('length')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-lg-3 col-md-6 ">--}}
                        {{--                                <label class="col-form-label" for="width">Chiều rộng (Cm)</label>--}}
                        {{--                                <input type="number" min="0" step="1" id="width" class="form-control"--}}
                        {{--                                    name="width" value="{{ old('width') }}">--}}
                        {{--                                @error('width')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-lg-3 col-md-6 ">--}}
                        {{--                                <label class="col-form-label" for="height">Chiều cao (Cm)</label>--}}
                        {{--                                <input type="number" min="0" step="1" id="height" class="form-control"--}}
                        {{--                                    name="height" value="{{ old('height') }}">--}}
                        {{--                                @error('height')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-6 ">--}}
                        {{--                                <label class="col-form-label" for="sku">SKU</label>--}}
                        {{--                                <input type="text" id="sku" class="form-control" name="sku"--}}
                        {{--                                    value="{{ old('sku') }}">--}}
                        {{--                                @error('sku')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-md-6 ">--}}
                        {{--                                <label class="col-form-label" for="unit">Barcode</label>--}}
                        {{--                                <input type="text" id="barcode" class="form-control" name="barcode"--}}
                        {{--                                    value="{{ old('barcode') }}">--}}
                        {{--                                @error('barcode')--}}
                        {{--                                    <span class="form-text text-danger">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="">
                            <label for="avatar" class="col-form-label">Ảnh đại diện <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="avatar" id="avatar"
                                   placeholder="Vui lọng chọn file" accept="image/*">
                            @error('avatar')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_avatar">

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
                                @if (!empty($attributes))
                                    @foreach ($attributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                                @if (in_array($attribute->id, old('attributes', []))) selected @endif>{{ $attribute->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div id="attribute_values_wrap">
                            @if (old('attributes'))
                                @foreach ($attributes as $attribute)
                                    @if (in_array($attribute->id, old('attributes', [])))
                                        @php
                                            $dt = [];
                                            if (old('attribute_values_' . $attribute->id)) {
                                                $temp = json_decode(old('attribute_values_' . $attribute->id), true);
                                                foreach ($temp as $value) {
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
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Giá sản phẩm
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="product_num_wrap" @if (old('product_attribute_value')) style="display: none" @endif>
                            <div class="">
                                <label class="col-form-label" for="name">Giá <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="price_sell" class="form-control" name="price_sell"
                                       placeholder="" value="{{ old('price_sell') }}">
                                @error('price_sell')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="">
                                <label class="col-form-label" for="num">Số lượng <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="num" class="form-control" name="num" placeholder=""
                                       value="{{ old('num') }}">
                                @error('num')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="product_attr_num_wrap mt-2">
                            @if (old('product_attribute_value'))
                                @if ($errors->has('product_attribute_price_sell.*') || $errors->has('product_attribute_price_cost.*'))
                                    <div class="form-text text-danger text_error_attribute">Vui lòng nhập đủ thông tin
                                        giá
                                    </div>
                                @endif
                                @php($product_attribute_value = old('product_attribute_value'))
                                @php($product_attribute_price_sell = old('product_attribute_price_sell'))
                                @php($product_attribute_num = old('product_attribute_num'))
                                <table class="table table-bordered">
                                    <tr>
                                        <th></th>
                                        <th>Giá<span class="text-danger">*</span></th>
                                        <th>Số lượng<span class="text-danger">*</span></th>
                                    </tr>

                                    @foreach ($product_attribute_value as $value)
                                        <tr>
                                            <td>{{ $value }}<input type="hidden" class="form-control"
                                                                   name="product_attribute_value[]"
                                                                   value="{{ $value }}"/></td>
                                            <td><input type="text" class="form-control"
                                                       name="product_attribute_price_sell[{{ $value }}]"
                                                       value="{{ $product_attribute_price_sell[$value] ?? '' }}"/></td>
                                            <td><input type="text" class="form-control"
                                                       name="product_attribute_num[{{ $value }}]"
                                                       value="{{ $product_attribute_num[$value] ?? '' }}"/></td>
                                        </tr>
                                    @endforeach
                                </table>
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

                            </div>
                        </div>
                        <div class="">
                            <label class="col-form-label" for="short_description">Mô tả ngắn</label>
                            <textarea class="form-control" name="short_description"
                                      id="short_description">{!! old('short_description') !!}</textarea>

                            <label class="col-form-label" for="long_description">Mô tả dài</label>
                            <textarea class="form-control" name="long_description"
                                      id="long_description">{!! old('long_description') !!}</textarea>
                            @error('long_description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <label class="col-form-label" for="description">Mô tả sản phẩm</label>
                            <textarea class="form-control" name="description"
                                      id="description">{!! old('description') !!}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            {{-- <div id="editor" style="min-height: 300px">
                                {!! old('content') !!}
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="mb-1 card">
                    <div class="card-header">
                        <div class="card-title">
                            Hiển thị
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="tag_labels">Tag sản phẩm</label><br>
                            @foreach($tagLabels as $tagLabel)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tag_labels[]" id="tag_label_{{ $tagLabel->id }}" value="{{ $tagLabel->id }}">
                                    <label class="form-check-label" for="tag_label_{{ $tagLabel->id }}">{{ $tagLabel->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{--            <div class="col-md-4"> --}}
            {{--                <div class="mb-1 card"> --}}
            {{--                    <div class="card-header"> --}}
            {{--                        <div class="card-title"> --}}
            {{--                            Thuế & GTGT --}}
            {{--                        </div> --}}
            {{--                    </div> --}}
            {{--                    <div class="card-body"> --}}
            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="select2-multiple">Thuế</label> --}}
            {{--                            <div class="row d-flex"> --}}
            {{--                                <div class="col-8"> --}}
            {{--                                    <input type="text" class="form-control" name="tax_value" value="{{ old('tax_value') }}"/> --}}
            {{--                                </div> --}}
            {{--                                <div class="col-4"> --}}
            {{--                                    <select class="form-control select2" id="" name="tax_type"> --}}
            {{--                                        @foreach ($vat_type as $k => $v) --}}
            {{--                                            <option value="{{ $k }}" @if ($k == old('tax_type')) selected @endif>{{ $v }}</option> --}}
            {{--                                        @endforeach --}}
            {{--                                    </select> --}}
            {{--                                </div> --}}
            {{--                            </div> --}}
            {{--                        </div> --}}
            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="select2-multiple">VAT</label> --}}
            {{--                            <div class="row d-flex"> --}}
            {{--                                <div class="col-8"> --}}
            {{--                                    <input type="text" class="form-control" name="vat_value" value="{{ old('vat_value') }}"/> --}}
            {{--                                </div> --}}
            {{--                                <div class="col-4"> --}}
            {{--                                    <select class="form-control select2" id="" name="vat_type"> --}}
            {{--                                        @foreach ($vat_type as $k => $v) --}}
            {{--                                            <option value="{{ $k }}" @if ($k == old('vat_type')) selected @endif>{{ $v }}</option> --}}
            {{--                                        @endforeach --}}
            {{--                                    </select> --}}
            {{--                                </div> --}}
            {{--                            </div> --}}
            {{--                        </div> --}}
            {{--                    </div> --}}
            {{--                </div> --}}
            {{--            </div> --}}
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
    <!-- Thêm thư viện TinyMCE -->
{{--    <script src="https://cdn.tiny.cloud/1/zcb3va2hvob1k0x7410m4ujxxcuy4y32t4eo4d96va692ocm/tinymce/7/tinymce.min.js"--}}
    <script src="https://cdn.tiny.cloud/1/byt97a8fkys8hyitumfhzj4med78sgmc6cnj6477lmr095a3/tinymce/7/tinymce.min.js"></script>
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

            let data_dev_df = null
            let shop_id = `{{ request('shop_id') }}`
            let shop_name = `{{ $shop->name ?? '' }}`
            if (shop_id && shop_name) {
                data_dev_df = {
                    id: shop_id,
                    name: shop_name,
                    selected: true
                }
            }
            let selectDevAjax = $('#shop_id');
            selectDevAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectDevAjax.parent(),
                width: '100%',
                data: [
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
                placeholder: 'Tên shop',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup =
                        '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' +
                        repo.name + '</span></span><small class="text-muted">' + repo.phone +
                        '</small> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                /*let sale_name = $(this).find(":selected").data("name");
                $('#user_develop_name').val(sale_name);*/
                console.log(e);
                ProSell.loading.show()
                window.location.href = location.protocol + '//' + location.host + location.pathname +
                    '?shop_id=' + e.params.data.id
            }).on("select2:clear", function (e) {
                console.log(e);
                ProSell.loading.show()
                window.location.href = location.protocol + '//' + location.host + location.pathname
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
                            let html = (
                                '<div class="input-group mt-1"><span class="input-group-text">' +
                                this.text +
                                '</span><input type="text" class="form-control input-group-sm js_attribute_value" placeholder="" id="attribute_values_' +
                                value + '" name="attribute_values_' + value + '" /></div>')
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
                    let html =
                        '' //'<div class="form-text text-danger text_error_attribute">' + text_error + '</div>'
                    html += '<table class="table table-bordered">'
                    html +=
                        '<tr><th></th><th>Giá <span class="text-danger">*</span></th><th>Số lượng<span class="text-danger">*</span></th></tr>'
                    /*<th>Ảnh <span class="text-danger">*</span></th>*/
                    result.forEach(function (item) {
                        item.sort(function (a, b) {
                            return a.localeCompare(b);
                        });
                        var mergedString = item.join('-');
                        html += '<tr><td>' + mergedString +
                            '<input type="hidden" class="form-control" name="product_attribute_value[]" value="' +
                            mergedString +
                            '"/></td><td><input type="text" class="form-control" name="product_attribute_price_sell[' +
                            mergedString + ']" value="' + ((price_sell_old != null) ?
                                price_sell_old[mergedString] : '') +
                            '"/></td><td><input type="text" class="form-control" name="product_attribute_num[' +
                            mergedString + ']" value="' + ((num_old != null) ? num_old[
                                mergedString] : '') + '"/></td></tr>'
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
