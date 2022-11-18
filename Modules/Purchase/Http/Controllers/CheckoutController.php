<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Purchase\Entities\Purchase;

class CheckoutController extends Controller
{
    public function checkout(Purchase $purchase)
    {
        return response([
            "message" => "Checkout with payment system is not available now"
        ], 500);
    }
}