<?php

namespace Modules\Group\Repositories;

use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupAdmin;
use Modules\Group\Entities\Membership;
use Modules\Group\Repositories\Interfaces\GroupAdminRepositoryInterface;
use Modules\User\Entities\User;

class GroupAdminRepository implements GroupAdminRepositoryInterface
{

    public function getAdmins(Group $group)
    {
        return $group->admins;
    }

    public function assignAsAdmin(Group $group, User $user)
    {
        $membership = Membership::query()->approved()->where(['user_id' => $user->id, 'group_id' => $group->id])->first();

        if ($membership) {
            return GroupAdmin::query()->create(['membership_id' => $membership->id]);
        }

        return null;
    }

    public function updateAdminPermissions(GroupAdmin $groupAdmin, array $permissions)
    {
        return $groupAdmin->update($permissions);
    }

    public function dischargeAdmin(GroupAdmin $groupAdmin)
    {
        return $groupAdmin->delete();
    }
}