<?php

namespace Modules\Purchase\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = \Modules\Purchase\Entities\Coupon::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'amount' => $this->faker->numberBetween(10, 10000000),
            'code' => $this->faker->lexify('********'),
            'expires_at' => now()->addMinutes(5),
            'status' => true
        ];
    }
}
