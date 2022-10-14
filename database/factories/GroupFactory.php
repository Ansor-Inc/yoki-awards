<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Group\Entities\GroupCategory;
use Modules\User\Enums\UserDegree;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Group\Entities\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'title' => fake()->title(),
            'group_category_id' => GroupCategory::factory(),
            'member_limit' => 3,
            'degree' => UserDegree::GENIUS->value,
            'is_private' => random_int(0, 1)
        ];
    }
}
