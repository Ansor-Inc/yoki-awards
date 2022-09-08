<?php

namespace App\Policies\Traits;

use App\Models\BlackList;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait AuthorizesGroupBlackListActions
{
    public function getBlackList(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    public function addToBlackList(User $user, Group $group, User $member)
    {
        if ($group->isInBlackList($member)) return Response::deny('This user is already in blackList!');

        return $this->authorizeBlackListActions($user, $group, $member);
    }

    public function updateBlackListPermissions(User $user, Group $group, BlackList $blackListMember)
    {
        return $this->authorizeBlackListActions($user, $group, $blackListMember->member);
    }

    public function removeFromBlackList(User $user, Group $group, BlackList $blackListMember)
    {
        return $this->authorizeBlackListActions($user, $group, $blackListMember->member);
    }

    protected function authorizeBlackListActions(User $user, Group $group, User $member)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');
        if (!$group->hasMember($member)) return Response::deny('The given user is not member of the group!');
        if ($user->id === $member->id) return false;

        return true;
    }
}