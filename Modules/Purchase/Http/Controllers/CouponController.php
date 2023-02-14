<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Purchase\Entities\Coupon;
use Modules\Purchase\Http\Requests\ActivateCouponRequest;

class CouponController extends Controller
{
    public function activateCoupon(ActivateCouponRequest $request)
    {
        $user = $request->user();

        $coupon = Coupon::query()->where('code', $request->input('code'))->firstOrFail();

        if ($coupon->valid() and $coupon->validFor($user)) {
            $user->activateCoupon($coupon);
            return response(['message' => 'Kupon aktivlashtirildi!']);
        }

        return response(['message' => 'Yaroqsiz yoki ishlatilgan kupon!'], 422);
    }
}
