<?php

namespace Modules\Group\Entities\Traits;

use Modules\Group\Entities\Membership;
use function auth;
use function collect;

trait HasGroupPermissions
{
    public function currentUserPermissionStatus()
    {
        return $this->hasOne(Membership::class)
            ->where('memberships.user_id', auth()->id())
            ->leftJoin('group_admins', 'memberships.id', '=', 'group_admins.membership_id')
            ->leftJoin('black_list', 'memberships.id', '=', 'black_list.membership_id');
    }
    
    public function getCurrentUserPermissionsAttribute()
    {
        $permissions = [
            'can_update_group' => false,
            'can_create_post' => false,
            'can_add_to_blacklist' => false,
            'can_comment' => true,
            'can_see_post' => true,
        ];

        if ((int)$this->owner_id === (int)auth()->id()) {
            return array_map(fn() => true, $permissions);
        }

        $userPermissions = collect($this->currentUserPermissionStatus->toArray())
            ->only(collect($permissions)->keys()->toArray())
            ->whereNotNull()
            ->map(fn($permission) => (bool)$permission)
            ->toArray();

        return array_merge($permissions, $userPermissions);
    }

}