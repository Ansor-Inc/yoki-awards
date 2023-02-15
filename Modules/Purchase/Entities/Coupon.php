<?php

namespace Modules\Purchase\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Purchase\Database\factories\CouponFactory;
use Modules\User\Entities\User;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['code'];

    protected static function newFactory()
    {
        return CouponFactory::new();
    }

    protected static function booted()
    {
        static::addGlobalScope('onlyActiveCoupons', fn($query) => $query->where('status', true));
    }

    public function valid()
    {
        return $this->status && Carbon::create($this->expires_at)->isAfter(now());
    }

    public function validFor(User $user)
    {
        return $user->coupons()->where('coupon_id', $this->id)->doesntExist();
    }
}
