@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Chiết khấu')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.discount.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Giá trị đơn hàng tối thiểu</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="discount_total" class="form-control" name="discount_total"
                                           placeholder="" value="{{ old('discount_total') }}">
                                </div>
                                @error('discount_total')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="form-label" for="select2-multiple">Giá trị chiết khấu</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="row d-flex">
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="discount_value" value="{{ old('discount_value') }}"/>
                                        @error('discount_value')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control select2" id="" name="discount_type">
                                            @foreach($type as $k => $v)
                                                <option value="{{ $k }}" @if($k == old('discount_type')) selected @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
            $('.select2').select2({
                minimumResultsForSearch: -1
            });
        })
    </script>
@endsection

