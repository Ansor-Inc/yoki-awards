<?php

namespace Modules\Group\Repositories\Interfaces;

use App\Models\BlackList;
use App\Models\Group;
use App\Models\User;

interface BlackListRepositoryInterface
{
    public function getBlackList(Group $group);

    public function addToBlackList(Group $group, User $user);

    public function removeFromBlackList(BlackList $blackListMember);

    public function updatePermissions(BlackList $blackListMember, array $permissions);
}