<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fullname' => $this->faker->name(),
            'avatar' => $this->faker->image(),
            'phone' => $this->faker->numerify('############'),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'birthdate' => $this->faker->date(),
            'email' => $this->faker->email(),
            'region' => $this->faker->country(),
            'verified' => true,
            'phone_verified_at' => now(),
            'password' => Hash::make('password')
        ];
    }
}

