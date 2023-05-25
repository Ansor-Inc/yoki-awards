<?php

namespace Modules\Group\Entities;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Group\Entities\Traits\HasGroupAdmins;
use Modules\Group\Entities\Traits\HasGroupPermissions;
use Modules\Group\Enums\GroupStatus;
use Modules\Group\Enums\GroupUserStatus;
use Modules\Group\Filters\GroupFilter;
use Modules\Post\Entities\Post;
use Modules\User\Entities\User;

class Group extends Model
{
    use HasGroupAdmins;
    use HasGroupPermissions;

    protected $guarded = ['id'];

    protected $casts = ['degree_scope' => 'array'];

    //Actions to-do when model is booted
    protected static function booted(): void
    {
        static::addGlobalScope('approved', fn($query) => $query->approved()); //Always retrieve only approved groups
    }

    //Relations:
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function mostReviewedPosts(): Builder|HasMany
    {
        return $this->posts()
            ->select('id', 'title')
            ->has('comments')
            ->withCount('comments')
            ->orderBy('comments_count', 'DESC')
            ->limit(3);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GroupCategory::class, 'group_category_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')->wherePivot('approved', true);
    }

    public function potentialMembers(): BelongsToMany
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

    public function blackListMembers(): BelongsToMany
    {
        return $this->members()
            ->select('users.id as user_id', 'users.fullname', 'users.avatar', 'black_list.*')
            ->join('black_list', 'memberships.id', '=', 'black_list.membership_id');
    }

    //Helper methods:
    public function hasMember(User|Authenticatable $user)
    {
        return $this->memberships()->approved()->where('user_id', $user->id)->exists();
    }

    public function isInBlackList(User $member): bool
    {
        return $this->blackListMembers()->where('user_id', $member->id)->exists();
    }

    public function isFull(): bool
    {
        if (isset($this->members_count) && isset($this->member_limit)) {
            return (int)$this->members_count >= (int)$this->member_limit;
        }

        return (int)$this->memberships()->approved()->count() >= (int)$this->member_limit;
    }

    //Scopes:
    public function scopeFilter($query, array $filters): void
    {
        (new GroupFilter($query))->apply($filters);
    }

    public function scopeApproved($query): void
    {
        $query->where('status', GroupStatus::APPROVED->value);
    }

    //Attributes
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
