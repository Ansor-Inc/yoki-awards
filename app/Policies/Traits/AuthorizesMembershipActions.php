<?php

namespace App\Policies\Traits;

use App\Policies\Responses\GroupPolicyResponse;
use Illuminate\Auth\Access\Response;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\Membership;
use Modules\User\Entities\User;

trait AuthorizesMembershipActions
{
    public function getApprovedMembers(User $user, Group $group): Response
    {
        return ($group->owner->is($user) || $group->hasMember($user))
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOrMemberOfTheGroup();
    }

    public function getPendingMembers(User $user, Group $group): Response
    {
        return $group->owner->is($user)
            ? Response::allow()
            : GroupPolicyResponse::notOwnerOfTheGroup();
    }

    public function acceptMember(User $user, Group $group, User $potentialMember): Response|bool
    {
        if (!$group->owner->is($user))
            return GroupPolicyResponse::notOwnerOfTheGroup();

        if ($group->isFull())
            return GroupPolicyResponse::groupIsFull();

        //if (!in_array($potentialMember->degree, $group->degree_scope)) return Response::deny(__('group.not_enough_degree'));

        return $this->validateMembership($group, $potentialMember);
    }

    public function rejectMember(User $user, Group $group, User $potentialMember): Response|bool
    {
        if (!$group->owner->is($user))
            return GroupPolicyResponse::notOwnerOfTheGroup();

        return $this->validateMembership($group, $potentialMember);
    }

    public function removeMember(User $user, Group $group, User $member): Response|bool
    {
        if (!$group->owner->is($user))
            return GroupPolicyResponse::notOwnerOfTheGroup();

        if (!$group->hasMember($member))
            return GroupPolicyResponse::isNotMemberOfTheGroup();

        return true;
    }

    protected function validateMembership(Group $group, User $potentialMember): Response|bool
    {
        $membership = $this->getMembership($group, $potentialMember);

        if (is_null($membership))
            return GroupPolicyResponse::hasNotRequestedToJoinYet();

        if ($membership->approved)
            return GroupPolicyResponse::hasAlreadyBeenAccepted();

        if ($membership->isRejected())
            return GroupPolicyResponse::hasBeenRejectedToJoin();

        return true;
    }

    protected function getMembership(Group $group, User $user)
    {
        return Membership::query()->where('user_id', $user->id)->where('group_id', $group->id)->first();
    }
}