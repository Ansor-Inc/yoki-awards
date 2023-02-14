<?php

namespace Modules\Purchase\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Purchase\Enums\BalanceType;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Purchase\Entities\Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
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

