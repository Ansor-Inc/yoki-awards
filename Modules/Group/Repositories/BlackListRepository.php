<?php

namespace Modules\Group\Repositories;

use Modules\Group\Entities\BlackList;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\Membership;
use Modules\Group\Interfaces\BlackListRepositoryInterface;
use Modules\User\Entities\User;

class BlackListRepository implements BlackListRepositoryInterface
{

    public function getBlackList(Group $group)
    {
        return $group->blackListMembers;
    }

    public function addToBlackList(Group $group, User $user)
    {
        $membership = Membership::query()->approved()->where(['group_id' => $group->id, 'user_id' => $user->id])->first();

        if ($membership) {
            return BlackList::query()->create(['membership_id' => $membership->id]);
        }

        return null;
    }

    public function removeFromBlackList(BlackList $blackListMember)
    {
        return $blackListMember->delete();
    }

    public function updatePermissions(BlackList $blackListMember, array $permissions)
    {
        return $blackListMember->forceFill($permissions)->save();
    }
}
