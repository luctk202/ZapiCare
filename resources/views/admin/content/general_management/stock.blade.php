@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Sản phẩm bán sỉ')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product.update-stock', ['id' => $product->id]) }}">
                @csrf
                <div class="row">
                    @if (\Session::has('success'))
                        <div class="alert alert-success p-2 mb-1">
                            {!! \Session::get('success') !!}
                        </div>
                    @endif
                    
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Thuộc tính</th>
                            <th>Số lượng tồn</th>
                            <th>Số lượng nhập</th>
                        </tr>
                        </thead>
                        <tbody style="border-top: 0 !important;">
                        @foreach($stocks as $stock)
                            <tr>
                                <td>
                                    {{ $product->name }}
                                </td>
                                <td>
                                    {{ $stock->attributes_name }}
                                </td>
                                <td>
                                    {{ $stock->num ?? '' }}
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="stocks[{{$stock->id}}]">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="col-sm-12 d-flex justify-content-center mb-2 mt-2">
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

            let data_ctv_df = null
            let referred_by = `{{ old('parent_id') }}`
            let referred_name = `{{ old('parent_name') }}`
            if (referred_by && referred_name) {
                data_ctv_df = {
                    id: referred_by,
                    name: referred_name,
                    selected: true
                }
            }
            let selectSaleAjax = $('#parent_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data: [
                    data_ctv_df
                ],
                ajax: {
                    url: '/admin/ctv/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        if (data.result === true) {
                            let dt = data.data
                            return {
                                results: dt.data,
                                pagination: {
                                    more: params.page * 50 < dt.total
                                }
                            };
                        }
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: 'Tên/Email/Điện thoại',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    let markup = '<div class="d-flex flex-column"><span class="text-truncate text-body"><span class="">' + repo.name + '</span></span><small class="text-muted">' + repo.phone + '</small> </div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    $(repo.element).attr('data-name', repo.name);
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let sale_name = $(this).find(":selected").data("name");
                $('#parent_name').val(sale_name);
            });
        })
    </script>
@endsection

