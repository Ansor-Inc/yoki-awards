<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{genre}/books', [GenreController::class, 'genreBooks']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/books/{book}/bookmark', [BookController::class, 'bookmark']);
    Route::put('/books/{book}/rate', [BookController::class, 'rate']);
});
