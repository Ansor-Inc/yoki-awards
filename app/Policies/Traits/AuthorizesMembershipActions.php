<?php

namespace App\Policies\Traits;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait AuthorizesMembershipActions
{
    public function getApprovedMembers(User $user, Group $group)
    {
        return $this->isOwner($user, $group) || $group->hasMember($user);
    }

    public function getPendingMembers(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    public function acceptMember(User $user, Group $group, User $potentialMember)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');

        if ($group->is_full) return Response::deny('This group is full!');

        if (!in_array($potentialMember->degree, $group->degree_scope)) return Response::deny('Do not have enough degree!');

        return $this->validateMembership($group, $potentialMember);
    }

    public function rejectMember(User $user, Group $group, User $potentialMember)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');

        return $this->validateMembership($group, $potentialMember);
    }

    public function removeMember(User $user, Group $group, User $member)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');
        if (!$group->hasMember($member)) return Response::deny('This user is not member of this group!');

        return true;
    }

    protected function validateMembership(Group $group, User $potentialMember)
    {
        $membership = $this->getMembership($group, $potentialMember);

        if (is_null($membership)) return Response::deny('This member has not requested to join the group yet!');

        if ($membership->approved) return Response::deny('This user has already been accepted!');

        if ($membership->isRejected()) return Response::deny('This user has been rejected to join this group!');

        return true;
    }

    protected function getMembership(Group $group, User $user)
    {
        return Membership::query()->where('user_id', $user->id)->where('group_id', $group->id)->first();
    }

    protected function isOwner(User $user, Group $group)
    {
        return ((int)$user->id === (int)$group->owner_id);
    }
}