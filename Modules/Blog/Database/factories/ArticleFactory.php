<?php

namespace Modules\Blog\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Enums\ArticleStatus;
use Modules\User\Database\factories\UserFactory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Blog\Entities\Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(),
            'body' => $this->faker->paragraph(),
            'user_type' => 'user',
            'user_id' => UserFactory::new()->create()->id,
            'status' => ArticleStatus::PUBLISHED->value,
        ];
    }
}

