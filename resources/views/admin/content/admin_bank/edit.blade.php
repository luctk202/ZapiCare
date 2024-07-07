@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Tài khoản thanh toán')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.admin-bank.update') }}">
                @csrf
                @if (\Session::has('success'))
                    <div class="alert alert-success p-2">
                        {!! \Session::get('success') !!}
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="bank_username">Chủ tài khoản </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="bank_username" class="form-control" name="bank_username"
                                       placeholder="" value="{{ old('bank_username', $data->bank_username) }}">
                                @error('bank_username')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="bank_number">Số tài khoản </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="bank_number" class="form-control" name="bank_number"
                                       placeholder="" value="{{ old('bank_number', $data->bank_number) }}">
                                @error('bank_number')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="bank_name">Ngân hàng</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-select" id="bank_name" name="bank_name">
                                    @foreach($banks as  $value)
                                        <option value="{{ $value }}" @if($value == old('bank_name', $data->bank_name)) selected @endif >{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="bank_content">Nội dung chuyển khoản</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="bank_content" class="form-control" name="bank_content"
                                       placeholder="" value="{{ old('bank_number', $data->bank_content) }}">
                                @error('bank_content')
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
            /*$('select').select2({
                //minimumResultsForSearch: -1
            });*/
        })
    </script>
@endsection

