<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\AuthController;

Route::post('signup', [AuthController::class, 'signUp']);
Route::post('signin', [AuthController::class, 'signIn']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::post('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
