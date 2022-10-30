<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'complainer_id');
    }
}
