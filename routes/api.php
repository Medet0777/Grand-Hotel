<?php

use App\Http\Controllers\Api\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\AuthController;

Route::post('signup', [AuthController::class, 'signUp']);
Route::post('signin', [AuthController::class, 'signIn']);


Route::post('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register/verify-otp', [AuthController::class, 'verifyRegistrationOtp']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');


    Route::delete('/wishlist/remove/{hotelId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');


    Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist.list');
});
