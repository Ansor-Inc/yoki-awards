<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    use HandlesAuthorization;

    public function getMembers(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    /**
     * Determine whether the user can delete the model.
     *
     */
    public function delete(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    public function joinGroup(User $user, Group $group)
    {
        if ($this->isOwner($user, $group)) return Response::deny('You are the owner of this group!');

        if ($group->is_full) return Response::deny('This group is full!');

        if ($user->isWaitingForJoinApproval($group)) return Response::deny('You have already requested to join this group!');

        return true;
    }

    public function leaveGroup(User $user, Group $group)
    {
        $isMemberOfTheGroup = request()->user()->joinedGroups()->where(['groups.id' => $group->id, 'memberships.approved' => true])->exists();

        return $isMemberOfTheGroup ? true : Response::deny('You are not the member of this group yet!');
    }

    public function acceptMember(User $user, Group $group, User $potentialMember)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');

        if ($group->is_full) return Response::deny('This group is full!');

        if (!$potentialMember->isWaitingForJoinApproval($group)) return Response::deny('This member has not requested to join the group yet!');

        return true;
    }

    protected function isOwner(User $user, Group $group)
    {
        return $user->id === $group->owner_id;
    }
}
