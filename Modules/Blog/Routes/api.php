<?php

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
Route::get('/articles', [BlogController::class, 'index']);
Route::get('/articles/tags', [BlogController::class, 'tags']);
Route::get('/articles/{articleId}', [BlogController::class, 'show']);
Route::put('/articles/{articleId}', [BlogController::class, 'incrementViewsCount']);
