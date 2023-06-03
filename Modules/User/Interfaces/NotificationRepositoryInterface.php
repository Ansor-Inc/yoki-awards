<?php

namespace Modules\User\Interfaces;

use ArrayAccess;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getNotifications(Authenticatable $user, ?int $perPage): ArrayAccess|LengthAwarePaginator;
}
