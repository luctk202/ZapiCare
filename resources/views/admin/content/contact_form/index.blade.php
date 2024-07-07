
@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cấp độ')


@section('content')
    <!-- users list start -->

    <div class="card">
        <form method="GET" action="{{ route('admin.contact-form.index') }}">
            <div class="card-body">
                <h4 class="card-title">{{ translate('Bộ lọc') }}</h4>
                <div class="row">
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Họ tên</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Vui lòng nhập"
                               value="{{ request('name') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Vui lòng nhập"
                               value="{{ request('phone') }}">
                    </div>
                    <div class="col-3">
                        <label class="form-label" for="basicInput">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Vui lòng nhập"
                               value="{{ request('address') }}">
                    </div>
{{--                    <div class="col-3">--}}
{{--                        <label class="form-label" for="basicInput">Nội dung</label>--}}
{{--                        <input type="text" class="form-control" name="content" id="content" placeholder="Vui lòng nhập"--}}
{{--                               value="{{ request('content') }}">--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i data-feather='search'></i>
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mt-1">
                                Đang hiển thị từ {{ $data->firstItem() }} đến {{ $data->lastItem() }}
                                của {{ $data->total() }} bản ghi
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>Tên</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Nôi dung</th>
                                <th>Ngày</th>
                            </tr>
                            </thead>
                            <tbody style="border-top: 0 !important;">
                            @foreach($data as $dt)
                                <tr>
                                    <td>
                                        {{ $dt->name }}
                                    </td>
                                    <td>
                                        {{ $dt->phone }}
                                    </td>
                                    <td>
                                        {{ $dt->address }}
                                    </td>
                                    <td>
                                        {{ $dt->content }}
                                    </td>
                                    <td>
                                        {{ $dt->created_at }}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end col-12">
                        {{ $data->appends(request()->input())->links('admin.panels.paging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- users list ends -->
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
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection
