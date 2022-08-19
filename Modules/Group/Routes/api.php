<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Group\Http\Controllers\GroupController;

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

Route::middleware('auth:sanctum')->prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'getGroups']);
    Route::get('/mine', [GroupController::class, 'getMyGroups']);
    Route::post('/', [GroupController::class, 'createGroup']);
    Route::delete('/{id}', [GroupController::class, 'deleteGroup']);
});