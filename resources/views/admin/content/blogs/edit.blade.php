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
    <form method="POST" id="create-blogs" action="{{ route('admin.blogs.update',['blog' => $data->id]) }}"
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
                            <label for="image" class="col-form-label">Ảnh bài viết</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*"
                                   placeholder="Vui lọng chọn file">
                            @error('image')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                            <div class="d-flex overflow-scroll" id="preview_image">
                                @if($data->image)
                                    <div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                        <div style="width: 110px;height: 110px;padding:5px">
                                            <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($data->image) }}"/>
                                        </div>
                                        <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                            <div style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
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

