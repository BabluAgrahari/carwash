<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApiController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\VehicleModelController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ShopOwnerController;
use App\Http\Controllers\Admin\LoginController;
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


Route::post('/login', [LoginController::class, 'store']);
Route::post('register', [LoginController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {


    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('getUserDetails', [LoginController::class, 'get_user']);

 Route::resource('category', CategoryController::class);
   
    // Route::group(['prefix' => 'categories'], function () {
    //     Route::get('get', [CategoryController::class, 'index']);
    //     Route::get('show/{id}', [CategoryController::class, 'show']);
    //     Route::post('create', [CategoryController::class, 'store']);
    //     Route::post('update/{category}',  [CategoryController::class, 'update']);
    //     Route::delete('delete/{category}',  [CategoryController::class, 'destroy']);
    // });
    // Route::apiResource('category',)

        Route::group(['prefix' => 'vehicleBrand'], function () {
        Route::get('get', [VehicleBrandController::class, 'index']);
        Route::get('show/{id}', [VehicleBrandController::class, 'show']);
        Route::post('create', [VehicleBrandController::class, 'store']);
        Route::post('update/{vehicleBrand}',  [VehicleBrandController::class, 'update']);
        Route::delete('delete/{vehicleBrand}',  [VehicleBrandController::class, 'destroy']);
    });
    Route::group(['prefix' => 'vehicleModel'], function () {
        Route::get('get', [VehicleModelController::class, 'index']);
        Route::get('show/{id}', [VehicleModelController::class, 'show']);
        Route::post('create', [VehicleModelController::class, 'store']);
        Route::post('update/{vehicleBrand}',  [VehicleModelController::class, 'update']);
        Route::delete('delete/{vehicleBrand}',  [VehicleModelController::class, 'destroy']);
    });
    Route::group(['prefix' => 'services'], function () {
        Route::get('get', [ServiceController::class, 'index']);
        Route::get('show/{id}', [ServiceController::class, 'show']);
        Route::post('create', [ServiceController::class, 'store']);
        Route::post('update/{service}',  [ServiceController::class, 'update']);
        Route::delete('delete/{service}',  [ServiceController::class, 'destroy']);
    });
    Route::group(['prefix' => 'shopOwner'], function () {
        Route::get('get', [ShopOwnerController::class, 'index']);
        Route::get('show/{id}', [ShopOwnerController::class, 'show']);
        Route::post('create', [ShopOwnerController::class, 'store']);
        Route::post('update/{shopOwner}',  [ShopOwnerController::class, 'update']);
        Route::delete('delete/{shopOwner}',  [ShopOwnerController::class, 'destroy']);
    });
});

Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');;
