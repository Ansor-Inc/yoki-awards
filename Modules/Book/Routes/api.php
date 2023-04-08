<?php

use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookCommentController;
use Modules\Book\Http\Controllers\BookController;
use Modules\Book\Http\Controllers\BookQuoteController;
use Modules\Book\Http\Controllers\GenreController;
use Modules\Book\Http\Controllers\PublisherController;

Route::prefix('publishers')->group(function () {
    Route::get('/', [PublisherController::class, 'index']);
    Route::get('/{publisher}', [PublisherController::class, 'show']);
    Route::get('/{publisher}/books', [PublisherController::class, 'getPublisherBooks']);
});

Route::prefix('genres')->group(function () {
    Route::get('/', [GenreController::class, 'index']);
    Route::get('/{genre}/books', [GenreController::class, 'genreBooks']);
});

Route::prefix('books')->group(callback: function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/search', [BookController::class, 'search']);
    Route::get('/sections', [BookController::class, 'getBooksBySections']);

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/saved', [BookController::class, 'getSavedBooks']);
        Route::put('/{book}/bookmark', [BookController::class, 'bookmark'])->middleware('throttle:10,1');
        Route::put('/{book}/rate', [BookController::class, 'rate'])->middleware('throttle:10,1');
        Route::post('/{book}/complete', [BookController::class, 'markBookAsCompleted'])->middleware('throttle:10,1');
        Route::post('/{book}/comments', [BookCommentController::class, 'store'])->middleware('throttle:10,1');
    });

    Route::get('/{book}', [BookController::class, 'show']);
    Route::get('/{book}/variants', [BookController::class, 'getBookWithVariants']);
    Route::get('/{book}/similar', [BookController::class, 'getSimilarBooks']);
    Route::get('/{book}/comments', [BookCommentController::class, 'index']);

    //Quote routes:
    Route::get('/{book}/quotes', [BookQuoteController::class, 'index'])->whereNumber('book');

    Route::post('/{book}/quotes', [BookQuoteController::class, 'store'])
        ->middleware('auth:sanctum')
        ->whereNumber('book');
});

Route::prefix('comments')->middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::put('/{comment}', [BookCommentController::class, 'update'])->middleware('throttle:10,1');
    Route::delete('/{comment}', [BookCommentController::class, 'destroy']);
});

Route::get('/quotes', [BookQuoteController::class, 'getAllQuotes']);
Route::put('/quotes/{quote}', [BookQuoteController::class, 'update'])
    ->middleware('auth:sanctum')
    ->whereNumber('book');
