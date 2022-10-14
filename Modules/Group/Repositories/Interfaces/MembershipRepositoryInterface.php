<?php

namespace Modules\Group\Repositories\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

interface MembershipRepositoryInterface
{
    public function createMembership(Group $group, User|Authenticatable $user);

    public function removeMembership(Group $group, User|Authenticatable $user);

    public function getApprovedMembersOfGroup(Group $group, array $filters);

    public function getPotentialMembersOfGroup(Group $group);

    public function acceptMembership(Group $group, User|Authenticatable $user);

    public function rejectMembership(Group $group, User|Authenticatable $user);
}