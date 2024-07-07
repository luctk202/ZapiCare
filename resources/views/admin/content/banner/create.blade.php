@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Tạo banner')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.banner.store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="category_id">Tên banner <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                        @error('name')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{--<div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="category_id">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Vui lòng chọn</option>
                            @foreach($categories as $value)
                                <option value="{{ $value->id }}"
                                        @if($value->id == old('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>--}}
                    <div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="position">Vị trí hiển thị <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="position" name="position">
                            <option value="">Vui lòng chọn</option>
                            @foreach($positions as $key => $value)
                                <option value="{{ $key }}"
                                        @if($key == old('position')) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('position')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
{{--                    <div class=" col-sm-12 mb-1">--}}
{{--                        <label class="col-form-label" for="category_id">Danh mục</label>--}}
{{--                        <select class="form-select" id="category_id" name="category_id">--}}
{{--                            <option value="0" selected>Vui lòng chọn</option>--}}
{{--                            @foreach($categories as $value)--}}
{{--                                <option value="{{ $value->id }}"--}}
{{--                                        @if($value->id == old('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        @error('category_id')--}}
{{--                        <span class="form-text text-danger">{{ $message }}</span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    {{--<div class="col-sm-12 mb-1">
                        <label class="col-form-label" for="group_id">Danh mục </label>
                        <select class="form-select" id="group_id" name="group_id">
                            @foreach($group as $k => $v)
                                <option value="{{ $k }}" @if(request('group_id', 0) == $k) selected @endif>{{ $v }}</option>
                            @endforeach
                        </select>
                        @error('group_id')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>--}}
                    <div class="col-sm-12 mb-1">
                        <label for="image" class="col-form-label">Ảnh</label>
                        <input class="form-control" type="file" name="image" id="image" accept="image/png, image/gif, image/jpeg"
                               placeholder="Vui lọng chọn file">
                        @error('image')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                        <div class="d-flex overflow-scroll" id="preview_image">

                        </div>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label for="link" class="col-form-label">Link</label>
                        <input class="form-control" type="text" name="link" id="link" value="{{ old('link') }}">
                        @error('link')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="">Thời gian hiển thị</label>
                        <input
                            type="text"
                            name="time"
                            value="{{ old('time') }}"
                            class="form-control flatpickr-range"
                            placeholder="DD-MM-YYYY to DD-MM-YYYY"
                        />
                        @error('time')
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

@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

            $('select').select2({
                minimumResultsForSearch: -1
            });

            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })

            $('.flatpickr-range').flatpickr({
                mode: 'range',
                dateFormat: "d-m-Y",
            });
        })
    </script>
@endsection

