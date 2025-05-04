<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
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
Route::put('/profile', [ProfileController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{hotelId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist.list');
});


Route::get('/hotels', [HotelController::class, 'index']);
Route::post('/hotels', [HotelController::class, 'store']);
Route::get('/hotels/{id}', [HotelController::class, 'show']);
Route::put('/hotels/{id}', [HotelController::class, 'update']);
Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);
Route::get('/hotels/popular', [HotelController::class, 'popular'])->name('hotels.popular');
Route::get('/hotels/search', [HotelController::class, 'search'])->name('hotels.search');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});

Route::get('/rooms', [RoomController::class, 'index']);
Route::post('/rooms', [RoomController::class, 'store']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);
Route::put('/rooms/{id}', [RoomController::class, 'update']);
Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

Route::get('/hotels/{hotelId}/rooms', [RoomController::class, 'getRoomsByHotel']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users/{userId}/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::get('/users/{userId}', [ProfileController::class, 'getUserData']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'createReview']);
});
Route::get('/hotels/{hotelId}/reviews', [ReviewController::class, 'getReviewByHotel']);
