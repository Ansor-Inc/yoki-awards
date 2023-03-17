<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\User\Http\Requests\GetNotificationsRequest;
use Modules\User\Interfaces\NotificationRepositoryInterface;
use Modules\User\Transformers\NotificationResource;

class NotificationController
{
    public function __construct(private NotificationRepositoryInterface $notificationRepository)
    {
    }

    public function index(GetNotificationsRequest $request): AnonymousResourceCollection
    {
        return NotificationResource::collection(
            $this->notificationRepository->getNotifications(
                user: $request->user(),
                perPage: $request->input('per_page'))
        );
    }
}
