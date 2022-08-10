<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Http\Controllers\Auth\AuthController;
use Modules\User\Http\Controllers\Auth\PasswordResetController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-verify', [AuthController::class, 'registerVerify']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::post('/reset-password-verify', [PasswordResetController::class, 'verifyResetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'getMe']);
    Route::put('/me', [UserController::class, 'updateMe']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/auth/redirect', [AuthController::class,]);

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    // $user->token
});
