<?php

use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\BannerController;

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

