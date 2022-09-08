<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Group\Http\Controllers\BlackListController;
use Modules\Group\Http\Controllers\GroupAdminController;
use Modules\Group\Http\Controllers\GroupController;
use Modules\Group\Http\Controllers\MembershipController;

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

Route::middleware(['auth:sanctum', 'verified'])->prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'getGroups']);
    Route::get('/mine', [GroupController::class, 'getMyGroups']);
    Route::get('/categories', [GroupController::class, 'groupCategories']);
    Route::post('/', [GroupController::class, 'createGroup']);
    Route::get('/{group}', [GroupController::class, 'showGroup']);
    Route::put('/{group}', [GroupController::class, 'updateGroup']);
    Route::delete('/{group}', [GroupController::class, 'deleteGroup']);

    Route::get('/{group}/members/approved', [MembershipController::class, 'groupApprovedMembers']);
    Route::get('/{group}/members/pending', [MembershipController::class, 'groupPendingMembers']);

    Route::post('/{group}/accept/{user}', [MembershipController::class, 'acceptMember']);
    Route::post('/{group}/reject/{user}', [MembershipController::class, 'rejectMember']);
    Route::post('/{group}/remove/{user}', [MembershipController::class, 'removeMember']);

    Route::post('/{group}/join', [MembershipController::class, 'joinGroup']);
    Route::post('/{group}/leave', [MembershipController::class, 'leaveGroup']);

    Route::get('/{group}/admins', [GroupAdminController::class, 'index']);
    Route::post('/{group}/admins/{user}', [GroupAdminController::class, 'create']);
    Route::delete('/{group}/admins/{groupAdmin}', [GroupAdminController::class, 'remove']);
    Route::put('/{group}/admins/{groupAdmin}', [GroupAdminController::class, 'updatePermissions']);

    Route::get('/{group}/blacklist', [BlackListController::class, 'index']);
    Route::post('/{group}/blacklist/{user}', [BlackListController::class, 'addToBlackList']);
    Route::put('/{group}/blacklist/{blackListMember}', [BlackListController::class, 'updatePermissions']);
    Route::delete('/{group}/blacklist/{blackListMember}', [BlackListController::class, 'removeFromBlacklist']);
});