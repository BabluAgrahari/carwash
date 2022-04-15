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


use App\Http\Controllers\App\AppController as Apps;
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
    Route::resource('vehicleBrand', VehicleBrandController::class);
    Route::resource('vehicleModel', VehicleModelController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('shopOwner', ShopOwnerController::class);

});

//for mobile application
Route::get('service', [Apps::class, 'index']);


Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');;
