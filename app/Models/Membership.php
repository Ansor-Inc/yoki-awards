<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = ['id'];

    public function scopeApproved($query)
    {
        $query->where('approved', true);
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
