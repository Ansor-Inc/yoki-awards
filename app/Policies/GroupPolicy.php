<?php

namespace App\Policies;

use App\Policies\Traits\AuthorizesGroupAdminActions;
use App\Policies\Traits\AuthorizesGroupBlackListActions;
use App\Policies\Traits\AuthorizesGroupPostActions;
use App\Policies\Traits\AuthorizesMembershipActions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

class GroupPolicy
{
    use HandlesAuthorization;
    use AuthorizesMembershipActions,
        AuthorizesGroupAdminActions,
        AuthorizesGroupBlackListActions,
        AuthorizesGroupPostActions;

    public function show(User $user, Group $group)
    {
        return $this->isOwner($user, $group) || $group->hasMember($user);
    }

    public function update(User $user, Group $group)
    {
        return $this->isOwner($user, $group) || $group->currentUserPermissions['can_update_group'];
    }

    public function delete(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    public function joinGroup(User $user, Group $group)
    {
        if ($this->isOwner($user, $group)) return $this->deny('You are the owner of this group!');
        if ($group->is_full) return $this->deny('This group is full!');
        if (!in_array($user->degree, $group->degree_scope ?? [])) return $this->deny('Your degree is not sufficient to join this group!');
        if ($user->isWaitingForJoinApproval($group)) return $this->deny('You have already requested to join this group!');

        return true;
    }

    public function leaveGroup(User $user, Group $group)
    {
        return $group->hasMember($user) ? true : $this->deny('You are not the member of this group yet!');
    }
}
