<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\BookUserStatus;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\Membership;
use Modules\User\Contracts\CanResetPasswordContract;
use Modules\User\Enums\UserDegree;
use Modules\User\Filters\UserFilter;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id', 'remember_token'];

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

    public function scopeOfDegree(Builder $query, UserDegree $degree)
    {
        $query->where('degree', $degree->value);
    }

    public function bookUserStatuses()
    {
        return $this->hasMany(BookUserStatus::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function joinedGroups()
    {
        return $this->belongsToMany(Group::class, 'memberships')->wherePivot('approved', true);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function isWaitingForJoinApproval(Group $group)
    {
        return $this->memberships()->where('memberships.group_id', $group->id)->where('approved', false)->exists();
    }

    public function hasVerifiedPhone()
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

    public function readBooks()
    {
        return $this->belongsToMany(Book::class, 'book_reads');
    }

    public function scopeFilter(Builder $builder, array $filters)
    {
        (new UserFilter($builder))->apply($filters);
    }
}

