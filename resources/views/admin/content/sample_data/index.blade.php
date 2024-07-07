@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Mục kiểm tra')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.sample-data.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label for="test_system_id" class="form-label">Hệ thống</label>
                        <select class="form-select select2" id="test_system_id" name="test_system_id">
                            <option value="0" selected>Vui lòng chọn</option>
                            @foreach($test_systems as $test_system)
                                <option value="{{ $test_system->id }}"
                                        @if($test_system->id == request('test_system_id')) selected @endif>
                                    {{ $test_system->test_item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <label class="form-label" for="test_item_id">Tên mục kiểm tra <span class="text-danger">*</span></label>
                        <select class="form-select select2s" name="test_item_id" id="test_item_id">
                            <option value="0" selected>Vui lòng chọn</option>
                            @foreach ($test_items as $value)
                                <option value="{{ $value->id }}"
                                        @if ($value->id == old('test_item_id')) selected @endif>
                                    {{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    {{--                    <div class="col-3">--}}
                    {{--                        <label for="test_system_id" class="form-label">Hệ thống</label>--}}
                    {{--                        <select class="form-select select2" id="test_system_id" name="test_system_id"--}}
                    {{--                                onchange="loadTestItems()">--}}
                    {{--                            <option value="0" selected>Vui lòng chọn</option>--}}
                    {{--                            @foreach($test_systems as $test_system)--}}
                    {{--                                <option value="{{ $test_system->id }}"--}}
                    {{--                                        @if($test_system->id == request('test_system_id')) selected @endif>--}}
                    {{--                                    {{ $test_system->prefix . $test_system->test_item }}--}}
                    {{--                                </option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}

                    {{--                    <div class="col-3">--}}
                    {{--                        <label for="test_item_id" class="form-label">Mục kiểm tra</label>--}}
                    {{--                        <select class="form-select select2" id="test_item_id" name="test_item_id">--}}
                    {{--                            <option value="0" selected>Vui lòng chọn</option>--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}

{{--                    <div class="col-3">--}}
{{--                        <label for="level_id" class="form-label">Mức độ</label>--}}
{{--                        <select class="form-select select2" id="level_id" name="level_id">--}}
{{--                            <option value="0" selected>Vui lòng chọn</option>--}}
{{--                            @foreach($levels as $level)--}}
{{--                                <option value="{{ $level->id }}"--}}
{{--                                        @if($level->id == request('level_id')) selected @endif>--}}
{{--                                    {{ $level->prefix . $level->name }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    {{--                    <div class="col-3">--}}
                    {{--                        <label class="form-label" for="basicInput">Tên mục kiểm tra</label>--}}
                    {{--                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"--}}
                    {{--                               value="{{ request('name') }}">--}}
                    {{--                    </div>--}}
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Lý giải chỉ số</label>
                        <input type="text" class="form-control" name="explanation" id="explanation"
                               placeholder="Vui lòng nhập"
                               value="{{ request('explanation') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Dấu hiệu thường gặp</label>
                        <input type="text" class="form-control" name="symptom" id="symptom" placeholder="Vui lòng nhập"
                               value="{{ request('symptom') }}">
                    </div>
                    {{--                    <div class="col-3">--}}
                    {{--                        <label class="form-label" for="basicInput">Bệnh lý thường găp</label>--}}
                    {{--                        <input type="text" class="form-control" name="disease" id="disease" placeholder="Vui lòng nhập"--}}
                    {{--                               value="{{ request('disease') }}">--}}
                    {{--                    </div>--}}
{{--                    <div class="col-3">--}}
{{--                        <label class="form-label" for="disease">Bệnh lý thường gặp <span class="text-danger">*</span></label>--}}
{{--                        <select class="form-select select2s" name="disease_id" id="disease_id">--}}
{{--                            <option value="0" selected>Vui lòng chọn</option>--}}
{{--                            @foreach ($diseases as $value)--}}
{{--                                <option value="{{ $value->id }}"--}}
{{--                                        @if ($value->id == old('disease_id')) selected @endif>--}}
{{--                                    {{ $value->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

                    <div class="col-3">
                        <label class="form-label" for="basicInput">Tư vấn</label>
                        <input type="text" class="form-control" name="advice" id="advice" placeholder="Vui lòng nhập"
                               value="{{ request('advice') }}">
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
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                            <div>
                            </div>
                            <div>
                                <a href="{{ route('admin.sample-data.create') }}" class="btn btn-success">Thêm mới</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                {{--                                <th width="10%">Mã</th>--}}
                                <th>Tên mục kiểm tra</th>
                                <th>Hệ thống</th>
{{--                                <th>Cấp độ</th>--}}
{{--                                <th>Khoảng min</th>--}}
{{--                                <th>Khoảng max</th>--}}
                                <th style="min-width: 200px">Lý giải chỉ số</th>
                                <th style="min-width: 200px">Dấu hiệu thường gặp</th>
                                <th style="min-width: 200px">Bệnh lý thường gặp</th>
                                <th style="min-width: 300px">Tư vấn</th>
                                <th style="min-width: 200px">Sản phẩm gợi ý</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $dt)
                                <tr>
                                    <td>
                                        {{ $dt->testItem->name ?? '' }}
                                    </td>
                                    <td>
                                        {{$dt->testItem->testSystem->test_item ?? '' }}
                                    </td>
{{--                                    <td>--}}
{{--                                        {{ $dt->levels->name ?? '' }}--}}
{{--                                    </td>--}}
{{--                                    <td style="white-space: nowrap !important;">--}}
{{--                                        {{ $dt->range_min ?? '' }}--}}
{{--                                    </td>--}}
{{--                                    <td style="white-space: nowrap !important;">--}}
{{--                                        {{ $dt->range_max ?? ''}}--}}
{{--                                    </td>--}}
                                    <td>
                                        {{ $dt->explanation ?? ''}}
                                    </td>
                                    <td>
                                        {{ $dt->symptom ?? ''}}
                                    </td>
                                    <td>
                                        @foreach ($dt->diseases as $disease)
                                            {{ $disease->name }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $dt->advice ?? ''}}
                                    </td>
                                    <td>
                                        @foreach ($dt->products as $product)
                                            {{ $product->name }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center col-actions">
                                            <a class="me-2"
                                               href="{{ route('admin.sample-data.edit', ['sample_data' => $dt->id]) }}"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Sửa"
                                               aria-label="Sửa">
                                                <i data-feather="edit-2" class="text-body"></i>
                                            </a>
                                            <a class="me-25 js_btn_delete" data-id="{{ $dt->id }}"
                                               href="javascript:void(0)"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa"
                                               aria-label="Xóa">
                                                <i data-feather="trash" class="text-body"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $data->appends(request()->input())->links('admin.panels.paging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- users list ends -->
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
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2(); // Khởi tạo Select2 cho tất cả các phần tử có class select2
        });

        $(document).ready(function () {
            $('.js_update_status').on('change', function () {
                let id = $(this).data('id');
                let status = 0;
                if ($(this).prop('checked') === true) {
                    status = 1;
                }
                ProSellPage.updateStatus(id, status)
            })

            $('.js_btn_delete').on('click', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhân xóa',
                    text: "Bạn chắc chắn muốn xóa dữ liệu này ?",
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
                        ProSellPage.delete(id)
                    }
                });
            })
            $('#test_system_id').change(function () {
                var testSystemId = $(this).val();
                console.log(testSystemId);
                if (testSystemId) {
                    $.ajax({
                        url: '/admin/get-test-items/' + testSystemId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#test_item_id').empty();
                            $('#test_item_id').append('<option value="0">Vui lòng chọn</option>');
                            $.each(data, function (key, value) {
                                console.log(value);
                                $('#test_item_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#test_item_id').empty();
                    $('#test_item_id').append('<option value="0">Vui lòng chọn</option>');
                }
            });


        });
        var ProSellPage = {
            delete: function (id) {
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/sample-data/delete/' + id,
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
            // updateStatus: function (id, status) {
            //     if (ProSell.config.ajaxProcess === false) {
            //         ProSell.loading.show()
            //         ProSell.config.ajaxProcess = true
            //         Axios({
            //             method: 'post',
            //             url: '/admin/level/update-status/' + id,
            //             data: {status: status}
            //         }).then(function (response) {
            //             let res = response.data;
            //             if (res.result === true) {
            //                 location.reload()
            //             } else {
            //                 ProSell.loading.hide()
            //                 ProSell.config.ajaxProcess = false
            //                 Swal.fire(res.message)
            //             }
            //         }).catch(function (error) {
            //             ProSell.loading.hide()
            //             ProSell.config.ajaxProcess = false
            //         });
            //     }
            // },
        }

    </script>
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

