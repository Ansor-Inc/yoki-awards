<?php

namespace Modules\Group\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\Membership;
use Modules\GroupInterfaces\Interfaces\MembershipRepositoryInterface;
use Modules\User\Entities\User;

class MembershipRepository implements MembershipRepositoryInterface
{
    public function createMembership(Group $group, User|Authenticatable $user)
    {
        return Membership::query()->create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'approved' => !$group->is_private
        ]);
    }

    public function removeMembership(Group $group, User|Authenticatable $user)
    {
        $group->memberships()->where('user_id', $user->id)->delete();
    }

    public function getApprovedMembersOfGroup(Group $group, array $filters)
    {
        $query = $group->members()
            ->filter($filters)
            ->select('users.id', 'users.fullname', 'users.avatar', 'users.degree')
            ->selectRaw('IF(ISNULL(group_admins.id), FALSE, TRUE)  as is_admin')
            ->withPivot('approved')
            ->wherePivot('approved', true)
            ->leftJoin('group_admins', 'group_admins.membership_id', '=', 'memberships.id');

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function getPotentialMembersOfGroup(Group $group)
    {
        return $group->potentialMembers()
            ->select('users.id', 'users.fullname', 'users.avatar', 'users.degree')
            ->withPivot('approved')
            ->get();
    }

    public function acceptMembership(Group $group, User|Authenticatable $user)
    {
        $group->memberships()->where('user_id', $user->id)->update(['approved' => true]);
    }

    public function rejectMembership(Group $group, User|Authenticatable $user)
    {
        $group->memberships()->where('user_id', $user->id)->update(['rejected_at' => now()]);
    }
}
