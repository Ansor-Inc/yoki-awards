<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\AuthController;
use Modules\User\Http\Controllers\Auth\PasswordResetController;

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

Route::get('/articles', [BlogController::class, 'index']);
Route::get('/articles/tags', [BlogController::class, 'tags']);
Route::get('/articles/{article}', [BlogController::class, 'show']);
Route::put('/articles/{article}', [BlogController::class, 'incrementViewsCount']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/upload', [FileUploadController::class, 'upload']);
});
