<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\DatabaseNotification;
use Modules\User\Http\Requests\GetNotificationsRequest;
use Modules\User\Transformers\NotificationResource;

class NotificationController extends Controller
{
    public function index(GetNotificationsRequest $request): AnonymousResourceCollection
    {
        $user = auth()->user();

        $notifications = $request->has('per_page') ?
            $user->notifications()->latest()->paginate($request->input('per_page')) :
            $user->notifications()->latest()->get();

        return NotificationResource::collection($notifications);
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return $this->success();
    }
}
