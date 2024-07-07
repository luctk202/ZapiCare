@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thêm danh mục')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product-category.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên danh mục</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="name" class="form-control" name="name"
                                           placeholder="" value="{{ old('name') }}">
                                </div>
                                @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Danh mục cha</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-select select2s" id="parent_id" name="parent_id">
                                    <option value="0">Vui lòng chọn</option>
                                    @foreach($opt as $value)
                                        <option value="{{ $value->id }}" @if($value->id == old('parent_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="advice">Mô tả ngắn</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea id="short_description" class="form-control" name="short_description">{{ old('short_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="advice">Mô tả dài</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea id="long_description" class="form-control" name="long_description">{{ old('long_description') }}</textarea>
                            </div>
                        </div>
                    </div>

{{--                    <div class="col-12">--}}
{{--                        <div class="mb-1 row">--}}
{{--                            <div class="col-sm-3">--}}
{{--                                <label class="form-label" for="select2-multiple">Bộ lọc</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                <select class="select2s form-select" id="filter_id" name="filter_id[]" multiple>--}}
{{--                                    @if(!empty($filters))--}}
{{--                                        @foreach($filters as $filter)--}}
{{--                                            <option value="{{ $filter->id  }}"--}}
{{--                                                    @if(in_array($filter->id, old('filter_id',[]))) selected @endif>{{ $filter->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label for="image" class="col-form-label">Ảnh nền</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="image" id="image" accept="image/png, image/gif, image/jpeg"
                                       placeholder="Vui lọng chọn file">
                                @error('image')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                                <div class="d-flex overflow-scroll" id="preview_image">

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
        </div>
    </div>
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({
                minimumResultsForSearch: -1
            });
            $('.select2s').select2({
                //minimumResultsForSearch: -1
            });
            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })
        })
    </script>
@endsection

