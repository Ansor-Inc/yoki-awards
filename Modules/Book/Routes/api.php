<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookCommentController;
use Modules\Book\Http\Controllers\BookController;
use Modules\Book\Http\Controllers\GenreController;
use Modules\Book\Http\Controllers\PublisherController;

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

Route::get('/publishers', [PublisherController::class, 'index']);
Route::get('/publishers/{id}', [PublisherController::class, 'show']);
Route::get('/publishers/{id}/books', [PublisherController::class, 'publisherBooks']);

Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{genre}/books', [GenreController::class, 'genreBooks']);

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{book}', [BookController::class, 'show']);
    Route::get('/{book}/comments', [BookCommentController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/{book}/bookmark', [BookController::class, 'bookmark']);
        Route::put('/{book}/rate', [BookController::class, 'rate']);
        Route::post('/{book}/comments', [BookCommentController::class, 'store']);

    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/comments/{comment}', [BookCommentController::class, 'update']);
    Route::delete('/comments/{comment}', [BookCommentController::class, 'destroy']);

});

