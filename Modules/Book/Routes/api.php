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
Route::get('/publishers/{publisher}', [PublisherController::class, 'show']);
Route::get('/publishers/{publisher}/books', [PublisherController::class, 'getPublisherBooks']);

Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{genre}/books', [GenreController::class, 'genreBooks']);

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/search', [BookController::class, 'search']);
    Route::get('/sections', [BookController::class, 'getBooksBySections']);

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/saved', [BookController::class, 'getSavedBooks']);
        Route::put('/{book}/bookmark', [BookController::class, 'bookmark'])->middleware('throttle:3,1');
        Route::put('/{book}/rate', [BookController::class, 'rate'])->middleware('throttle:3,1');
        Route::post('/{book}/complete', [BookController::class, 'markBookAsCompleted'])->middleware('throttle:2,1');
        Route::post('/{book}/comments', [BookCommentController::class, 'store'])->middleware('throttle:3,1');
    });

    Route::get('/{book}', [BookController::class, 'show']);
    Route::get('/{book}/variants', [BookController::class, 'getBookWithVariants']);
    Route::get('/{book}/similar', [BookController::class, 'getSimilarBooks']);
    Route::get('/{book}/comments', [BookCommentController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::put('/comments/{comment}', [BookCommentController::class, 'update'])->middleware('throttle:3,1');
    Route::delete('/comments/{comment}', [BookCommentController::class, 'destroy']);
});

