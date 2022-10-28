<?php

namespace Modules\Book\Helpers;

use Illuminate\Database\Eloquent\Collection;

class BookRatingPercentage
{
    /**
     * @return array<int, array{rating:int, percentage:int}>
     */
    public static function makeFrom(Collection|array $ratingPercentages): array
    {
        if (!$ratingPercentages instanceof Collection) {
            $ratingPercentages = collect($ratingPercentages);
        }

        return collect([1, 2, 3, 4, 5])
            ->map(function ($rating) use ($ratingPercentages) {
                $key = $ratingPercentages->search(fn($item) => $item['rating'] === $rating);
                return ['rating' => $rating, 'percentage' => ($key === false) ? 0 : (int)$ratingPercentages->get($key)['percentage']];
            })->toArray();
    }
}