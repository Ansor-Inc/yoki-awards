<?php

use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/upload/image', [ImageUploadController::class, 'upload']);
    Route::post('/help', [\App\Http\Controllers\AppealController::class, 'submit'])->middleware('throttle:30,1');
});
