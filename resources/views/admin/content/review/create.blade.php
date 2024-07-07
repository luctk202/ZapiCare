@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Chi tiết đánh giá')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reviews.store') }}">
                @csrf
                <input name="parent_id" value="{{$reviews->id}}" hidden>
                <input name="product_id" value="{{$reviews->product_id}}" hidden>
                <div class="row">
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="name">Đánh giá <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <textarea value="{{$reviews->comment}}" disabled rows="5" class="form-control" required="">{{$reviews->comment}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="email">Tên khách hàng</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control"
                                       placeholder="" value="{{ old('name',$reviews->name) }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label class="col-md-3 col-form-label">
                            Hình ảnh
                        </label>
                        <div class="col-md-9">
                            <div class="d-flex overflow-scroll" id="preview_images">
                                @if($reviews->images)
                                    @foreach($reviews->images as $image)
                                        <div
                                            style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                            <div style="width: 110px;height: 110px;padding:5px">
                                                <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $reviews->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"/>
                                            </div>
                                            <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                <div
                                                    style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $image }}</div>
                                                <small style="line-height: 20px"></small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-sm-3">
                            <label class="col-form-label" for="email">Ngày tạo</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" value="{{$reviews->created_at->format('Y-m-d')}}" disabled name="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            Câu trả lời
                        </label>
                        <div class="col-md-9">
                            <textarea name="comment" rows="5" class="form-control" required=""></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-1 col-sm-12 d-flex justify-content-center mb-2">
                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận
                    </button>
                </div>
            </form>
        </div>
        <div>
            <div class="card-header">
                <h5 class="mb-0 h6">Đánh giá liên quan</h5>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>Tên</th>
                        <th data-breakpoints="lg">Email</th>
                        <th data-breakpoints="lg">Số điện thoại</th>
                        <th data-breakpoints="lg">Đánh giá</th>
                        <th data-breakpoints="lg">Hình ảnh</th>
                        <th data-breakpoints="lg">Ngày tạo</th>
                        <th data-breakpoints="lg">Trạng thái</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews->childrenReviews as $key => $childrenReview)
                        {{--                    {{dd($comment->childrenComments[0])}}--}}
                        <tr>
                            <td>{{ ($key+1) }}</td>

                            <td>{{ $childrenReview->name }}</td>

                            <td>{{ $childrenReview->user ? $childrenReview->user->email : '' }}</td>
                            <td>{{$childrenReview->user ? $childrenReview->user->phone : ''}}</td>
                            <td>{{$childrenReview->comment}}</td>
                            <td>
                                <div class="d-flex justify-content-start">
                                    @if($childrenReview->images)
                                        @foreach($childrenReview->images as $image)
                                            <div
                                                style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                                <div style="width: 110px;height: 110px;padding:5px">
                                                    <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $reviews->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"/>
                                                </div>
                                                <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                                    <div
                                                        style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $image }}</div>
                                                    <small style="line-height: 20px"></small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>{{$childrenReview->created_at->format('Y-m-d')}}</td>
                            <td>
                                <div class="form-check form-check-primary form-switch">
                                    <input type="checkbox" data-id="{{ $childrenReview->id  }}"
                                           @if($childrenReview->status == 1) checked="true"
                                           @endif class="form-check-input js_update_status"/>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{--<script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>--}}
@endsection
@section('page-script')
    <script type="text/javascript">
    </script>
@endsection

