<?php

namespace App\Policies;

use App\Policies\Responses\GroupPolicyResponse;
use App\Policies\Traits\AuthorizesGroupAdminActions;
use App\Policies\Traits\AuthorizesGroupBlackListActions;
use App\Policies\Traits\AuthorizesGroupPostActions;
use App\Policies\Traits\AuthorizesMembershipActions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

class GroupPolicy
{
    use HandlesAuthorization;
    use AuthorizesMembershipActions,
        AuthorizesGroupAdminActions,
        AuthorizesGroupBlackListActions,
        AuthorizesGroupPostActions;

    public function show(User $user, Group $group): Response
    {
        return ($group->owner->is($user) || $group->hasMember($user))
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOrMemberOfTheGroup();
    }

    public function update(User $user, Group $group): Response
    {
        return ($group->owner->is($user) || $group->currentUserPermissions['can_update_group'])
            ? Response::allow()
            : GroupPolicyResponse::dontHaveEnoughPrivilege();
    }

    public function delete(User $user, Group $group): Response
    {
        return $group->owner->is($user)
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOfTheGroup();
    }

    public function joinGroup(User $user, Group $group): Response|bool
    {
        if ($group->owner->is($user))
            return GroupPolicyResponse::isOwnerOfTheGroup();

        if ($group->isFull())
            return GroupPolicyResponse::groupIsFull();

        //if (!in_array($user->degree, $group->degree_scope ?? [])) return $this->deny('Your degree is not sufficient to join this group!');

        if ($user->isWaitingForJoinApproval($group))
            return GroupPolicyResponse::hasAlreadyRequestedToJoin();

        return true;
    }

    public function leaveGroup(User $user, Group $group): Response
    {
        return $group->hasMember($user)
            ? Response::allow()
            : GroupPolicyResponse::notMemberOfTheGroup();
    }
}
