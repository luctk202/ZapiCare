@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
    {{-- Page css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <div class="col-12">
                <div class="d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                    <div>
                        {{--<h4 class="card-title">Line Chart</h4>
                        <span class="card-subtitle text-muted">Commercial networks</span>--}}
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-merge mb-2" >
                            <span class="input-group-text" id="basic-addon-search2" style="background-color: #fff !important;"><i class="" data-feather="calendar"></i></span>
                            <input type="text" class="form-control bg-transparent flat-picker" placeholder="YYYY-MM-DD" style="background-color: #ffffff !important;min-width: 220px;padding-left: 15px"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row match-height">
            <!-- Medal Card -->
            
            <!--/ Medal Card -->
            
            <!-- Statistics Card -->
            <div class=" col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Thống kê</h4>
                        {{--<div class="d-flex align-items-center">
                          <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>
                        </div>--}}
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            {{--<div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="shopping-bag" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0 js_count_order_wholesale_new">--</h4>
                                        <p class="card-text font-small-3 mb-0">Đơn sỉ chờ xử lý</p>
                                    </div>
                                </div>
                            </div>--}}
                            <div class="col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="shopping-bag" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0 js_count_order_retail_new">--</h4>
                                        <p class="card-text font-small-3 mb-0">Đơn chờ xử lý</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0 js_sum_order_retail">--</h4>
                                        <p class="card-text font-small-3 mb-0">Doanh thu</p>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="col-xl-3 col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-success me-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0 js_sum_order_wholesale">--</h4>
                                        <p class="card-text font-small-3 mb-0">Doanh thu bán sỉ</p>
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->
        </div>
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="line-area-chart" style="min-height: 400px"></div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row match-height">
            
            <!-- Transaction Card -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-transaction">
                    <div class="card-header">
                        <h4 class="card-title">Doanh thu cao nhất</h4>
                        {{--<div class="dropdown chart-dropdown">
                            <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Last 28 Days</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Last Year</a>
                            </div>
                        </div>--}}
                    </div>
                    <div class="card-body top_user">
                    
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-4 col-md-6 col-12">
                <div class="card card-transaction">
                    <div class="card-header">
                        <h4 class="card-title">Điểm tích lũy cao nhất</h4>
                    </div>
                    <div class="card-body top_point">
            
                    </div>
                </div>
            </div>--}}
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-transaction">
                    <div class="card-header">
                        <h4 class="card-title">Sản phẩm bán chạy</h4>
                    </div>
                    <div class="card-body top_product">
            
                    </div>
                </div>
            </div>
            <!--/ Transaction Card -->
        </div>
    </section>
    <!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    
    <script type="text/javascript">

        const ProSellPage = {
            loadSum: function (start_date, end_date) {
                //('.flat-picker').attr('disabled', true);
                Axios({
                    method: 'get',
                    url: '/admin/dashboard/sum',
                    params: {
                        start_date,
                        end_date
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res.result === true) {
                        //$('.flat-picker').attr('disabled', false);
                        $('.js_count_order_retail_new').html(numberWithCommas(res.count_order_retail_new))
                        /*$('.js_count_order_wholesale_new').html(numberWithCommas(res.count_order_wholesale_new))*/
                        $('.js_sum_order_retail').html(numberWithCommas(res.sum_order_retail))
                        /*$('.js_sum_order_wholesale').html(numberWithCommas(res.sum_order_wholesale))*/
                    } else {
                        $('.js_count_order_retail_new').html('--')
                        /*$('.js_count_order_wholesale_new').html('--')*/
                        $('.js_sum_order_retail').html('--')
                        /*$('.js_sum_order_wholesale').html('--')*/
                        //$('.flat-picker').attr('disabled', false);
                    }
                }).catch(function (error) {
                });
            },
            loadRevenue: function (start_date, end_date) {
                $('.flat-picker').attr('disabled', true);
                Axios({
                    method: 'get',
                    url: '/admin/dashboard/revenue-by-day',
                    params: {
                        start_date,
                        end_date
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res.result === true) {
                        $('.flat-picker').attr('disabled', false);
                        let series = [
                            /*{
                                name: 'Sỉ',
                                data: res.wholesales
                            },*/
                            {
                                name: 'Doanh thu',
                                data: res.retail
                            }
                        ]
                        console.log(series)
                        console.log(areaChart)
                        areaChart.updateOptions({
                            xaxis: {
                                categories: res.categories
                            }
                        })
                        areaChart.updateSeries(series)

                    } else {
                        $('.flat-picker').attr('disabled', false);
                    }
                }).catch(function (error) {
                });
            },
            loadTop: function (start_date, end_date) {
                Axios({
                    method: 'get',
                    url: '/admin/dashboard/top',
                    params: {
                        start_date,
                        end_date
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res.result === true) {
                        console.log(res)
                        $('.top_user').html(ProSellPage.renderTopUser(res.top_user))
                        /*$('.top_point').html(ProSellPage.renderTopUser(res.top_point))*/
                        $('.top_product').html(ProSellPage.renderProduct(res.top_product))
                    } else {
                    }
                }).catch(function (error) {
                });
            },
            renderTopUser(data){
                let html = []
                data.forEach(function (item, index){
                    let cl = (index < 2) ? 'bg-light-primary' : 'bg-light-secondary'
                    html.push(
                        `<div class="transaction-item">
                            <div class="d-flex">
                                <div class="avatar ` + cl +` rounded float-start">
                                    <div class="avatar-content">
                                        `+ (index +1) +`
                                    </div>
                                </div>
                                <div class="transaction-percentage">
                                    <h6 class="transaction-title">`+ item.name +`</h6>
                                    <small>`+ item.phone +`</small>
                                </div>
                            </div>
                            <div class="fw-bolder text-danger">`+ numberWithCommas(item.export_total) +`</div>
                        </div>`
                    )
                })
                return html
            },
            renderProduct(data){
                let html = []
                data.forEach(function (item, index){
                    let cl = (index < 2) ? 'bg-light-primary' : 'bg-light-secondary'
                    html.push(
                        `<div class="transaction-item">
                            <div class="d-flex">
                                <div class="avatar ` + cl +` rounded float-start">
                                    <div class="avatar-content">
                                        `+ (index +1) +`
                                    </div>
                                </div>
                                <div class="transaction-percentage">
                                    <h6 class="transaction-title mt-1">`+ item.name +`</h6>
                                </div>
                            </div>
                            <div class="fw-bolder text-danger">`+ numberWithCommas(item.export_total) +`</div>
                        </div>`
                    )
                })
                return html
            }
        }

        const dateToString = (dateObj, char = '/') => {
            const year = dateObj.getFullYear()
            const month = ("0" + (dateObj.getMonth() + 1)).slice(-2) // Months are zero-indexed, so add 1
            const day = ("0" + dateObj.getDate()).slice(-2)
            return `${day}${char}${month}${char}${year}`
        }

        function numberWithCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }


        let end = dateToString(new Date(), '-')
        let thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        let start = dateToString(thirtyDaysAgo, '-')
        ProSellPage.loadRevenue(start, end)
        ProSellPage.loadSum(start, end)
        ProSellPage.loadTop(start, end)

        let flatPicker = $('.flat-picker')
        if (flatPicker.length) {
            flatPicker.each(function () {
                $(this).flatpickr({
                    mode: 'range',
                    dateFormat: 'd-m-Y',
                    defaultDate: [start, end],
                    onChange: function (selectedDates, dateStr, instance) {
                        if (selectedDates.length === 2) {
                            let start = dateToString(selectedDates[0], '-')
                            let end = dateToString(selectedDates[1], '-')
                            ProSellPage.loadRevenue(start, end)
                            ProSellPage.loadSum(start, end)
                            ProSellPage.loadTop(start, end)
                            //console.log(start, end)
                        }

                    }
                });
            });
        }

        let areaChartEl = document.querySelector('#line-area-chart')
        let areaChartConfig = {
            chart: {
                height: 400,
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                //show: false,
                width: 1.5,
                curve: 'straight'
            },
            legend: {
                position: 'top',
                horizontalAlign: 'start'
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        if (value === '' || value === 0) {
                            return 0
                        }
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }
                }
            },
            series: [],
            /*noData: {
                text: 'Loading...'
            },*/
            /*xaxis: {
                categories: res.categories
            },*/
            yaxis: {
                opposite: false,
                labels: {
                    formatter: function (value) {
                        if (value === '' || value === 0) {
                            return 0
                        }
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }
                }
            }
        };
        var areaChart = new ApexCharts(areaChartEl, areaChartConfig);
        areaChart.render();
    </script>
@endsection
