<?php

namespace App\Models\Traits;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Arr;

trait HasGroupAdmins
{
    public function admins()
    {
        return $this->members()
            ->select('users.id as user_id', 'users.fullname', 'users.avatar', 'group_admins.*')
            ->join('group_admins', 'memberships.id', '=', 'group_admins.membership_id');
    }

    public function hasAdmin(User $user)
    {
        return $this->admins()->wherePivot('user_id', $user->id)->exists();
    }

}