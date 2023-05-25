<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\BannerController;
use Modules\Content\Http\Controllers\PopupController;
use Modules\Content\Http\Controllers\WysiwygMediaUploadController;

Route::prefix('content')->group(function () {
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/popup', [PopupController::class, 'index']);
    Route::post('/wysiwyg-media', [WysiwygMediaUploadController::class, 'upload'])->middleware('auth');
});
