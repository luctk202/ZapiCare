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
    <form method="POST" id="create-blogs" action="{{ route('admin.blogs.store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{--<div class="col-4">
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
                    </div>--}}
                    <div class="col-12">
                        {{--<div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="category_id">Danh mục tin đăng</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == request('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>--}}
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="title">Tiêu đề<span
                                        class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                            @error('title')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{--<div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="blog_category_id">Danh mục bài viết</label>
                            <select class="form-select" id="blog_category_id" name="blog_category_id">
                                <option value="0">Vui lòng chọn</option>
                                @foreach($blog_categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == old('blog_category_id')) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>--}}
                        <div class="col-sm-12 mb-1">
                            <label for="image" class="col-form-label">Ảnh bài viết</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*"
                                   placeholder="Vui lọng chọn file">
                            @error('image')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_image">
        
                            </div>
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="">Mô tả </label>
                            <textarea class="form-control" name="description" >{{ old('description') }}</textarea>
                            @error('description')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="col-form-label" for="">Nội dung bài viết</label>
                            <textarea class="form-control" name="content" id="content" >{!! old('content') !!}</textarea>
                            @error('content')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            {{--<div id="editor" style="min-height: 300px">
                                {!! old('content') !!}
                            </div>--}}
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
                    'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                    'table emoticons template paste help codesample'
                ],

                image_title : true,
                automatic_uploads: true,
                images_upload_url : '/admin/upload',
                file_picker_types: 'image',
                relative_urls: false, // Sử dụng liên kết tuyệt đối
                remove_script_host: false, // Giữ nguyên host trong đường dẫn
                file_picker_callback: function (cv, value, meta){
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function(){
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        render.onload = function(){
                            var id = 'blobid'+(new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {title:file.name});
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
            /*var myEditor;
            ClassicEditor.create(document.querySelector('#editor'), {
                    //toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            }).then(editor => {
                console.log('Editor was initialized', editor);
                myEditor = editor;
            }).catch(error => {
                    //console.error(error);
            });*/
            
            $('select').select2({
                //dropdownParent: $('#addressForm')
                //minimumResultsForSearch: -1
                minimumResultsForSearch: -1
            });

            const form = document.querySelector('#create-blogs');
            form.addEventListener('submit', function(event) {
                //const editorData = document.querySelector('#my-editor').value;
                const editorInput = document.createElement('input');
                editorInput.type = 'hidden';
                editorInput.name = 'content';
                editorInput.value = myEditor.getData();
                form.appendChild(editorInput);
            });

            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
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
                        params:{
                            province_id:province_id
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
                            if(district_id){
                                $('#modal_district_id').val(district_id).trigger("change", [ward_id]);
                            }
                        }else {
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
                        params:{
                            district_id:district_id
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
                            if(ward_id){
                                $('#modal_ward_id').val(ward_id)
                            }
                        }else {
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

