@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Tag sản phẩm')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tag-label.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên tag sản phẩm</label>
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
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="color">Màu sắc</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="color" class="form-control jscolor" name="color" value="{{ old('color') }}">
                                </div>
                                @error('color')
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.5/jscolor.min.js"></script>
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

