<?php

namespace Modules\Group\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Membership extends Model
{
    protected $guarded = ['id'];

    public function scopeApproved($query)
    {
        $query->where('approved', true);
    }

    public function scopeUnApproved($query)
    {
        $query->where('approved', false);
    }

    public function isRejected()
    {
        return !is_null($this->rejected_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
