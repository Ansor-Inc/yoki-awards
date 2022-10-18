<?php

namespace Modules\Group\Entities;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Modules\Book\Enums\BookStatus;
use Modules\Group\Entities\Traits\HasGroupAdmins;
use Modules\Group\Entities\Traits\HasGroupPermissions;
use Modules\Group\Enums\GroupStatus;
use Modules\Group\Enums\GroupUserStatus;
use Modules\User\Entities\User;
use function auth;

class Group extends Model
{
    use HasGroupAdmins, HasGroupPermissions;

    protected $guarded = ['id', 'status'];

    protected $casts = [
        'degree_scope' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('approved', fn($query) => $query->where('status', GroupStatus::APPROVED->value));
        static::created(function ($group) {
            $group->update(['invite_link' => Str::random()]);
        });
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')->wherePivot('approved', true);
    }

    public function potentialMembers()
    {
        return $this->belongsToMany(User::class, 'memberships')->wherePivot('approved', false);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function currentUserMembershipStatus(): HasOne
    {
        return $this->hasOne(Membership::class)->where('user_id', auth()->id());
    }

    public function hasMember(User|Authenticatable $user)
    {
        return $this->memberships()->approved()->where('user_id', $user->id)->exists();
    }

    public function blackListMembers(): BelongsToMany
    {
        return $this->members()
            ->select('users.id as user_id', 'users.fullname', 'users.avatar', 'black_list.*')
            ->join('black_list', 'memberships.id', '=', 'black_list.membership_id');
    }

    public function isInBlackList(User $member): bool
    {
        return $this->blackListMembers()->where('user_id', $member->id)->exists();
    }

    public function getIsFullAttribute(): bool
    {
        return (int)$this->memberships()->approved()->count() >= (int)$this->member_limit;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GroupCategory::class, 'group_category_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category_id'] ?? false, function ($query) use ($filters) {
            $query->where('group_category_id', $filters['category_id']);
        });
    }

    public function getCurrentUserJoinStatusAttribute(): string
    {
        $status = $this->currentUserMembershipStatus;

        if ($this->owner_id == auth()->id()) return GroupUserStatus::OWNER->value;
        if (is_null($status)) return GroupUserStatus::NOT_JOINED->value;
        if (!is_null($status->rejected_at)) return GroupUserStatus::REJECTED->value;
        if (!$status->approved) return GroupUserStatus::REQUESTED_TO_JOIN->value;

        return GroupUserStatus::JOINED->value;
    }
}
