@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Mục kiểm tra hệ thống')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.test_system.update', ['test_system' => $data->id]) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên hệ thống </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="test_item" class="form-control" name="test_item"
                                           placeholder="" value="{{ old('test_item', $data->test_item) }}">
                                </div>
                                @error('test_item')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
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


