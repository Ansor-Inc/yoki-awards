<?php

use Illuminate\Support\Facades\Route;

Route::get('/{inviteLink}', [\Modules\Group\Http\Controllers\GroupController::class, 'getByInviteLink'])->middleware(['auth:sanctum', 'verified']);
