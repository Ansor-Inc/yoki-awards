<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\ArticleCommentController;
use Modules\Blog\Http\Controllers\ArticleController;
use Modules\Blog\Http\Controllers\UserArticleController;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/tags', [ArticleController::class, 'tags']);
    Route::get('/{article}', [ArticleController::class, 'show']);
    Route::put('/{article}', [ArticleController::class, 'incrementViewsCount']);

    Route::middleware('auth')->group(function () {
        Route::post('/', [UserArticleController::class, 'store']);
        Route::put('/{articleWithoutScopes}/edit', [UserArticleController::class, 'update']);
        Route::delete('/{articleWithoutScopes}', [UserArticleController::class, 'destroy']);
        Route::post('/draft', [UserArticleController::class, 'saveToDraft']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/{article}/comments', [ArticleCommentController::class, 'index']);
        Route::post('/{article}/comments', [ArticleCommentController::class, 'store']);
    });
});

Route::prefix('me')->middleware('auth')->group(function () {
    Route::get('/articles', [UserArticleController::class, 'index']);
    Route::get('/articles/{articleWithoutScopes}', [UserArticleController::class, 'show']);
});


