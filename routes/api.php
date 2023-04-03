<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified', 'verified.device'])->group(function () {
    Route::post('/upload/image', [ImageUploadController::class, 'upload']);
});

Route::get('/banners', [BannerController::class, 'index']);
