@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cáp độ')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.contact.update', ['contact' => $data->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="phone">Số điện thoại</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="phone" class="form-control" name="phone"
                                           placeholder="" value="{{ old('phone', $data->phone) }}">
                                </div>
                                @error('phone')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="email">Email </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="email" class="form-control" name="email"
                                           placeholder="" value="{{ old('email', $data->email) }}">
                                </div>
                                @error('email')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Đia chỉ</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="address" class="form-control" name="address"
                                           placeholder="" value="{{ old('address', $data->address) }}">
                                </div>
                                @error('address')
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
            // $('input[name="logo"]').on('change', function () {
            //     ProSell.readURL(this, 'logo')
            // })
            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })
        })
    </script>
@endsection

