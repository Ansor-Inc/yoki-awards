<?php

namespace Modules\Group\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Group\Entities\GroupCategory;

class GroupCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true)
        ];
    }
}

