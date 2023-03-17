<?php

namespace Modules\User\Repositories;

use ArrayAccess;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\User\Interfaces\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{

    public function getNotifications(Authenticatable $user, ?int $perPage): ArrayAccess|LengthAwarePaginator
    {
        return $perPage ? $user->notifications()->paginate($perPage) : $user->notifications()->get();
    }

    public function markNotificationAsRead(int $notificationId): void
    {
        // TODO: Implement markNotificationAsRead() method.
    }
}
