<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Blog\Entities\Article;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\BookUserStatus;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\Membership;
use Modules\Purchase\Entities\Purchase;
use Modules\Reaction\Traits\DisLiker;
use Modules\User\Entities\Traits\HasBalance;
use Modules\User\Entities\Traits\UsesCoupons;
use Modules\User\Enums\UserDegree;
use Modules\User\Filters\UserFilter;
use Modules\User\Interfaces\CanResetPasswordContract;
use Modules\Reaction\Traits\Liker;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed $balance
 * @property mixed $id
 */
class User extends Authenticatable implements CanResetPasswordContract
{
    use DisLiker;
    use HasApiTokens;
    use HasBalance;
    use HasFactory;
    use HasRoles;
    use Liker;
    use Notifiable;
    use UsesCoupons;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id', 'remember_token', 'balance'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    //Scopes:
    public function scopeOfDegree(Builder $query, UserDegree $degree): void
    {
        $query->where('degree', $degree->value);
    }

    public function scopeFilter(Builder $builder, array $filters): void
    {
        (new UserFilter($builder))->apply($filters);
    }

    //Relations:
    public function bookUserStatuses(): HasMany
    {
        return $this->hasMany(BookUserStatus::class);
    }

    public function articles(): MorphMany
    {
        return $this->morphMany(Article::class, 'user');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function joinedGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'memberships')->wherePivot('approved', true);
    }

    public function readBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_reads');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'complainer_id');
    }

    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class);
    }

    //Helper methods:
    public function isWaitingForJoinApproval(Group $group): bool
    {
        return $this->memberships()->where('memberships.group_id', $group->id)->where('approved', false)->exists();
    }

    public function complain(Complaint $complaint): Model|bool
    {
        return $this->complaints()->save($complaint);
    }

    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill(['phone_verified_at' => $this->freshTimestamp()])->save();
    }

    public function getPhoneForPasswordReset()
    {
        return $this->phone;
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(BloggerApplication::class, 'user_id');
    }

    public function currentDeviceToken(): object|null
    {
        return $this->tokens()
            ->where('user_agent', request()->userAgent())
            ->where('ip', request()->ip())
            ->first();
    }
}

