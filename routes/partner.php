<?php
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Pos\ProductController;
//use App\Http\Controllers\Pos\CustomerController;
//use App\Http\Controllers\Pos\CustomerGroupController;
use App\Http\Controllers\Partner\OrderController;
//use App\Http\Controllers\Pos\ShopController;
//use App\Http\Controllers\Pos\ReportController;
//use App\Http\Controllers\Pos\TransactionController;
//use App\Http\Controllers\Pos\NotificationController;
use App\Http\Controllers\Api\CronjobController;
//use App\Http\Controllers\Pos\ProductCategoryController;

Route::group(['prefix' => 'auth'], function() {
    Route::controller(\App\Http\Controllers\Pos\AuthController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::post('/shop/update', 'update');
        });
    });
});

Route::group(['prefix' => 'banner'], function () {
    Route::controller(\App\Http\Controllers\Pos\BannerShopController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::get('/delete/{id}', 'delete');
            Route::post('/update-status/{id}', 'update_status');
        });
    });
});

Route::group(['prefix' => 'customer'], function () {
    Route::controller(\App\Http\Controllers\Pos\CustomerController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::post('/delete/{id}', 'delete');
        });
    });
});

//Route::group(['prefix' => 'customer-group'], function () {
//    Route::controller(CustomerGroupController::class)->group(function () {
//        Route::middleware(['auth:sanctum', 'permission_shop'])->group(function () {
//            Route::get('/', 'index');
//            Route::post('/', 'store');
//            Route::post('/{id}', 'update');
//            Route::post('/delete/{id}', 'delete');
//        });
//    });
//});

Route::group(['prefix' => 'product'], function () {
    Route::controller(\App\Http\Controllers\Pos\ProductController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::post('/update-price', 'update_price');
            Route::post('/update-stock', 'update_stock');
            Route::get('/history-stock', 'history_stock');
            Route::post('/import-bellhome', 'import_bellhome');
            Route::post('/{id}', 'update');
            Route::get('/delete/{id}', 'delete');
            Route::post('/draft/{id}', 'update_draft');
        });
    });
});
Route::group(['prefix' => 'coupon'], function() {
    Route::controller(\App\Http\Controllers\Partner\CouponController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::get('/delete/{id}', 'delete');
            Route::post('/update-status/{id}', 'update_status');
        });
    });
});

Route::group(['prefix' => 'attribute'], function() {
    Route::controller(\App\Http\Controllers\Pos\ProductAttributeController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_shop'])->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::post('/delete/{id}', 'delete');
        });
    });
});

Route::group(['prefix' => 'review'], function() {
    Route::controller(\App\Http\Controllers\Pos\ReviewController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_shop'])->group(function () {
            Route::get('/', 'index');
            Route::post('/{id}', 'show');
            Route::post('/reply/{id}', 'reply');
        });
    });
});
//Route::group(['prefix' => 'product-category'], function() {
//    Route::controller(ProductCategoryController::class)->group(function () {
//        Route::middleware(['auth:sanctum', 'permission_shop'])->group(function () {
//            Route::get('/', 'index');
//            Route::post('/', 'store');
//            Route::post('/{id}', 'update');
//        });
//    });
//});

Route::group(['prefix' => 'orders'], function () {
    Route::controller(OrderController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/', 'index');
            Route::get('/product', 'index_product');
            Route::get('/customer', 'index_customer');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
//            Route::post('cancel/{id}', 'cancel');
//            Route::post('payment/{id}', 'payment');
            Route::post('update_status/{id}', 'update_status');
            Route::post('update_status_payment/{id}', 'update_status_payment');
        });
    });
});

//Route::group(['prefix' => 'shop'], function () {
//    Route::controller(ShopController::class)->group(function () {
//        Route::middleware('auth:sanctum')->group(function () {
//            Route::get('/', 'index');
//            Route::get('/payment-method', 'payment_method');
//        });
//    });
//});

Route::group(['prefix' => 'report'], function () {
    Route::controller(\App\Http\Controllers\Pos\ReportController::class)->group(function () {
        Route::middleware(['auth:sanctum', 'permission_partner'])->group(function () {
            Route::get('/profit', 'profit');
            Route::get('/revenue-transaction', 'revenue_transaction');
            Route::get('/status-order', 'status_order');
            Route::get('/inventory', 'inventory');
            Route::get('/inventory-day', 'inventory_day');
            Route::get('/revenue-day', 'revenue_day');
        });
    });
});

//Route::group(['prefix' => 'transactions'], function () {
//    Route::controller(TransactionController::class)->group(function () {
//        Route::middleware(['auth:sanctum', 'permission_shop'])->group(function () {
//            Route::get('/', 'index');
//        });
//    });
//});

//Route::group(['prefix' => 'notification'], function() {
//    Route::controller(CronjobController::class)->group(function () {
//        Route::get('/send', 'pos_notification');
//    });
//    Route::controller(NotificationController::class)->group(function () {
//        Route::middleware('auth:sanctum')->group(function () {
//            Route::get('/', 'index');
//            Route::get('/{id}', 'show')->where('id', '[0-9]+');
//            Route::post('/read', 'read');
//            Route::post('/delete', 'delete');
//        });
//    });
//});
//
//Route::controller(CronjobController::class)->group(function () {
//    Route::get('/job/shop-report-day', 'shop_report_day');
//});
