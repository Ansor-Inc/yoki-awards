<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    use HasFactory;

    protected $table = 'black_list';

    protected $fillable = ['membership_id'];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function member()
    {
        return User::query()->select('users.id', 'users.fullname', 'users.avatar')
            ->join('memberships', 'memberships.user_id', '=', 'users.id')
            ->join('black_list', 'black_list.membership_id', '=', 'memberships.id');
    }

    public function getMemberAttribute()
    {
        return $this->member()->first();
    }

}
