<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\Authenticate;

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

Route::group(['prefix' => 'v1', 'middleware' => [Authenticate::class]], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('/create', [AccountController::class, 'create']);
        Route::post('/login', [AccountController::class, 'login'])->withoutMiddleware([Authenticate::class]);
        Route::get('/logout', [AccountController::class, 'logout']);
        Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->withoutMiddleware([Authenticate::class]);
        Route::get('/reset-password-token', [AccountController::class, 'resetPasswordToken'])->withoutMiddleware([Authenticate::class]);
    });
    
    /** User endpoint */
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'show']);
        Route::delete('/', [UserController::class, 'delete']);
        // Route::get('/orders', [UserController::class, 'listOrders']);
        Route::put('/edit', [UserController::class, 'edit']);
    }); 


    /** Order endpoint */
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/shipment-locator', [OrderController::class, 'shipmentLocator']);
    Route::get('orders/dashboard', [OrderController::class, 'dashboard']);
    Route::group(['prefix' => 'order'], function () {
        Route::post('create', [OrderController::class, 'create']);
        Route::get('{uuid}', [OrderController::class, 'show']);
        Route::put('{uuid}', [OrderController::class, 'update']);
        Route::delete('{uuid}', [OrderController::class, 'delete']);
        Route::get('{uuid}/download', [OrderController::class, 'download']);
    });


    /** Payment endpoint */
    Route::get('payments', [PaymentController::class, 'index']);
    Route::group(['prefix' => 'payment'], function () {
        Route::post('create', [PaymentController::class, 'create']);
        Route::get('{uuid}', [PaymentController::class, 'show']);
        Route::put('{uuid}', [PaymentController::class, 'update']);
        Route::delete('{uuid}', [PaymentController::class, 'delete']);
    });

    /** Order status endpoint */
    Route::get('order-statuses', [OrderStatusController::class, 'index']);
    Route::group(['prefix' => 'order-status'], function () {
        Route::post('create', [OrderStatusController::class, 'create']);
        Route::get('{uuid}', [OrderStatusController::class, 'show']);
        Route::put('{uuid}', [OrderStatusController::class, 'update']);
        Route::delete('{uuid}', [OrderStatusController::class, 'delete']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
