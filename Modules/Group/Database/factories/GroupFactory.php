<?php

namespace Modules\Group\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Group\Entities\Group;
use Modules\Group\Enums\GroupStatus;
use Modules\User\Database\factories\UserFactory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'owner_id' => UserFactory::new(),
            'member_limit' => 10,
            'degree_scope' => [],
            'is_private' => false,
            'invite_link' => $this->faker->slug,
            'status' => GroupStatus::APPROVED->value
        ];
    }
}

