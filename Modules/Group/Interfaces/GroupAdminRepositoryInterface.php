<?php

namespace Modules\Group\Interfaces;

use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupAdmin;
use Modules\User\Entities\User;

interface GroupAdminRepositoryInterface
{
    public function getAdmins(Group $group);

    public function assignAsAdmin(Group $group, User $user);

    public function updateAdminPermissions(GroupAdmin $groupAdmin, array $permissions);

    public function dischargeAdmin(GroupAdmin $groupAdmin);
}
