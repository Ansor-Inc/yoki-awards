<?php

namespace Modules\User\Entities\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Coupon;

trait UsesCoupons
{
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_uses');
    }

    public function activateCoupon(Coupon $coupon): void
    {
        DB::transaction(function () use ($coupon) {
            $this->useTheCoupon($coupon);
            $this->deposit((int)$coupon->amount);
        });
    }

    public function useTheCoupon(Coupon $coupon): void
    {
        $this->coupons()->attach($coupon->id);
    }
}
