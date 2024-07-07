@extends('admin/layouts/fullLayoutMaster')

@section('title', 'Đăng nhập')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
    <div class="auth-wrapper auth-basic px-2">
        <div class="auth-inner my-2">
            <!-- Login basic -->
            <div class="card mb-0">
                <div class="card-body">
                    <a href="#" class="brand-logo">
                        {{--<img src="{{ asset('images/logo/logo-bellhome.png') }}" alt="" width="120px">--}}
                        <h2 class="brand-text text-primary ms-1">ZapiCare</h2>
                    </a>

                    <h4 class="card-title mb-1">Welcome you! 👋</h4>
                    <p class="card-text mb-2">Vui lòng đăng nhập tài khoản</p>

                    <form class="auth-login-form mt-2"  action="{{ route('admin.auth.login') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label for="login-email" class="form-label">Email</label>
                            <input
                                type="text"
                                class="form-control"
                                id="login-email"
                                name="email"
                                placeholder="john@example.com"
                                aria-describedby="login-email"
                                tabindex="1"
                                autofocus
                                value="{{ old('email') }}"
                            />
                            @error('email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="login-password">Mật khẩu</label>
                                {{--<a href="{{url('auth/forgot-password-basic')}}">
                                  <small>Forgot Password?</small>
                                </a>--}}
                            </div>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input
                                    type="password"
                                    class="form-control form-control-merge"
                                    id="login-password"
                                    name="password"
                                    tabindex="2"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="login-password"
                                />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                            @error('password')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" value="1" tabindex="3"/>
                                <label class="form-check-label" for="remember-me"> Ghi nhớ tài khoản </label>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" tabindex="4">Đăng nhập</button>
                    </form>

                    {{--<p class="text-center mt-2">
                      <span>New on our platform?</span>
                      <a href="{{url('auth/register-basic')}}">
                        <span>Create an account</span>
                      </a>
                    </p>--}}

                    {{-- <div class="divider my-2">
                       <div class="divider-text">or</div>
                     </div>--}}

                    {{--<div class="auth-footer-btn d-flex justify-content-center">
                      <a href="#" class="btn btn-facebook">
                        <i data-feather="facebook"></i>
                      </a>
                      <a href="#" class="btn btn-twitter white">
                        <i data-feather="twitter"></i>
                      </a>
                      <a href="#" class="btn btn-google">
                        <i data-feather="mail"></i>
                      </a>
                      <a href="#" class="btn btn-github">
                        <i data-feather="github"></i>
                      </a>
                    </div>--}}
                </div>
            </div>
            <!-- /Login basic -->
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
    <script src="{{asset(mix('js/scripts/pages/auth-login.js'))}}"></script>
@endsection
