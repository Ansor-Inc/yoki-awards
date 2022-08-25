<?php

namespace Database\Factories;

use App\Models\GroupCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Enums\UserDegree;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
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
