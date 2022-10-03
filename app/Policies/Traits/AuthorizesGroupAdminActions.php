<?php

namespace App\Policies\Traits;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait AuthorizesGroupAdminActions
{
    public function getAdmins(User $user, Group $group)
    {
        return $this->isOwner($user, $group);
    }

    public function assignAsAdmin(User $user, Group $group, User $member)
    {
        if (!$this->isOwner($user, $group)) return Response::deny('You are not the owner of this group!');
        if (!$group->hasMember($member)) return Response::deny('Provided user is not a member of this group!');
        if ($group->hasAdmin($member)) return Response::deny('Provided user already assigned as admin!');
        if ($user->id === $member->id) return Response::deny('You cannot assign yourself as admin!');

        return true;
    }

    public function dischargeAdmin(User $user, Group $group)
    {
        return $this->isOwner($user, $group) ? true : Response::deny('You are not the owner of this group!');
    }

    public function updateAdminPermissions(User $user, Group $group)
    {
        return $this->isOwner($user, $group) ? true : Response::deny('You are not the owner of this group!');
    }
}