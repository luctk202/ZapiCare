<?php

use App\Http\Controllers\Api\GeneralManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductAttributeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\FlashSaleController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CronjobController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Webhook\SmsController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\TestResultController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

///*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});*/

Route::get('/province', function (Request $request) {
    try {
        return response()->json(['data' => 123321], 200);
    } catch (Exception $e) {
        // Xử lý lỗi và trả về response lỗi
        return response()->json(['error' => 'Lỗi khi lấy danh sách tỉnh', 'message' => $e->getMessage()], 500);
    }
});

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('signup', 'signup');
        Route::post('password/reset', 'resetPassword');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('logout', 'logout');
            Route::get('user', 'user');
            Route::post('user/update', 'update');
            Route::post('user/delete', 'destroy');
            Route::post('password/change', 'changePassword');
        });
    });
});

Route::group(['prefix' => 'address'], function () {
    Route::controller(AddressController::class)->group(function () {
        Route::get('/province', 'province');
        Route::get('/district', 'district');
        Route::get('/ward', 'ward');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::post('/delete/{id}', 'delete');
            Route::post('set-default/{id}', 'set_default');
        });
    });
});
Route::group(['prefix' => 'reviews'], function () {
    Route::controller(\App\Http\Controllers\Api\ReviewController::class)->group(function () {
        Route::get('/product/{id}', 'index');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/submit', 'submit');
        });
    });
});

Route::group(['prefix' => 'product'], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show')->where('id', '[0-9]+');
        });
    });
});


Route::group(['prefix' => 'product-category'], function () {
    Route::controller(ProductCategoryController::class)->group(function () {
        //Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', 'index');
        //});
    });
});

Route::group(['prefix' => 'brand'], function () {
    Route::controller(BrandController::class)->group(function () {
        //Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', 'index');
        Route::get('/get-by-category', 'get_by_category');
        //});
    });
});

Route::group(['prefix' => 'filter'], function () {
    Route::controller(FilterController::class)->group(function () {
        //Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', 'index');
        Route::get('/get-by-category', 'get_by_category');
        //});
    });
});

Route::group(['prefix' => 'fee'], function () {
    Route::controller(FeeController::class)->group(function () {
        //Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', 'index');
        // });
    });
});

Route::group(['prefix' => 'product-attribute'], function () {
    Route::controller(ProductAttributeController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', 'index');
        });
    });
});
Route::group(['prefix' => 'analysis'], function () {
    Route::controller(\App\Http\Controllers\Api\AnalysisController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/count', 'count');
        Route::post('/cancel/{id}', 'cancel');
        Route::get('/logs/{id}', 'logs');
        Route::get('/{id}', 'show');
    });
});

Route::group(['prefix' => 'orders'], function () {
    Route::controller(OrderController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', 'store');
            Route::get('/', 'index');
            Route::get('/count', 'count');
            Route::post('/cancel/{id}', 'cancel');
            Route::get('/logs/{id}', 'logs');
            Route::get('/{id}', 'show');
        });
    });
});

Route::group(['prefix' => 'banner'], function () {
    Route::controller(BannerController::class)->group(function () {
        Route::get('/', 'index');
    });
});

Route::group(['prefix' => 'flash-sale'], function () {
    Route::controller(FlashSaleController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });
});

Route::group(['prefix' => 'shop'], function () {
    Route::controller(ShopController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });
});

Route::group(['prefix' => 'coupon'], function () {
    Route::controller(CouponController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/apply-shop', 'applyShop');
            Route::get('/apply', 'apply');
        });
        Route::get('/', 'index');
        Route::get('/{code}', 'show');
    });
});

Route::group(['prefix' => 'cart'], function () {
    Route::controller(CartController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/update-num/{id}', 'update_num');
            Route::post('/remove/{id}', 'remove');
            Route::post('/delete', 'delete');
        });
    });
});

Route::group(['prefix' => 'notification'], function () {
    Route::controller(CronjobController::class)->group(function () {
        Route::get('/send', 'notification');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show')->where('id', '[0-9]+');
            Route::get('/count-item-type', 'count_by_item_type');
            Route::get('/count', 'count');
            Route::post('/read', 'read');
            Route::post('/delete', 'delete');
        });
    });
});


Route::controller(TestController::class)->group(function () {
    Route::get('test', 'index');
    Route::get('test/update_wallet', 'update_wallet');
    Route::get('test/update_payment', 'update_payment');
    Route::get('test/update_cancel', 'update_cancel');
    Route::get('test/update_export', 'update_export');
    Route::get('test/update_ward', 'update_ward');
    Route::get('test/convert_wallet', 'convert_wallet');
    Route::get('test/update_profit', 'update_profit');
});

Route::post('hook/forward-sms', [SmsController::class, 'index'])->name('sms');
Route::group(['prefix' => 'pos'], function () {
    require 'pos.php';
});
Route::group(['prefix' => 'partner'], function () {
    require 'partner.php';
});

//lực upload
Route::group(['prefix' => 'upload_file'], function () {
    Route::controller(FileUploadController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/search-user-contact',[FileUploadController::class,'searchUserContact']);
            Route::post('/upload_file', [FileUploadController::class, 'uploadZip']);
            Route::post('/add-user-contact', [FileUploadController::class, 'addUserContact']);
            Route::post('/link-user-contact',[FileUploadController::class,'linkUserContact']);
//            Route::post('/link-user', [FileUploadController::class, 'linkUser']);
        });
    });
});


Route::group(['prefix' => 'test_result'], function () {
    Route::controller(TestResultController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/result/', [TestResultController::class, 'result']);
            Route::post('/follow/{id}',[TestResultController::class,'follow']);
//            Route::get('/result-follow',[TestResultController::class,'resultFollow']);
        });
    });
});


Route::group(['prefix' => 'general_managements'], function () {
    Route::get('/', [GeneralManagementController::class, 'index']);
    Route::post('/store', [GeneralManagementController::class, 'store']);
    Route::get('/{slug}', [GeneralManagementController::class, 'show']);
    Route::put('/update/{generalManagementId}', [GeneralManagementController::class, 'update']);
    Route::delete('/delete/{generalManagementId}', [GeneralManagementController::class, 'delete']);
});


Route::group(['prefix' => 'diseases'], function () {
    Route::controller(\App\Http\Controllers\Api\DiseaseController::class)->group(function () {
        Route::get('/', 'index');
    });
});

Route::group(['prefix' => 'contacts'], function () {
    Route::controller(\App\Http\Controllers\Api\ContactController::class)->group(function () {
        Route::get('/', 'index');
    });
});

Route::group(['prefix' => 'contacts-form'], function () {
    Route::controller(\App\Http\Controllers\Api\ContactFormController::class)->group(function () {
        Route::post('/', 'store');
    });
});
