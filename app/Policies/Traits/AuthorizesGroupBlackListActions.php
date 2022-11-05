<?php

namespace App\Policies\Traits;

use App\Policies\Responses\GroupPolicyResponse;
use Illuminate\Auth\Access\Response;
use Modules\Group\Entities\BlackList;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

trait AuthorizesGroupBlackListActions
{
    public function getBlackList(User $user, Group $group): Response
    {
        return ($group->owner->is($user) || $group->currentUserPermissions['can_add_to_blacklist'])
            ? Response::allow()
            : GroupPolicyResponse::dontHaveEnoughPrivilege();
    }

    public function addToBlackList(User $user, Group $group, User $member): Response|bool
    {
        if ($group->isInBlackList($member)) return Response::deny('This user is already in blackList!');

        return $this->authorizeBlackListActions($user, $group, $member);
    }

    public function updateBlackListPermissions(User $user, Group $group, BlackList $blackListMember): Response|bool
    {
        return $this->authorizeBlackListActions($user, $group, $blackListMember->member);
    }

    public function removeFromBlackList(User $user, Group $group, BlackList $blackListMember): Response|bool
    {
        return $this->authorizeBlackListActions($user, $group, $blackListMember->member);
    }

    protected function authorizeBlackListActions(User $user, Group $group, User $member): Response|bool
    {
        if (!$group->hasMember($member))
            return GroupPolicyResponse::isNotMemberOfTheGroup();

        if ($user->is($member)) return false;

        return ($group->owner->is($user) || $group->currentUserPermissions['can_add_to_blacklist'])
            ? Response::allow()
            : GroupPolicyResponse::dontHaveEnoughPrivilege();
    }
}