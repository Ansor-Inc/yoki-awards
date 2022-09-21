<?php

namespace Modules\Group\Repositories;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Modules\Group\Repositories\Interfaces\MembershipRepositoryInterface;

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

    public function getApprovedMembersOfGroup(Group $group)
    {
        return $group->members()
            ->select('users.id', 'users.fullname', 'users.avatar', 'users.degree')
            ->selectRaw('IF(ISNULL(group_admins.id), FALSE, TRUE)  as is_admin')
            ->withPivot('approved')
            ->wherePivot('approved', true)
            ->leftJoin('group_admins', 'group_admins.membership_id', '=', 'memberships.id')
            ->get();
    }

    public function getPotentialMembersOfGroup(Group $group)
    {
        return $group->members()
            ->select('users.id', 'users.fullname', 'users.avatar', 'users.degree')
            ->withPivot('approved')
            ->wherePivot('approved', false)
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