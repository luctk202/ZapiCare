@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Gửi Thông báo')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.notification.store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="title">Tiêu đề <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="{{old('title')}}">
                        @error('title')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="text">Nội dung <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="text" value="{{old('text')}}">
                        @error('text')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label for="image" class="col-form-label">Icon</label>
                        <input class="form-control" type="file" name="icon" id="icon" accept="image/png, image/gif, image/jpeg"
                               placeholder="Vui lọng chọn file">
                        @error('icon')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                        <div class="d-flex overflow-scroll" id="preview_icon">
                        </div>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="type">Loại thông báo <span
                                    class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type">
                            <option value="1" @if(old('type') == 1) selected @endif>Khuyến mại</option>
                            <option value="2" @if(old('type') == 2) selected @endif>Thành viên</option>
                        </select>
                        @error('type')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="time_send">Thời gian gửi <span
                                    class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="time_send"
                            value="{{ old('time_send') }}"
                            class="form-control flatpickr-range"
                            placeholder="DD-MM-YYYY H:I"
                        />
                        @error('time_send')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="">Chi tiết thông báo</label>
                        <textarea class="form-control" name="description" id="description" >{!! old('description') !!}</textarea>
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
    <script src="https://cdn.tiny.cloud/1/15bnbk3l03aihwl2yzmxwk6lk00c666drmpw0pr7w4rww8l8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

            tinymce.init({
                selector: '#description',
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

            $('select').select2({
                minimumResultsForSearch: -1
            });

            $('input[name="icon"]').on('change', function () {
                ProSell.readURL(this, 'icon')
            })

            $('.flatpickr-range').flatpickr({
                //mode: 'range',
                enableTime: true,
                dateFormat: "d-m-Y H:i",
            });
        })
    </script>
@endsection

