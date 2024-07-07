@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thêm quản lí chung')
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
    <form method="POST" id="form_create_product" action="{{ route('admin.generalManagement.store') }}"
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
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="name">Tên <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="title" class="form-control title" name="title" placeholder=""
                                       value="{{ old('title') }}">
                                @error('title')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="col-form-label" for="slug">Slug <span
                                        class="text-danger">*</span></label>
                                <input  type="text" id="slug" class="form-control slug" name="slug" placeholder=""
                                       value="{{ old('slug') }}">
                                @error('slug')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="">
                            <label class="col-form-label" for="description">Nội dung</label>
                            <textarea class="form-control" name="content"
                                      id="description">{!! old('content') !!}</textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <!-- Thêm thư viện TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/zcb3va2hvob1k0x7410m4ujxxcuy4y32t4eo4d96va692ocm/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {



            const title = document.querySelector('.title');
            const slug = document.querySelector('.slug');

            function getSlug(title) {
                let slug = title.toLowerCase();
                slug = title.toLowerCase();

                //Đổi ký tự có dấu thành không dấu
                slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
                slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
                slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
                slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
                slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
                slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
                slug = slug.replace(/đ/gi, "d");
                //Xóa các ký tự đặt biệt
                slug = slug.replace(
                    /\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi,
                    ""
                );
                //Đổi khoảng trắng thành ký tự gạch ngang
                slug = slug.replace(/ /gi, "-");
                //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                slug = slug.replace(/\-\-\-\-\-/gi, "-");
                slug = slug.replace(/\-\-\-\-/gi, "-");
                slug = slug.replace(/\-\-\-/gi, "-");
                slug = slug.replace(/\-\-/gi, "-");
                //Xóa các ký tự gạch ngang ở đầu và cuối
                slug = "@" + slug + "@";
                slug = slug.replace(/\@\-|\-\@|\@/gi, "");
                return slug;
            }

            title.addEventListener('keyup', (e) => {
                const titleValue = e.target.value;
                slug.value = getSlug(titleValue);
            })

            slug.addEventListener('change', (e) => {
                if (slug.value == '') {
                    const title = document.querySelector('.title');
                    const titleValue = title.value;
                    slug.value = getSlug(titleValue);
                }
            })

            tinymce.init({
                selector: '#description',
                height: 500,
                plugins: [
                    'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                    'table emoticons template paste help codesample'
                ],
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullpage | ' +
                    'forecolor backcolor emoticons | help | codesample',
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '/admin/upload',
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
                            cb(blobInfo.blobUri(), {title: file.name});
                        };
                    };
                    input.click();
                },
                relative_urls: false,
                remove_script_host: false,
                menu: {
                    favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
                },
                menubar: 'favs file edit view insert format tools table help'
            });
        })
    </script>
@endsection
