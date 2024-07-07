<?php

use App\Http\Controllers\Admin\GeneralManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminBankController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\FilterController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SampleDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest:admin']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
});

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('auth.update-password');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/revenue-by-day', [DashboardController::class, 'revenueByDay'])->name('dashboard.revenue-by-day');
    Route::get('/dashboard/sum', [DashboardController::class, 'sum'])->name('dashboard.sum');
    Route::get('/dashboard/top', [DashboardController::class, 'top'])->name('dashboard.top');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/report/top-province', 'top_province')->name('report.top-province')->middleware('permission:report');
        Route::get('/report/top-district', 'top_district')->name('report.top-district')->middleware('permission:report');
        Route::get('/report/top-product', 'top_product')->name('report.top-product')->middleware('permission:report');
        Route::get('/report/top-product-number', 'top_product_number')->name('report.top-product-number')->middleware('permission:report');
        Route::get('/report/top-user', 'top_user')->name('report.top-user')->middleware('permission:report');
    });

    Route::resource('admin', AdminController::class)->middleware('permission:admin');
    Route::controller(AdminController::class)->group(function () {
        Route::post('/admin/delete/{id}', 'delete')->name('admin.delete')->middleware('permission:admin');
        Route::post('/admin/update-status/{id}', 'update_status')->name('admin.update-status')->middleware('permission:admin');
    });

    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->middleware('permission:admin');
    Route::controller(\App\Http\Controllers\Admin\ReviewController::class)->group(function () {
        Route::post('/reviews/delete/{id}', 'delete')->name('reviews.delete')->middleware('permission:admin');
        Route::post('/reviews/update-status/{id}', 'update_status')->name('reviews.update-status')->middleware('permission:admin');
    });

    Route::resource('role', RoleController::class)->middleware('permission:admin');
    Route::controller(RoleController::class)->group(function () {
        Route::post('/role/delete/{id}', 'delete')->name('role.delete')->middleware('permission:admin');
    });

    Route::resource('permission', PermissionController::class)->middleware('permission:admin');
    Route::controller(PermissionController::class)->group(function () {
        Route::post('/permission/delete/{id}', 'delete')->name('permission.delete')->middleware('permission:admin');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/search', 'search')->name('customer.search');
        Route::post('/customer/update-status/{id}', 'update_status')->name('customer.update-status')->middleware('permission:customer');
        Route::post('/customer/verified/{id}', 'verified')->name('customer.verified')->middleware('permission:customer');
    });
    Route::resource('customer', CustomerController::class)->middleware('permission:customer');

    Route::controller(ShopController::class)->group(function () {
        Route::post('/shop/delete/{id}', 'delete')->name('zone.delete');
        Route::post('/shop/update-status/{id}', 'update_status')->name('zone.update-status');
        Route::get('/shop/search', 'search')->name('shop.search');
    })->middleware('permission:shop');
    Route::resource('shop', ShopController::class)->middleware('permission:shop');

    Route::controller(\App\Http\Controllers\Admin\PartnerController::class)->group(function () {
        Route::post('/partner/delete/{id}', 'delete')->name('partner.delete');
        Route::post('/partner/update-status/{id}', 'update_status')->name('partner.update-status');
        Route::get('/partner/search', 'search')->name('shop.search');
        Route::get('/partner/setting/{id}', 'setting')->name('partner.setting');
        Route::post('/partner/setting/{id}', 'updateSetting');
    })->middleware('permission:partner');
    Route::resource('partner', \App\Http\Controllers\Admin\PartnerController::class)->middleware('permission:partner');

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/search', 'search')->name('user.search');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product-draft/edit/{id}', 'edit_product_draft')->name('product.edit-product-draft')->middleware('permission:product');
        Route::post('/product-draft/update/{id}', 'update_product_draft')->name('product.update-product-draft')->middleware('permission:product');
        Route::post('/product/update-status/{id}', 'update_status')->name('product.update-status')->middleware('permission:product');
        Route::post('/product/update-typical/{id}', 'update_typical')->name('product.update-typical')->middleware('permission:product');
        Route::post('/product/update-new/{id}', 'update_new')->name('product.update-new')->middleware('permission:product');
        Route::post('/product/update-status/{id}', 'update_status')->name('product.update-status')->middleware('permission:product');
        Route::post('/product/approval/{id}', 'approval')->name('product.approval')->middleware('permission:product');
        Route::post('/product/cancel-approval/{id}', 'cancel_approval')->name('product.cancel-approval')->middleware('permission:product');
        Route::post('/upload', 'upload')->name('upload');
        Route::get('/product/edit-stock/{id}', 'edit_stock')->name('product.edit-stock')->middleware('permission:product');
        Route::post('/product/update-stock/{id}', 'update_stock')->name('product.update-stock')->middleware('permission:product');
        Route::post('/product/delete/{id}', 'delete')->name('product.delete')->middleware('permission:product');
        Route::get('/product/search-wholesale', 'search_wholesale')->name('product.search-wholesale')->middleware('permission:product');
        Route::get('/product/search', 'search')->name('product.search')->middleware('permission:product');
    });
    Route::resource('product', ProductController::class)->middleware('permission:product');

    Route::controller(ProductAttributeController::class)->group(function () {
        Route::post('/product-attribute/delete/{id}', 'delete')->name('product-attribute.delete')->middleware('permission:thuoc_tinh');
    });
    Route::resource('product-attribute', ProductAttributeController::class)->middleware('permission:thuoc_tinh');

    Route::controller(\App\Http\Controllers\Admin\TagLabelControlel::class)->group(function () {
        Route::post('/tag-label/delete/{id}', 'delete')->name('tag-label.delete');
    });
    Route::resource('tag-label', \App\Http\Controllers\Admin\TagLabelControlel::class);

    Route::controller(ProductCategoryController::class)->group(function () {
        Route::post('/product-category/delete/{id}', 'delete')->name('product-category.delete')->middleware('permission:product_category');
        Route::post('/product-category/update-status/{id}', 'update_status')->name('product-category.update-status')->middleware('permission:product_category');
        Route::post('/product-category/update-hot/{id}', 'update_hot')->name('product-category.update-hot')->middleware('permission:product_category');
        Route::post('/product-category/update-home/{id}', 'update_home')->name('product-category.update-home')->middleware('permission:product_category');
    });
    Route::resource('product-category', ProductCategoryController::class)->middleware('permission:product_category');

    Route::controller(FilterController::class)->group(function () {
        Route::post('/filter/delete/{id}', 'delete')->name('filter.delete')->middleware('permission:filter');
        Route::post('/filter/delete-attribute/{id}', 'delete_attribute')->name('filter.delete-attribute')->middleware('permission:filter');
        Route::get('/filter/create-attribute/{id}', 'create_attribute')->name('filter.create-attribute')->middleware('permission:filter');
        Route::post('/filter/create-attribute/{id}', 'store_attribute')->name('filter.store-attribute')->middleware('permission:filter');
        Route::get('/filter/edit-attribute/{id}', 'edit_attribute')->name('filter.edit-attribute')->middleware('permission:filter');
        Route::post('/filter/edit-attribute/{id}', 'update_attribute')->name('filter.update-attribute')->middleware('permission:filter');
    });
    Route::resource('filter', FilterController::class)->middleware('permission:filter');

    Route::controller(BrandController::class)->group(function () {
        Route::post('/brand/delete/{id}', 'delete')->name('brand.delete')->middleware('permission:product_brand');
        Route::post('/brand/update-status/{id}', 'update_status')->name('brand.update-status')->middleware('permission:product_brand');
        Route::post('/brand/update-hot/{id}', 'update_hot')->name('brand.update-hot')->middleware('permission:product_brand');
    });
    Route::resource('brand', BrandController::class)->middleware('permission:product_brand');


    Route::controller(OrderController::class)->group(function () {
        //Route::post('/cart/delete/{id}', 'delete')->name('cart.delete');
        Route::get('/orders/print/{id}', 'print')->name('orders.print')->middleware('permission:order');
        Route::post('/orders/update-status/{id}', 'update_status')->name('orders.update-status')->middleware('permission:order');
        Route::post('/orders/update-payment/{id}', 'update_payment')->name('orders.update-payment')->middleware('permission:order');
        Route::post('/orders/store', 'store')->name('orders.store')->middleware('permission:order');
        Route::post('/orders/add-handle', 'addHandle')->name('orders.add-handle')->middleware('permission:order');
        Route::post('/orders/note/{id}', 'store_note')->name('orders.note')->middleware('permission:order');
    });
    Route::resource('orders', OrderController::class)->middleware('permission:order');

    Route::controller(AddressController::class)->group(function () {
        Route::get('/address/district', 'district')->name('address.district');
        Route::get('/address/ward', 'ward')->name('address.ward`');
    });

    Route::controller(BannerController::class)->group(function () {
        Route::post('/banner/update-status/{id}', 'update_status')->name('banner.update-status')->middleware('permission:banner');
        Route::post('/banner/delete/{id}', 'delete')->name('banner.delete')->middleware('permission:banner');
    });
    Route::resource('banner', BannerController::class)->middleware('permission:banner');


    Route::resource('banner-shop', \App\Http\Controllers\Admin\BannerShopController::class)->middleware('permission:banner_shop');
    Route::controller(\App\Http\Controllers\Admin\BannerShopController::class)->group(function () {
        Route::post('/banner-shop/update-status/{id}', 'update_status')->name('banner-shop.update-status')->middleware('permission:banner_shop');
        Route::post('/banner-shop/delete/{id}', 'delete')->name('banner-shop.delete')->middleware('permission:banner_shop');
    });

    Route::controller(FlashSaleController::class)->group(function () {
        Route::post('/flash-sale/update-status/{id}', 'update_status')->name('flash-sale.update-status')->middleware('permission:flash_sale');
        Route::post('/flash-sale/update-home/{id}', 'update_home')->name('flash-sale.update-home')->middleware('permission:flash_sale');
        Route::post('/flash-sale/delete/{id}', 'delete')->name('flash-sale.delete')->middleware('permission:flash_sale');
    });
    Route::resource('flash-sale', FlashSaleController::class)->middleware('permission:flash_sale');

    Route::controller(BlogController::class)->group(function () {
        //Route::get('/blogs/search', 'search')->name('news.search');
        Route::post('/upload', 'upload')->name('upload');
        Route::post('/blogs/update-status/{id}', 'update_status')->name('blogs.update-status')->middleware('permission:blog');
    });
    Route::resource('blogs', BlogController::class)->middleware('permission:blog');

    Route::controller(BlogCategoryController::class)->group(function () {
        Route::post('/blog-category/delete/{id}', 'delete')->name('blog-category.delete');
        Route::post('/blog-category/update-status/{id}', 'update_status')->name('blog-category.update-status');
    });
    Route::resource('blog-category', BlogCategoryController::class);

    Route::controller(AdminBankController::class)->group(function () {
        Route::get('/admin-bank/edit', 'edit')->name('admin-bank.edit')->middleware('permission:admin_bank');
        Route::post('/admin-bank/update', 'update')->name('admin-bank.update')->middleware('permission:admin_bank');
    });

    Route::controller(NotificationController::class)->group(function () {
        //Route::post('/notification/update-status/{id}', 'update_status')->name('notification.update-status');
        Route::post('/notification/delete/{id}', 'delete')->name('notification.delete')->middleware('permission:notification');
    });
    Route::resource('notification', NotificationController::class)->middleware('permission:notification');

    Route::controller(NewsCategoryController::class)->group(function () {
        Route::post('/news-category/delete/{id}', 'delete')->name('news-category.delete')->middleware('permission:news_category');
        Route::post('/news-category/update-status/{id}', 'update_status')->name('news-category.update-status')->middleware('permission:news_category');
    });
    Route::resource('news-category', NewsCategoryController::class)->middleware('permission:news_category');

    Route::controller(NewsController::class)->group(function () {
        //Route::get('/blogs/search', 'search')->name('news.search');
        //Route::post('/upload', 'upload')->name('upload');
        Route::post('/news/update-status/{id}', 'update_status')->name('news.update-status')->middleware('permission:news');
    });
    Route::resource('news', NewsController::class)->middleware('permission:news');

    Route::controller(CouponController::class)->group(function () {
        Route::post('/coupon/update-status/{id}', 'update_status')->name('coupon.update-status')->middleware('permission:coupon');
        Route::post('/coupon/update-home/{id}', 'update_home')->name('coupon.update-home')->middleware('permission:coupon');
        Route::post('/coupon/delete/{id}', 'delete')->name('coupon.delete')->middleware('permission:coupon');
    });
    Route::resource('coupon', CouponController::class)->middleware('permission:coupon');

    //lực

    Route::controller(\App\Http\Controllers\Admin\LevelController::class)->group(function () {
        Route::post('/level/delete/{id}', 'destroy')->name('level.delete');
        Route::post('/level/update-status/{id}', 'update_status')->name('level.update-status');
    });
    Route::resource('level', \App\Http\Controllers\Admin\LevelController::class);

    Route::controller(\App\Http\Controllers\Admin\TestSystemController::class)->group(function () {
        Route::post('/test_system/delete/{id}', 'delete')->name('test_system.delete');
        Route::post('/test_system/update-status/{id}', 'update_status')->name('test_system.update-status');
    });
    Route::resource('test_system', \App\Http\Controllers\Admin\TestSystemController::class);

    Route::controller(\App\Http\Controllers\Admin\TestItemController::class)->group(function () {
        Route::post('/test_item/delete/{id}', 'delete')->name('test_item.delete');
        Route::get('/get-test-items/{testSystemId}', 'getTestItems');
        Route::post('/test_item/update-status/{id}', 'update_status')->name('test_item.update-status');
    });
    Route::resource('test_item', \App\Http\Controllers\Admin\TestItemController::class);

    Route::controller(SampleDataController::class)->group(function () {
        Route::post('/sample-data/delete/{id}', 'delete')->name('sample-data.delete');

    });

// Nam
    Route::group(['prefix' => 'generalManagement'], function () {

        Route::get('/', [GeneralManagementController::class, 'index'])->name('generalManagement.index');
        Route::get('/create', [GeneralManagementController::class, 'create'])->name('generalManagement.create');
        Route::post('/store', [GeneralManagementController::class, 'store'])->name('generalManagement.store');
        Route::get('/edit/{generalManagementId}', [GeneralManagementController::class, 'edit'])->name('generalManagement.edit');
        Route::put('/update/{generalManagementId}', [GeneralManagementController::class, 'update'])->name('generalManagement.update');
        Route::delete('/delete/{generalManagementId}', [GeneralManagementController::class, 'delete'])->name('generalManagement.delete');
    });

//dữ liệu mẫu
    Route::controller(SampleDataController::class)->group(function () {
        Route::post('/sample-data/delete/{id}', 'destroy');
//        Route::post('/sample-data/update-status/{id}', 'update_status')->name('level.update-status');
    });

    Route::resource('data-sample', SampleDataController::class);
    Route::get('sample-data', [SampleDataController::class, 'index'])->name('sample-data.index');
    Route::get('sample-data/create', [SampleDataController::class, 'create'])->name('sample-data.create');
    Route::post('sample-data', [SampleDataController::class, 'store'])->name('sample-data.store');
    Route::get('sample-data/edit/{sample_data}', [SampleDataController::class, 'edit'])->name('sample-data.edit');
    Route::put('sample-data/{sample_data}', [SampleDataController::class, 'update'])->name('sample-data.update');
//    Route::delete('sample-data/{sample_data}', [SampleDataController::class, 'destroy'])->name('sample-data.destroy');

    Route::get('/get-test-items/{testSystemId}', [SampleDataController::class, 'getTestItemsByTestSystemId']);



    Route::controller(\App\Http\Controllers\Admin\DiseaseControlle::class)->group(function () {
        Route::post('/disease/delete/{id}', 'destroy')->name('disease.delete');
//        Route::post('/disease/update-status/{id}', 'update_status')->name('level.update-status');
    });
    Route::resource('disease', \App\Http\Controllers\Admin\DiseaseControlle::class);

    Route::controller(\App\Http\Controllers\Admin\ContactController::class)->group(function () {
        Route::post('/contact/delete/{id}', 'destroy')->name('contact.delete');

    });
    Route::resource('contact', \App\Http\Controllers\Admin\ContactController::class);

    Route::controller(\App\Http\Controllers\Admin\ContactFormController::class)->group(function () {
        Route::get('/contact-form', 'index')->name('contact-form.index');

    });
});

