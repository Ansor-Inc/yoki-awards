<?php

use Illuminate\Support\Facades\Route;
use Modules\Reaction\Http\Controllers\DisLikeController;
use Modules\Reaction\Http\Controllers\LikeController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/likes/toggle', [LikeController::class, 'toggle']);
    Route::post('/dislikes/toggle', [DisLikeController::class, 'toggle']);
});
