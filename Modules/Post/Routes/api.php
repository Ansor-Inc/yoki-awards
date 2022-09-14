<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\GroupPostController;
use Modules\Post\Http\Controllers\PostCommentController;

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
    Route::get('/groups/{group}/posts', [GroupPostController::class, 'index']);
    Route::post('/groups/{group}/posts', [GroupPostController::class, 'create']);

    Route::prefix('posts')->group(function () {
        Route::put('/{post}', [GroupPostController::class, 'update']);
        Route::delete('/{post}', [GroupPostController::class, 'delete']);
        Route::post('/{post}/toggleLike', [GroupPostController::class, 'toggleLike']);

        Route::get('/{post}/comments', [PostCommentController::class, 'index']);
        Route::post('/{post}/comments', [PostCommentController::class, 'create']);
        Route::put('/post-comments/{comment}', [PostCommentController::class, 'update']);
        Route::delete('/post-comments/{comment}', [PostCommentController::class, 'delete']);
    });
});
