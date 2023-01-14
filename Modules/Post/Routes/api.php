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
Route::middleware(['auth:sanctum', 'verified', 'verified.device'])->group(function () {
    Route::get('/groups/{group}/posts', [GroupPostController::class, 'index']);
    Route::post('/groups/{group}/posts', [GroupPostController::class, 'create']);

    Route::prefix('posts')->group(function () {
        Route::get('/{post}', [GroupPostController::class, 'show']);
        Route::put('/{post}', [GroupPostController::class, 'update']);
        Route::delete('/{post}', [GroupPostController::class, 'delete']);
        Route::post('/{post}/toggleLike', [GroupPostController::class, 'toggleLike']);

        Route::get('/{post}/comments', [PostCommentController::class, 'index']);
        Route::post('/{post}/comments', [PostCommentController::class, 'create']);
        Route::put('/comments/{comment}', [PostCommentController::class, 'update']);
        Route::delete('/comments/{comment}', [PostCommentController::class, 'delete']);

        Route::post('/comments/{comment}/complain', [PostCommentController::class, 'complain']);
    });
});
