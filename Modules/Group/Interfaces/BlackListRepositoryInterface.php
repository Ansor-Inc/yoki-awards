<?php

namespace Modules\Group\Interfaces;

use Modules\Group\Entities\BlackList;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

interface BlackListRepositoryInterface
{
    public function getBlackList(Group $group);

    public function addToBlackList(Group $group, User $user);

    public function removeFromBlackList(BlackList $blackListMember);

    public function updatePermissions(BlackList $blackListMember, array $permissions);
}
