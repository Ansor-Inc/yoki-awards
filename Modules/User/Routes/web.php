<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\SocialAuthController;

Route::middleware(['guest:sanctum'])->group(function () {
    Route::get('/auth/{driver}/redirect', [SocialAuthController::class, 'redirect']);
    Route::get('/auth/{driver}/callback', [SocialAuthController::class, 'callback']);
});
