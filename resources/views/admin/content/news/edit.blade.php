@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đăng tin')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <style>
        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none;
        }

        .ck-editor__editable {
            min-height: 300px;
        }
    </style>
@endsection
@section('content')
    <form method="POST" id="create-blogs" action="{{ route('admin.news.update',['news' => $data->id]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="title">Tiêu đề<span
                                        class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $data->title) }}">
                            @error('title')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="author">Tác giả<span
                                        class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="author" value="{{ old('author', ($data->author ?? auth()->user()->name)) }}">
                            @error('author')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="category_id">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select select2s" name="category_id" id="category_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == old('category_id', $data->category_id)) selected @endif>{{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="type">Loại bài viết</label>
                            <select class="form-select" id="type" name="type">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($type as $key => $value)
                                    <option value="{{ $key }}"
                                            @if($key == old('type', $data->type)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('type')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1 js_product" @if(old('type', $data->type) != \App\Repositories\News\NewsRepository::TYPE_PRODUCT) style="display: none" @endif>
                            <label class="col-form-label" for="product_id">Sản phẩm</label>
                            <select class="form-select" id="product_id" name="product_id">
                                <option value="{{ old('product_id', $data->product_id) }}" selected>{{ old('product_name', $data->product_id ? $data->product->name : '') }}</option>
                            </select>
                            @error('product_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <input type="hidden" name="product_name" id="product_name" value="{{ old('product_name', $data->product_id ? $data->product->name : '') }}">
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="count_view">Số lượng xem</label>
                            <input type="text" class="form-control" name="count_view" value="{{ old('count_view', $data->count_view) }}">
                            @error('count_view')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="count_share">Số lượng chia sẻ</label>
                            <input type="text" class="form-control" name="count_share" value="{{ old('count_share', $data->count_share) }}">
                            @error('count_share')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label for="image" class="col-form-label">Ảnh bài viết</label>
                            <input class="form-control" type="file" name="image[]" id="image" accept="image/*"
                                   placeholder="Vui lọng chọn file" multiple>
                            @error('image')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_image">
                                @if($data->image)
                                    @foreach($data->image as $image)
                                        <div
                                                style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px">
                                                <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"/>
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
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="video">Video</label>
                            <input type="file" class="form-control" name="video" value="{{ old('video') }}" placeholder="Vui lọng chọn file" accept="video/mp4,video/x-m4v,video/*">
                            @error('url')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_video">
                                @if($data->video)
                                    <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                        <div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div>
                                        <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                            <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $data->video }}</div>
                                            <small style="line-height: 20px"></small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="url_share">Link chia sẻ</label>
                            <input type="text" class="form-control" name="url_share" value="{{ old('url_share', $data->url_share) }}">
                            @error('url_share')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="">Mô tả </label>
                            <textarea class="form-control" name="description">{{ old('description', $data->description) }}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="">Nội dung bài viết</label>
                            <textarea class="form-control" name="content" id="content" >{!! old('content', $data->content) !!}</textarea>
                            @error('content')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="https://cdn.tiny.cloud/1/15bnbk3l03aihwl2yzmxwk6lk00c666drmpw0pr7w4rww8l8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({
                selector: '#content',
                height: 500,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste imagetools wordcount'
                ],
                toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });

            $('select').select2({
                //dropdownParent: $('#addressForm')
                //minimumResultsForSearch: -1
                minimumResultsForSearch: -1
            });

            const form = document.querySelector('#create-blogs');
            form.addEventListener('submit', function (event) {
                //const editorData = document.querySelector('#my-editor').value;
                const editorInput = document.createElement('input');
                editorInput.type = 'hidden';
                editorInput.name = 'content';
                editorInput.value = myEditor.getData();
                form.appendChild(editorInput);
            });

            $('input[name="image[]"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })
            $('input[name="video"]').on('change', function () {
                ProSell.readURL(this, 'video')
            })

            let data = null
            let product_id = `{{ old('product_id', $data->product_id) }}`
            let product_name = `{{ old('product_name', $data->product_id ? $data->product->name : '') }}`
            if (product_id && product_name) {
                data = {
                    id: product_id,
                    name: product_name,
                    group: [],
                    selected: true
                }
            }
            console.log(data);
            let selectSaleAjax = $('#product_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data: [data],
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
                let product_name = $(this).find(":selected").data("name");
                $('#product_name').val(product_name);
            });

            $('#type').on('change', function (){
                let type = $(this).val();
                if(type != `{{ \App\Repositories\News\NewsRepository::TYPE_PRODUCT }}`){
                    $('.js_product').hide();
                }else {
                    console.log(222)
                    $('.js_product').show();
                }
            })
        })

        var ProSellPage = {
            loadDistrict: function (province_id, district_id, ward_id) {
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
                        $('#modal_province_id').attr('disabled', false)
                        let res = response.data;
                        if (res.result === true) {
                            let data = res.data
                            data.forEach(function (item) {
                                $('#modal_district_id').append('<option value="' + item.id + '">' + item.name + '</option>')
                            })
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            if (district_id) {
                                $('#modal_district_id').val(district_id).trigger("change", [ward_id]);
                            }
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
            loadWard: function (district_id, ward_id) {
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
                        $('#modal_district_id').attr('disabled', false)
                        let res = response.data;
                        if (res.result === true) {
                            let data = res.data
                            data.forEach(function (item) {
                                $('#modal_ward_id').append('<option value="' + item.id + '">' + item.name + '</option>')
                            })
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            if (ward_id) {
                                $('#modal_ward_id').val(ward_id)
                            }
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

