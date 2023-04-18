<?php


use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\BannerController;
use Modules\Content\Http\Controllers\PopupController;

Route::prefix('content')->group(function () {
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/popup', [PopupController::class, 'index']);
});

Route::get('/banners', [BannerController::class, 'index']);
