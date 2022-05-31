<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApiController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\VehicleModelController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ShopOwnerController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TimeSlapController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PassbookController;
use App\Http\Controllers\Admin\PayHistoryController;
use App\Http\Controllers\App\AppController as Apps;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\VendorController;
use App\Http\Controllers\App\TimeSlapController as TimeSlap;
use App\Http\Controllers\App\BookingController as Booking;
use App\Http\Controllers\App\ProfileController as Profile;
use App\Http\Controllers\App\LoginController as Login;
use App\Http\Controllers\App\ReviewController;

Route::post('/login', [LoginController::class, 'store']);
Route::post('register', [LoginController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('getUserDetails', [LoginController::class, 'get_user']);

    Route::resource('category', CategoryController::class);

    Route::resource('vehicle-brand', VehicleBrandController::class);
    Route::resource('vehicle-modal', VehicleModelController::class);
    Route::get('veh-modal/{brand_id}', [VehicleModelController::class, 'vehicleModal']);

    Route::resource('services', ServiceController::class);
    Route::get('vendor-services', [ServiceController::class, 'vendorServices']);

    Route::resource('shop-owner', ShopOwnerController::class);
    Route::post('assign-services/{id}', [ShopOwnerController::class, 'assignServices']);

    Route::resource('driver', DriverController::class);

    Route::resource('time-slap', TimeSlapController::class);
    Route::post('disable-services', [TimeSlapController::class, 'disableServices']);

    Route::resource('booking', BookingController::class);
    Route::resource('passbook', PassbookController::class);

    Route::resource('pay-history', PayHistoryController::class);

    Route::get('user', [UserController::class, 'index']);
});


//for mobile application
Route::post('app/send-otp', [Login::class, 'sendOtp']);
Route::post('app/verify-otp', [Login::class, 'verifyOtp']);
Route::post('app/resend-otp', [Login::class, 'resendOtp']);

Route::group(['prefix' => 'app', 'middleware' => 'appAuth'], function () {
    Route::get('service', [HomeController::class, 'serviceList']);
    Route::get('vendor', [HomeController::class, 'vendorList']);
    Route::get('vendor-list/{id}', [VendorController::class, 'vendorList']);
    Route::get('vendor-detail/{id}', [VendorController::class, 'vendorDetails']);
    Route::get('vehicle-brand', [HomeController::class, 'vehicleBrand']);
    Route::get('service-type/{type}', [HomeController::class, 'serviceWithType']);

    Route::get('time-slaps/{vendor_id}', [TimeSlap::class, 'index']);
    Route::post('time-slaps/{id}/{key}', [TimeSlap::class, 'updateServices']);

    Route::get('profile-detail', [Profile::class, 'userData']);
    Route::post('profile-update', [Profile::class, 'update']);
    Route::post('update-location', [Profile::class, 'updateLocation']);

    Route::post('save-booking', [Booking::class, 'store']);

    Route::get('review', [ReviewController::class, 'index']);
    Route::post('review', [ReviewController::class, 'store']);
});


Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');;
