@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Chi tiet san pham')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/swiper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce-details.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-number-input.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">

@endsection

@section('content')
    <!-- app e-commerce details start -->
    <section class="app-ecommerce-details">
        <div class="card">
            <!-- Product Details starts -->
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                        <div class="d-flex align-items-center justify-content-center">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($news->images[0] ?? '') }}"
                                class="img-fluid product-img"
                                alt="{{ $news->title }}"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <h4>{{ $news->title }}</h4>
                        <span class="card-text item-company">Đăng bởi <a href="#"
                                                                         class="company-name">Anh Tuấn</a></span>
                        <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                            <h4 class="item-price me-1">{{ !empty($news->price) ? number_format($news->price, 0, '.', '.') : 'Cho/tặng' }}</h4>
                            {{--<ul class="unstyled-list list-inline ps-1 border-start">
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                            </ul>--}}
                        </div>
                        {{--<p class="card-text">Available - <span class="text-success">In stock</span></p>--}}
                        <p class="card-text">
                            {{ $news->description }}
                        </p>
                        {{--<ul class="product-features list-unstyled">
                            <li><i data-feather="shopping-cart"></i> <span>Free Shipping</span></li>
                            <li>
                                <i data-feather="dollar-sign"></i>
                                <span>EMI options available</span>
                            </li>
                        </ul>--}}
                        <hr/>
                        @if($settings->filters)
                            @foreach($settings->filters as $filters)
                                <div class="product-color-options">
                                    @if(!empty($filters['label']))
                                        <h6>{{ $filters['label'] }}</h6>
                                    @endif
                                    @if($filters['attributes'])
                                    <ul class="list-unstyled mb-0">
                                        @foreach($filters['attributes'] as $attributes)
                                            @if(isset($news->filters[$attributes['key']]))
                                            <li class="">
                                                @if(!empty($attributes['label']))
                                                <span>{{ $attributes['label'] }} : </span>
                                                @endif
                                                <span>{{ is_array($news->filters[$attributes['key']]) ? implode(',', $news->filters[$attributes['key']]) : $news->filters[$attributes['key']] }}</span>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                                <hr/>
                            @endforeach
                        @endif

                        <div class="d-flex flex-column flex-sm-row pt-1">
                            <a href="#" class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0">
                                <i data-feather="shopping-cart" class="me-50"></i>
                                <span class="add-to-cart">Add to cart</span>
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-wishlist me-0 me-sm-1 mb-1 mb-sm-0">
                                <i data-feather="heart" class="me-50"></i>
                                <span>Wishlist</span>
                            </a>
                            <div class="btn-group dropdown-icon-wrapper btn-share">
                                <button
                                    type="button"
                                    class="btn btn-icon hide-arrow btn-outline-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i data-feather="share-2"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" class="dropdown-item">
                                        <i data-feather="facebook"></i>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i data-feather="twitter"></i>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i data-feather="youtube"></i>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i data-feather="instagram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Details ends -->

        </div>
    </section>
    <!-- app e-commerce details end -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/swiper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-ecommerce-details.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-number-input.js')) }}"></script>
@endsection
