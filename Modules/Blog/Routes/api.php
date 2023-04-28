<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\ArticleCommentController;
use Modules\Blog\Http\Controllers\ArticleController;

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
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/tags', [ArticleController::class, 'tags']);
Route::get('/articles/{articleId}', [ArticleController::class, 'show']);
Route::put('/articles/{articleId}', [ArticleController::class, 'incrementViewsCount']);

Route::middleware('auth')->group(function () {
    Route::get('/me/articles', [ArticleController::class, 'getUserArticles']);
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::post('/articles/{articleId}/publish', [ArticleController::class, 'publish']);
    Route::post('/articles/{article}/comments', [ArticleCommentController::class, 'store']);
});

Route::get('/articles/{article}/comments', [ArticleCommentController::class, 'index']);
