<?php

namespace Modules\Purchase\DTO;

use Modules\Purchase\Entities\Purchase;
use Spatie\LaravelData\Data;

class PurchaseData extends Data
{
    public function __construct(
        public string $title,
        public float  $amount,
        public int    $count,
        public string $itemCode,
        public string $packageCode,
        public int    $vatPercent
    )
    {
    }

    public static function fromModel(Purchase $purchase): static
    {
        return static::from([
            'title' => $purchase->book->title,
            'amount' => $purchase->getPaidAmount(),
            'count' => 1,
            'itemCode' => $purchase->book->code,
            'packageCode' => $purchase->book->package_code,
            'vatPercent' => (int)setting('vat_percent', 0)
        ]);
    }

}
