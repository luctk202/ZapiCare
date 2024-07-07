@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Thêm quyền')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.permission.store') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Mã quyền</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="name" class="form-control" name="name"
                                       placeholder="" value="{{ old('name') }}">
                                @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="display_name">Tên hiển thị</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="display_name" class="form-control" name="display_name"
                                       placeholder="" value="{{ old('display_name') }}">
                                @error('display_name')
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


@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

        })
    </script>
@endsection

