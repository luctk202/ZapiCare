@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Mục kiểm tra')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
    <form method="POST" id="form_create_product" action="{{ route('admin.test_item.store') }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="mb-1 row">
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Tên mục kiểm tra </label>
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
                <div class="mb-1 row">
                    <div class="col-sm-3">
                        <label class="col-form-label" for="test_system_id">Hệ thống kiểm tra <span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-select select2s" name="test_system_id" id="test_system_id">
                            <option value="0" selected>Vui lòng chọn</option>
                            @foreach ($test_systems as $value)
                                <option value="{{ $value->id }}"
                                        @if ($value->id == old('test_system_id')) selected @endif>
                                    {{$value->test_item }}</option>
                            @endforeach
                        </select>
                        @error('test_system_id')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-1 row">
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bình thường</label>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Khoảng min</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="normal_range_min" class="form-control" name="normal_range_min"
                                   placeholder="Khoảng min..." value="{{ old('normal_range_min') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Khoảng max</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="normal_range_max" class="form-control" name="normal_range_max"
                                   placeholder="Khoảng min..." value="{{ old('normal_range_max') }}">
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-1 row">
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường mức thấp </label>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường nhẹ</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="mild_low_min" class="form-control" name="mild_low_min"
                                   placeholder="Khoảng min..." value="{{ old('mild_low_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="mild_low_max" class="form-control" name="mild_low_max"
                                   placeholder="Khoảng max..." value="{{ old('mild_low_max') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường vừa</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="moderately_low_min" class="form-control" name="moderately_low_min"
                                   placeholder="Khoảng min..." value="{{ old('moderately_low_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="moderately_low_max" class="form-control" name="moderately_low_max"
                                   placeholder="Khoảng max..." value="{{ old('moderately_low_max') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường nặng</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="severity_low_min" class="form-control" name="severity_low_min"
                                   placeholder="Khoảng min..." value="{{ old('severity_low_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="severity_low_max" class="form-control" name="severity_low_max"
                                   placeholder="Khoảng max..." value="{{ old('severity_low_max') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-1 row">
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường mức cao </label>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường nhẹ</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="mild_high_min" class="form-control" name="mild_high_min"
                                   placeholder="Khoảng min..." value="{{ old('mild_high_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="mild_high_max" class="form-control" name="mild_high_max"
                                   placeholder="Khoảng max..." value="{{ old('mild_high_max') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường vừa</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="moderately_high_min" class="form-control" name="moderately_high_min"
                                   placeholder="Khoảng min..." value="{{ old('moderately_high_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="moderately_high_max" class="form-control" name="moderately_high_max"
                                   placeholder="Khoảng max..." value="{{ old('moderately_high_max') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label" for="name">Bất thường nặng</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="severity_high_min" class="form-control" name="severity_high_min"
                                   placeholder="Khoảng min..." value="{{ old('severity_high_min') }}">
                        </div>
                        <br>
                        <div class="input-group input-group-merge">
                            <input type="text" id="severity_high_max" class="form-control" name="severity_high_max"
                                   placeholder="Khoảng max..." value="{{ old('severity_high_max') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-12 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận</button>
        </div>
    </form>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2s').select2();
        });
    </script>
@endsection
