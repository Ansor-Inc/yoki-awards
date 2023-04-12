<?php


use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\PopupController;


Route::prefix('content')->group(function () {
    Route::get('/popup', [PopupController::class, 'index']);
});
