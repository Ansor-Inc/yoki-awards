<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Http\Controllers\Auth\AuthController;
use Modules\User\Http\Controllers\Auth\PasswordResetController;
use Modules\User\Http\Controllers\Auth\PhoneVerifyController;
use Modules\User\Http\Controllers\Auth\SocialAuthController;
use Modules\User\Http\Controllers\UserController;

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
Route::post('/verification/sendsms', [PhoneVerifyController::class, 'sendCode'])->middleware('throttle:3,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verification/verify', [PhoneVerifyController::class, 'verify']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::post('/reset-password/send-code', [PasswordResetController::class, 'sendCode'])->middleware('throttle:3,1');
    Route::post('/reset-password/verify', [PasswordResetController::class, 'verifyResetPassword']);
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);
});

Route::middleware(['auth:sanctum','verified'])->group(function () {
    Route::get('/me', [UserController::class, 'getMe']);
    Route::put('/me', [UserController::class, 'updateMe']);
    Route::post('/update/phone', [UserController::class, 'updatePhone']);
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
});


