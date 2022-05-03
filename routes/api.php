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
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\LoginController as Login;



Route::post('/login', [LoginController::class, 'store']);
Route::post('register', [LoginController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('getUserDetails', [LoginController::class, 'get_user']);

    Route::resource('category', CategoryController::class);

    Route::resource('vehicle-brand', VehicleBrandController::class);
    Route::resource('vehicle-modal', VehicleModelController::class);

    Route::resource('services', ServiceController::class);
    Route::resource('shop-owner', ShopOwnerController::class);
});

//for mobile application
Route::post('send-otp', [Login::class, 'sendOtp']);
Route::post('verify-otp', [Login::class, 'verifyOtp']);
Route::post('resend-otp', [Login::class, 'resendOtp']);

Route::group(['prefix' => 'app', 'middleware' => 'appAuth'], function () {
    Route::get('service', [HomeController::class, 'serviceList']);
});


Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');;
