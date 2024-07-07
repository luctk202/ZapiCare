@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Sửa tài khoản')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.admin.update',['admin' => $admin->id]) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên nhân viên</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i data-feather="user"></i>
                                    </span>
                                    <input type="text" id="name" class="form-control" name="name"
                                           placeholder="Tên nhân viên" value="{{ old('name', $admin->name) }}">
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
                                <label class="col-form-label" for="pass-icon">Password</label>
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
                                <label class="col-form-label" for="pass-icon">Xác nhận mật khẩu</label>
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
                    {{--<div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="email-icon">Email</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i data-feather="mail"></i>
                                    </span>
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Email"
                                           value="{{ old('email', $admin->email) }}">
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
                                <label class="col-form-label" for="pass-icon">Password</label>
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
                                <label class="col-form-label" for="pass-icon">Xác nhận mật khẩu</label>
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
                    </div>--}}
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Nhóm quyền</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-select select2" id="role_id" name="role_id">
                                    <option value="0">Vui lòng chọn</option>
                                    @foreach($roles as $key => $role)
                                        <option value="{{ $key }}" @if($key == old('role_id', $admin->role_id)) selected @endif >{{ $role }}</option>
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

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
    <style>
        .table th {
            white-space: nowrap !important;
        }
    </style>
@endsection
@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
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
        })
    </script>
@endsection

