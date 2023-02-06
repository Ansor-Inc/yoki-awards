<?php

namespace Modules\Book\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Book\Entities\Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'about' => $this->faker->text(),
            'copyright' => $this->faker->text(),
            'username' => $this->faker->userName(),
            'password' => $this->faker->password()
        ];
    }
}

