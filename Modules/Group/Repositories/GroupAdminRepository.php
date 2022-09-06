<?php

namespace Modules\Group\Repositories;

use App\Models\Group;
use App\Models\GroupAdmin;
use App\Models\Membership;
use App\Models\User;
use Modules\Group\Repositories\Interfaces\GroupAdminRepositoryInterface;

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
            GroupAdmin::query()->create(['membership_id' => $membership->id]);
        }
    }

    public function updateAdminPermissions(GroupAdmin $groupAdmin, array $permissions)
    {
        $groupAdmin->update($permissions);
    }

    public function dischargeAdmin(GroupAdmin $groupAdmin)
    {
        $groupAdmin->delete();
    }
}