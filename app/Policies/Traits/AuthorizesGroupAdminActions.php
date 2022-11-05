<?php

namespace App\Policies\Traits;

use App\Policies\Responses\GroupPolicyResponse;
use Illuminate\Auth\Access\Response;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

trait AuthorizesGroupAdminActions
{
    public function getAdmins(User $user, Group $group): Response
    {
        return $group->owner->is($user)
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOfTheGroup();
    }

    public function assignAsAdmin(User $user, Group $group, User $member): Response|bool
    {
        if (!$group->owner->is($user)) return GroupPolicyResponse::notOwnerOfTheGroup();
        if (!$group->hasMember($member)) return GroupPolicyResponse::isNotMemberOfTheGroup();
        if ($group->hasAdmin($member)) return Response::deny('Provided user already assigned as admin!');
        if ($user->is($member)) return Response::deny('You cannot assign yourself as admin!');

        return true;
    }

    public function dischargeAdmin(User $user, Group $group): Response
    {
        return $group->owner->is($user)
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOfTheGroup();
    }

    public function updateAdminPermissions(User $user, Group $group): Response
    {
        return $group->owner->is($user)
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOfTheGroup();
    }
}