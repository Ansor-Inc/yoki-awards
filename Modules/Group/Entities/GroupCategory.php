<?php

namespace Modules\Group\Entities;

use Illuminate\Database\Eloquent\Model;

class GroupCategory extends Model
{
    public function groups()
    {
        return $this->hasMany(Group::class)->approved()->whereNotNull('owner_id');
    }
}
