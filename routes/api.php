<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('signup', [AuthController::class, 'signUp']);
Route::post('signin', [AuthController::class, 'signIn']);
Route::post('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register/verify-otp', [AuthController::class, 'verifyRegistrationOtp']);
Route::put('/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{hotelId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist.list');
});


//Route::apiResource('hotels', HotelController::class);
Route::post('/hotels/check',[HotelController::class, 'store']);
Route::get('/hotels/popular', [HotelController::class, 'popular'])->name('hotels.popular');
Route::get('/hotels/search', [HotelController::class, 'search'])->name('hotels.search');


Route::apiResource('rooms', RoomController::class);
Route::get('/hotels/{hotel_id}/rooms', [RoomController::class, 'getRoomsByHotel']);
