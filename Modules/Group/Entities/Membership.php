<?php

namespace Modules\Group\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function isRejected(): bool
    {
        return !is_null($this->rejected_at);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
