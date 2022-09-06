<?php

namespace Modules\Group\Repositories;

use App\Models\BlackList;
use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Modules\Group\Repositories\Interfaces\BlackListRepositoryInterface;

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