<?php

namespace Modules\Group\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCategory extends Model
{
    use HasFactory;

    public function groups()
    {
        return $this->hasMany(Group::class)->approved()->whereNotNull('owner_id');
    }
}
