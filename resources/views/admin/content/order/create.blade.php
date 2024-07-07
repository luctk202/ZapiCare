@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Đơn hàng')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="mb-1 card">
                <div class="card-body">
                    <div class="row d-flex">
                        <div class="col-4">
                            <label class="col-form-label" for="name">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" name="name"
                                   placeholder="" value="{{ old('name') }}">
                        </div>
                        <div class="col-4">
                            <label class="col-form-label" for="category_id">Danh mục<span class="text-danger">*</span></label>
                            <select class="form-select select2s" name="category_id" id="category_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}"
                                            @if($value->id == old('category_id')) selected @endif>{{ $value->prefix . $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="col-form-label" for="brand_id">Thương hiệu<span class="text-danger">*</span></label>
                            <select class="form-select select2s" name="brand_id" id="brand_id">
                                <option value="0" selected>Vui lòng chọn</option>
                                @foreach($brands as $value)
                                    <option value="{{ $value->id }}" @if($value->id == old('brand_id')) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="justify-content-center my-1 product_loading text-center" style="display: none">
                <div class="spinner-border" rolSelecte="status" aria-hidden="true"></div>
            </div>
            <div class="row product_container">
            
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label" for="user_id">Khách hàng</label>
                            <div class="mb-1">
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="{{ request('user_id') }}" selected>{{ request('user_name') }}</option>
                                </select>
                            </div>
                            <input type="hidden" name="user_name" id="user_name" value="{{ request('user_name') }}">
                            <hr>
                        </div>
                        <div class="product_orders" style="max-height: 400px;overflow-y: scroll">
                            {{--<div class="row">
                                <div class="d-flex mb-1">
                                    <div class="item-quantity col-2"><input min="1" type="number" class="form-control" value="3"></div>
                                    <div class="item-name ms-1 col-9">
                                        <div>Sửa rửa mặt than tre <span class="text-danger">x 100,000</span></div>
                                        <div class="font-small-1"></div>
                                        <div>Mua <span class="text-danger">10</span> để được giá <span class="text-danger">80,000</span></div>
                                    </div>
                                    <div class="col-1 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-50 text-danger cursor-pointer">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <hr>--}}
                        </div>
                        <div class="price-details">
                            {{--<h6 class="price-title">Chi tiết giá</h6>--}}
                            <ul class="list-unstyled">
                                <li class="price-detail d-flex justify-content-between">
                                    <div class="detail-title">Giá bán lẻ</div>
                                    <div class="detail-amt total_price_sell">0</div>
                                </li>
                                <div class="note_discount_order text-danger"></div>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Điểm tích lũy</div>
                                    <div class="detail-amt total_point">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Chiết khấu đơn hàng</div>
                                    <div class="detail-amt total_discount_order">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Chiết khấu <span id="group_name">hạng thành viên</span></div>
                                    <div class="detail-amt total_discount_group">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Chiết khấu số lượng sản phẩm</div>
                                    <div class="detail-amt total_discount_product">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Lợi nhuận</div>
                                    <div class="detail-amt total_profit">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Thuế</div>
                                    <div class="detail-amt total_vat">0</div>
                                </li>
                                <li class="price-detail d-flex justify-content-between pt-1">
                                    <div class="detail-title">Phí vận chuyển</div>
                                    <div class="detail-amt total_fee">0</div>
                                </li>
                            </ul>
                            <hr>
                            <ul class="list-unstyled">
                                <li class="price-detail d-flex justify-content-between">
                                    <div class="detail-title detail-total">Tổng tiền thanh toán</div>
                                    <div class="detail-amt fw-bolder total_payment">0</div>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-primary d-block w-100 btn_payment">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
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
            ProSellPage.search('', 0, 0)

            let data_ctv_df = null
            let referred_by = `{{ request('user_id') }}`
            let referred_name = `{{ request('user_name') }}`
            if (referred_by && referred_name) {
                data_ctv_df = {
                    id: referred_by,
                    name: referred_name,
                    group: [],
                    selected: true
                }
            }
            let selectSaleAjax = $('#user_id');
            selectSaleAjax.wrap('<div class="position-relative"></div>').select2({
                dropdownAutoWidth: true,
                dropdownParent: selectSaleAjax.parent(),
                width: '100%',
                data: [
                    data_ctv_df
                ],
                ajax: {
                    url: '/admin/sale/search',
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
                    $(repo.element).attr('data-group', JSON.stringify(repo.group));
                    return repo.name || repo.phone;
                }
            }).on("select2:select", function (e) {
                let sale_name = $(this).find(":selected").data("name");
                let group = $(this).find(":selected").data("group");
                $('#user_name').val(sale_name);
                ProSellPage.data.group = group
                ProSellPage.renderPrice()
            }).on("select2:clear", function (e) {
                ProSellPage.data.group = null
                ProSellPage.renderPrice()
            });

            $('#name').keyup(delay(function (e) {
                let category_id = $('#category_id').val()
                let brand_id = $('#brand_id').val()
                ProSellPage.search(this.value, category_id, brand_id)
            }, 500));

            $('#category_id, #brand_id').on('change', function () {
                let search = $('#name').val()
                let category_id = $('#category_id').val()
                let brand_id = $('#brand_id').val()
                ProSellPage.search(search, category_id, brand_id)
            })

            $('.product_orders').on('input', '.product_number', function () {
                let value = $(this).val()
                value = (value !== '') ? value : 0
                let id = $(this).data('id')
                for (var i = 0; i < ProSellPage.data.products.length; i++) {
                    if (ProSellPage.data.products[i].id === id) {
                        ProSellPage.data.products[i].num = parseInt(value);
                        break;
                    }
                }
                ProSellPage.renderPrice(id);
            })

            $('.product_orders').on('click', '.product_remove', function () {
                let id = $(this).data('id')
                for (var i = 0; i < ProSellPage.data.products.length; i++) {
                    if (ProSellPage.data.products[i].id === id) {
                        ProSellPage.data.products.splice(i, 1)
                        break;
                    }
                }
                ProSellPage.renderPrice(id);
            })

            $('.product_container').on('click', '.product_item', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let point = $(this).data('point');
                let price = $(this).data('price');
                let weight = $(this).data('weight');
                let wholesalesData = $(this).data('wholesales');
                let discountsData = $(this).data('discounts');
                let taxData = $(this).data('tax');
                let discounts = JSON.parse(decodeURIComponent(discountsData));
                let wholesales = JSON.parse(decodeURIComponent(wholesalesData));
                let tax = JSON.parse(decodeURIComponent(taxData));
                var found = false;
                for (var i = 0; i < ProSellPage.data.products.length; i++) {
                    if (ProSellPage.data.products[i].id === id) {
                        ProSellPage.data.products[i].num++;
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    var newItem = {id, name, point, price_sell: price, weight, wholesales, discounts, tax, num: 1};
                    ProSellPage.data.products.push(newItem);
                }
                ProSellPage.renderPrice();
            })

            $('.btn_payment').on('click', function () {
                let user_id = $('#user_id').val();
                if (!user_id) {
                    Swal.fire({
                        text: 'Vui lòng nhập thông tin khách hàng',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttons: {
                            confirm: 'Xác nhận'
                        }
                    });
                    return false;
                }
                if (ProSellPage.data.products.length === 0) {
                    Swal.fire({
                        text: 'Vui lòng thêm sản phẩm vào đơn hàng',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttons: {
                            confirm: 'Xác nhận'
                        }
                    });
                    return false;
                }
                let products = []
                ProSellPage.data.products.forEach(function (item){
                    products.push({
                        id:item.id,
                        num:item.num
                    })
                })
                let data = {
                    user_id:user_id,
                    products:products
                }
                ProSellPage.createOrder(data)
            })
        })

        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        function findNearestElements(data, amount) {
            let smaller = null;
            let larger = null;

            for (let i = 0; i < data.length; i++) {
                const element = data[i];

                if (element.discount_total <= amount) {
                    if (smaller === null || element.discount_total > smaller.discount_total) {
                        smaller = element;
                    }
                } else if (element.discount_total > amount) {
                    if (larger === null || element.discount_total < larger.discount_total) {
                        larger = element;
                    }
                }
            }

            return {smaller, larger};
        }

        var ProSellPage = {
            data: {
                group: null,
                discounts: @json($discounts),
                fee: @json($fee),
                products: []
            },
            search: function (search, category_id, brand_id) {
                $('.product_loading').show()
                Axios({
                    method: 'get',
                    url: '/admin/product/search-wholesale',
                    params: {search, category_id, brand_id}
                }).then(function (response) {
                    $('.product_loading').hide()
                    let res = response.data;
                    if (res.result === true) {
                        let data = res.data.data
                        $('.product_container').html(ProSellPage.renderProduct(data))
                        feather.replace()
                    } else {

                    }
                }).catch(function (error) {
                    $('.product_loading').hide()
                });
            },
            findNearestWholeSale: function (data, number) {
                let smaller = null;
                let larger = null;
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    if (element.min_number <= number) {
                        if (smaller === null || element.min_number > smaller.min_number) {
                            smaller = element;
                        }
                    } else if (element.min_number > number) {
                        if (larger === null || element.min_number < larger.min_number) {
                            larger = element;
                        }
                    }
                }
                return {smaller, larger};
            },
            findNearestFee: function (data, weight) {
                let smaller = null;
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    if (element.weight <= weight) {
                        if (smaller === null || element.weight > smaller.weight) {
                            smaller = element;
                        }
                    }
                }
                return smaller;
            },
            renderProduct: function (data) {
                let html = [];
                data.forEach(function (item) {
                    let tax = {
                        tax_value: item.tax_value,
                        tax_type: item.tax_type,
                        vat_value: item.vat_value,
                        vat_type: item.vat_type,
                    }
                    html.push(
                        `<div class="col-xxl-2 col-xl-3 col-4 product_item" data-id="` + item.id + `" data-name="` + item.name + `" data-point="` + item.point_value + `" data-price="` + item.price_sell + `" data-weight="` + item.weight + `" data-discounts="` + encodeURIComponent(JSON.stringify(item.discounts)) + `" data-tax="` + encodeURIComponent(JSON.stringify(tax)) + `" data-wholesales="` + encodeURIComponent(JSON.stringify(item.wholesales)) + `">
                            <div class="ecommerce-card cursor-pointer card">
                                <div class="item-img text-center mx-auto" style="min-height: 50px">
                                    <img class="img-fluid card-img-top" src="` + window.location.origin + '/storage/' + item.avatar + `" alt="` + item.name + `">
                                </div>
                                <div class="p-50 card-body">
                                    <div class="item-wrapper mb-50">
                                        <h6 class="item-name">
                                            <div title="` + item.name + `" class="text-body" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">` + item.name + `</div>
                                        </h6>
                                    </div>
                                    <div class="item-cost"><h6 class="item-price text-danger">` + item.price_sell.toLocaleString() + `</h6></div>
                                </div>
                                <div class="item-options text-center">
                                    <button class="btn-cart btn btn-primary w-100">
                                        <i data-feather="shopping-cart"></i>
                                        <span>Thêm</span></button>
                                </div>
                            </div>
                        </div>`
                    )
                })
                return html
            },
            renderPrice: function (focus_id = 0) {
                if (ProSellPage.data.group !== null) {
                    $('#group_name').html(ProSellPage.data.group.name)
                } else {
                    $('#group_name').html('hạng thành viên')
                }
                if (ProSellPage.data.products.length > 0) {
                    let html_products = []
                    let total_price_sell = 0;
                    let total_point = 0;
                    let total_discount_order = 0;
                    let total_discount_product = 0;
                    let total_discount_group = 0;
                    let total_vat = 0;
                    let total_weight = 0;
                    let total_fee = 0;
                    let total_profit = 0;
                    let total_payment = 0;
                    let html_discount_order = '';
                    

                    ProSellPage.data.products.forEach(function (item) {
                        let html_discount_product = '';
                        let tax = item.tax
                        if (tax.tax_value > 0) {
                            if (tax.tax_type === 1) {
                                total_vat += Math.ceil(((tax.tax_value * item.price_sell) / 100 * item.num))
                            }
                            if (tax.tax_type === 2) {
                                total_vat += (tax.tax_value * item.num)
                            }
                        }
                        if (tax.vat_value > 0) {
                            if (tax.vat_type === 1) {
                                total_vat += Math.ceil(((tax.vat_value * item.price_sell) / 100 * item.num))
                            }
                            if (tax.vat_type === 2) {
                                total_vat += (tax.vat_value * item.num)
                            }
                        }

                        let data_wholesale = item.wholesales
                        let resWholesale = ProSellPage.findNearestWholeSale(data_wholesale, item.num)
                        if (resWholesale.smaller !== null) {
                            let discount_product = resWholesale.smaller;
                            total_discount_product += (item.price_sell - discount_product.price_sell) * item.num
                        }
                        if (resWholesale.larger !== null) {
                            let next_discount_product = resWholesale.larger
                            html_discount_product = 'Mua <span class="text-danger">' + next_discount_product.min_number + '</span> để được giá <span class="text-danger">' + next_discount_product.price_sell.toLocaleString() + '</span>'
                        }
                        total_weight += (item.weight * item.num)

                        let data_discounts = item.discounts;
                        if (ProSellPage.data.group !== null && data_discounts.length > 0) {
                            let data_discount = data_discounts.find(function (obj) {
                                return obj.group_id === ProSellPage.data.group.id;
                            });
                            if (data_discount) {
                                if (data_discount.discount_type === 1) {
                                    total_discount_group += parseInt((data_discount.discount_value * item.price_sell) / 100) * item.num
                                }
                                if (data_discount.discount_type === 2) {
                                    total_discount_group += data_discount.discount_value * item.num
                                }
                            }
                        }
                        html_products.push(
                            `<div class="row mt-50">
                                <div class="d-flex mb-1">
                                    <div class="item-quantity col-2"><input min="1" type="number" data-id="` + item.id + `" class="form-control product_number" value="` + item.num + `"></div>
                                    <div class="item-name ms-1 col-9">
                                        <div>` + item.name + ` <span class="text-danger">x ` + item.price_sell.toLocaleString() + `</span></div>
                                        <div class="font-small-1"></div>
                                        <div>` + html_discount_product + `</div>
                                    </div>
                                    <div class="col-1 mt-1">
                                        <i data-feather="trash" class="product_remove cursor-pointer" data-id="` + item.id + `"></i>
                                    </div>
                                </div>
                            </div>`
                        )
                        total_price_sell += (item.num * item.price_sell)
                        total_point += (item.num * item.point)
                    })
                    let resDiscount = findNearestElements(ProSellPage.data.discounts, total_price_sell)
                    if (resDiscount.smaller !== null) {
                        let discount_order = resDiscount.smaller
                        if (discount_order.discount_type === 1) {
                            total_discount_order = parseInt((discount_order.discount_value * total_price_sell) / 100)
                        }
                        if (discount_order.discount_type === 2) {
                            total_discount_order = discount_order.discount_value
                        }
                    }

                    if (resDiscount.larger !== null) {
                        let next_discount_order = resDiscount.larger
                        if (next_discount_order.discount_type === 1) {
                            html_discount_order = 'Đặt đơn hàng <span class="text-danger">' + next_discount_order.discount_total.toLocaleString() + '</span> để được giảm <span class="text-danger">' + next_discount_order.discount_value + '%</span>';
                        }
                        if (next_discount_order.discount_type === 2) {
                            html_discount_order = 'Đặt đơn hàng <span class="text-danger">' + next_discount_order.discount_total.toLocaleString() + '</span> để được giảm <span class="text-danger">' + next_discount_order.discount_value + 'VND</span>';
                        }
                    }

                    let fee = ProSellPage.findNearestFee(ProSellPage.data.fee, total_weight)
                    if (fee !== null) {
                        total_fee += fee.price
                        if (fee.weight_than > 0 && fee.price_than > 0) {
                            let weight_than = parseInt(total_weight - fee.weight)
                            total_fee += parseInt(Math.ceil(weight_than / fee.weight_than) * fee.price_than)
                        }
                    }
                    total_profit = total_discount_order + total_discount_product + total_discount_group
                    total_payment = total_price_sell - total_profit + total_fee + total_vat

                    html_products.push(`<hr>`)
                    $('.product_orders').html(html_products)
                    if (focus_id > 0) {
                        let val = $('.product_number[data-id="' + focus_id + '"]').val()
                        $('.product_number[data-id="' + focus_id + '"]').val('').val(val).focus()
                    }

                    feather.replace()
                    $('.total_price_sell').html(total_price_sell.toLocaleString())
                    $('.total_point').html(total_point.toLocaleString())
                    $('.total_discount_order').html(total_discount_order.toLocaleString())
                    $('.note_discount_order').html(html_discount_order)
                    $('.total_discount_group').html(total_discount_group.toLocaleString())
                    $('.total_discount_product').html(total_discount_product.toLocaleString())
                    $('.total_vat').html(total_vat.toLocaleString())
                    $('.total_fee').html(total_fee.toLocaleString())
                    $('.total_profit').html(total_profit.toLocaleString())
                    $('.total_payment').html(total_payment.toLocaleString())
                } else {
                    $('.product_orders').html('')
                    $('.total_price_sell').html(0)
                    $('.total_point').html(0)
                    $('.total_discount_order').html(0)
                    $('.note_discount_order').html('')
                    $('.total_discount_group').html(0)
                    $('.total_discount_product').html(0)
                    $('.total_vat').html(0)
                    $('.total_fee').html(0)
                    $('.total_profit').html(0)
                    $('.total_payment').html(0)
                }
            },
            createOrder:function (data){
                console.log(data)
                if (ProSell.config.ajaxProcess === false) {
                    ProSell.loading.show()
                    ProSell.config.ajaxProcess = true
                    Axios({
                        method: 'post',
                        url: '/admin/orders/store',
                        data:data
                    }).then(function (response) {
                        let res = response.data;
                        if (res.result === true) {
                            window.location.href = window.location.origin + '/admin/orders'
                        } else {
                            ProSell.loading.hide()
                            ProSell.config.ajaxProcess = false
                            //Swal.fire(res.message)
                            Swal.fire({
                                text: res.message,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttons: {
                                    confirm: 'Xác nhận'
                                }
                            });
                        }
                    }).catch(function (error) {
                        ProSell.loading.hide()
                        ProSell.config.ajaxProcess = false
                        Swal.fire({
                            text: 'Hệ thống đang lỗi không tạo được đơn hàng',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttons: {
                                confirm: 'Xác nhận'
                            }
                        });
                    });
                }
            }
        }
    </script>
@endsection

