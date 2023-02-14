<?php

namespace Modules\Purchase\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Purchase\Actions\Checkout\CheckoutAction;
use Modules\Purchase\Actions\Checkout\CompletePurchaseAction;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Http\Requests\CheckoutRequest;
use Modules\Purchase\Http\Requests\CompletePurchaseRequest;

class CheckoutController extends Controller
{
    public function checkout(Purchase $purchase, CheckoutRequest $request, CheckoutAction $checkoutAction): Response|Application|ResponseFactory
    {
        try {
            $link = $checkoutAction->execute($purchase, $request);
            return response(["checkout_link" => $link]);
        } catch (Exception $exception) {
            return response(["message" => $exception->getMessage()], 403);
        }
    }

    public function complete(Purchase $purchase, CompletePurchaseRequest $request, CompletePurchaseAction $action)
    {
        try {
            $action->execute($purchase, $request);
            return response([
                'message' => 'Successfully completed purchase!',
                'completed' => true
            ]);
        } catch (Exception $exception) {
            return response([
                "message" => $exception->getMessage(),
                "completed" => false
            ], 403);
        }
    }
}
