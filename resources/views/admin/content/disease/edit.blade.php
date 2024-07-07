@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Bênh lý')
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
    <form method="POST" action="{{ route('admin.disease.update', ['disease' => $data->id]) }}">
        @csrf
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
                            <label class="col-form-label" for="name">Tên bệnh lý</label>
                            <textarea id="name" class="form-control" name="name">{{ old('name',$data->name) }}</textarea>
                            @error('name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="product_id">Sản phẩm gợi ý <span class="text-danger">*</span></label>
                            <select class="form-select select2s" name="product_ids[]" id="product_id" multiple>
                                @foreach ($products as $value)
                                    <option value="{{ $value->id }}"
                                            @if (is_array(old('product_ids', $data->products->pluck('id')->toArray())) && in_array($value->id, old('product_ids', $data->products->pluck('id')->toArray())))
                                                selected
                                        @endif>
                                        {{$value->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_ids')
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
        </div>
    </form>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/15bnbk3l03aihwl2yzmxwk6lk00c666drmpw0pr7w4rww8l8/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
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
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                    };
                    input.click();
                },
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullpage | ' +
                    'forecolor backcolor emoticons | help | codesample',
                menu: {
                    favs: {
                        title: 'My Favorites',
                        items: 'code visualaid | searchreplace | spellchecker | emoticons'
                    }
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
