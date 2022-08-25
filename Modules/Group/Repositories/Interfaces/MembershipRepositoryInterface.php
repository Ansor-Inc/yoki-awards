<?php

namespace Modules\Group\Repositories\Interfaces;

use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface MembershipRepositoryInterface
{
    public function createMembership(Group $group, User|Authenticatable $user);

    public function removeMembership(Group $group, User|Authenticatable $user);

    public function getApprovedMembersOfGroup(Group $group);

    public function getPotentialMembersOfGroup(Group $group);

    public function acceptMembership(Group $group, User|Authenticatable $user);

    public function rejectMembership(Group $group, User|Authenticatable $user);
}