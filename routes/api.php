<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => [], 'prefix' => 'v1'], function () {
    /** User endpoint */

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'show']);
        Route::delete('/', [UserController::class, 'delete']);
        // Route::get('/orders', [UserController::class, 'listOrders']);
        Route::put('/edit', [UserController::class, 'edit']);
        Route::post('/login', [UserController::class, 'login']);
        Route::get('/logout', [UserController::class, 'logout']);
        Route::post('/create', [UserController::class, 'create']);
        Route::get('/forgot-password', [UserController::class, 'forgotPassword']);
        Route::get('/reset-password-token', [UserController::class, 'resetPasswordToken']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
