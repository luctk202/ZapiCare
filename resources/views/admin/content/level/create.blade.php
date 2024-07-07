@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cấp độ')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.level.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên cấp độ</label>
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
                                <label for="image" class="col-form-label">Level Image</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="level_image" id="level_image" accept="image/png, image/gif, image/jpeg"
                                       placeholder="Vui lọng chọn file">
                                @error('level_image')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                                <div class="d-flex overflow-scroll" id="preview_logo">

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-12">
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
                    </div>--}}
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
            // $('input[name="logo"]').on('change', function () {
            //     ProSell.readURL(this, 'logo')
            // })
            $('input[name="level_image"]').on('change', function () {
                ProSell.readURL(this, 'level_image')
            })
        })
    </script>
@endsection

