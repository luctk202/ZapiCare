@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đổi mật khẩu đăng nhập')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.auth.update-password') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Mật khẩu hiện tại</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i data-feather="lock"></i>
                                    </span>
                                    <input type="password" id="old_password" class="form-control" name="old_password" autocomplete="off"
                                           placeholder="Mật khẩu">
                                </div>
                                @error('old_password')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Mật khẩu mới</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i data-feather="lock"></i>
                                    </span>
                                    <input type="password" id="password" class="form-control" name="password" autocomplete="off"
                                           placeholder="Mật khẩu">
                                </div>
                                @error('password')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Xác nhận mật khẩu mới</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i data-feather="lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" class="form-control"
                                           name="password_confirmation" placeholder="Xác nhận mật khẩu">
                                </div>
                                @error('password_confirmation')
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

