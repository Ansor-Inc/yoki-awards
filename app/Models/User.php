<?php

namespace App\Models;

use App\Contracts\CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->memberships()->where('approved', false)->exists();
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
}

