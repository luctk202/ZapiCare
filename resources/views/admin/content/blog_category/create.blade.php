@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thêm danh mục')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blog-category.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên danh mục <span class="text-danger">*</span></label>
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
                                <label class="col-form-label" for="name">Vị trí</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="name" class="form-control" name="position"
                                           placeholder="" value="{{ old('position', 999) }}">
                                </div>
                                @error('position')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Danh mục tin đăng</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="0">Vui lòng chọn</option>
                                    @foreach($opt as $value)
                                        <option value="{{ $value->id }}"
                                                @if($value->id == old('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                                    @endforeach
                                </select>
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

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('select[name="category_id"]').select2({
                //minimumResultsForSearch: -1
            });
            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })
            $('input[name="icon"]').on('change', function () {
                ProSell.readURL(this, 'icon')
            })
        })
    </script>
@endsection

