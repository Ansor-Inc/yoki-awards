<?php

namespace Modules\Purchase\Actions\Checkout;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Enums\PurchaseStatus;

class CompletePurchaseAction
{
    public function execute(Purchase $purchase, Request $request)
    {
        $user = $request->user();
        $fromBalance = $request->input('from_balance');

        if (!$purchase->isPending()) {
            throw new Exception("Completed or cancelled purchase!");
        }

        if ((float)$purchase->amount !== (float)$fromBalance) {
            throw new Exception('Purchase amount does not equal to from balance field!');
        }

        if ($user->getBalance() < $fromBalance) {
            throw new Exception("User does not have enough balance!");
        }

        DB::transaction(function () use ($purchase, $user, $fromBalance) {
            $user->withdraw($fromBalance);
            $purchase->update([
                'from_balance' => $fromBalance,
                'state' => PurchaseStatus::COMPLETED->value
            ]);
        });
    }
}
