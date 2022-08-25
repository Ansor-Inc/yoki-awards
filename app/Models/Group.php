<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Book\Enums\BookStatus;

class Group extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'status'];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope('approved', fn($query) => $query->where('status', BookStatus::APPROVED));
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function getIsFullAttribute()
    {
        return $this->memberships()->approved()->count() >= $this->member_limit;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category()
    {
        return $this->belongsTo(GroupCategory::class, 'group_category_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category_id'] ?? false, function ($query) use ($filters) {
            $query->where('group_category_id', $filters['category_id']);
        });
    }

    public function scopeApproved($query)
    {
        $query->where('status', BookStatus::APPROVED);
    }


}
