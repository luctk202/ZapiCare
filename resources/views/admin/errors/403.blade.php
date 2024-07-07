@extends('admin/layouts/contentLayoutMaster')

@section('content')
    <div class="misc-wrapper">
        <div class="misc-inner p-2 p-sm-3">
            <div class="w-100 text-center">
                <h2 class="mb-1">Bạn không có quyền truy cập tính năng này! 🔐</h2>
                <p class="mb-2">Vui lòng liên hệ quản trị viên.</p>
                <a class="btn btn-primary mb-1 btn-sm-block" href="javascript:void(0)" onclick="history.back()">Quay lại</a>
                {{--<img class="img-fluid" src="{{asset('images/pages/not-authorized.svg')}}" alt="Not authorized page" />--}}
            </div>
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



