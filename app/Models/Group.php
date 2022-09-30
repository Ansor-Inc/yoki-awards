<?php

namespace App\Models;

use App\Models\Traits\HasGroupAdmins;
use App\Models\Traits\HasGroupPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Book\Enums\BookStatus;
use Modules\Group\Enums\GroupUserStatus;

class Group extends Model
{
    use HasFactory, HasGroupAdmins, HasGroupPermissions;

    protected $guarded = ['id', 'status'];

    protected $casts = [
        'degree_scope' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('approved', fn($query) => $query->where('status', BookStatus::APPROVED));
        static::created(function ($group) {
            $group->update(['invite_link' => Str::random()]);
        });
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')->wherePivot('approved', true);
    }

    public function currentUserMembershipStatus()
    {
        return $this->hasOne(Membership::class)->where('user_id', auth()->id());
    }

    public function hasMember(User|Authenticatable $user)
    {
        return $this->memberships()->approved()->where('user_id', $user->id)->exists();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function blackListMembers()
    {
        return $this->members()
            ->select('users.id as user_id', 'users.fullname', 'users.avatar', 'black_list.*')
            ->join('black_list', 'memberships.id', '=', 'black_list.membership_id');
    }

    public function isInBlackList(User $member)
    {
        return $this->blackListMembers()->where('user_id', $member->id)->exists();
    }

    public function getIsFullAttribute()
    {
        return (int)$this->memberships()->approved()->count() >= (int)$this->member_limit;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
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

    public function getCurrentUserJoinStatusAttribute()
    {
        $status = $this->currentUserMembershipStatus;

        if ($this->owner_id == auth()->id()) return GroupUserStatus::OWNER->value;
        if (is_null($status)) return GroupUserStatus::NOT_JOINED->value;
        if (!is_null($status->rejected_at)) return GroupUserStatus::REJECTED->value;
        if (!$status->approved) return GroupUserStatus::REQUESTED_TO_JOIN->value;

        return GroupUserStatus::JOINED->value;
    }
}
