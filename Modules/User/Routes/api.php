<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AppealController;
use Modules\User\Http\Controllers\Auth\AuthController;
use Modules\User\Http\Controllers\Auth\PasswordResetController;
use Modules\User\Http\Controllers\Auth\PhoneVerifyController;
use Modules\User\Http\Controllers\NotificationController;
use Modules\User\Http\Controllers\AccountController;

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

Route::middleware(['auth:sanctum', 'verified.device'])->group(function () {
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

Route::middleware(['auth:sanctum', 'verified.device'])->group(function () {
    Route::get('/me', [AccountController::class, 'getMe']);
    Route::get('/me/balance', [AccountController::class, 'getBalance']);
    Route::post('/me/apply-to-be-blogger', [AppealController::class, 'applyToBeBlogger']);

    Route::middleware('verified')->group(function () {
        Route::put('/me', [AccountController::class, 'updateMe']);
        Route::delete('/me', [AccountController::class, 'destroy']);
        Route::post('/update/phone', [AccountController::class, 'updatePhone']);
        Route::post('/update/avatar', [AccountController::class, 'updateAvatar']);

        Route::get('/help', [AppealController::class, 'index']);
        Route::post('/help', [AppealController::class, 'submit'])->middleware('throttle:30,1');
    });


    Route::post('/fcm-token', [AccountController::class, 'setFcmToken']);
    Route::get('/notifications', [NotificationController::class, 'index']);
});
