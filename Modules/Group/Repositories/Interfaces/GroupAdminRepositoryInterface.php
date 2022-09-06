<?php

namespace Modules\Group\Repositories\Interfaces;

use App\Models\Group;
use App\Models\GroupAdmin;
use App\Models\User;

interface GroupAdminRepositoryInterface
{
    public function getAdmins(Group $group);

    public function assignAsAdmin(Group $group, User $user);

    public function updateAdminPermissions(GroupAdmin $groupAdmin, array $permissions);

    public function dischargeAdmin(GroupAdmin $groupAdmin);
}